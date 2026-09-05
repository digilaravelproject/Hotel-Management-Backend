<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvTemplate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Events\TvConfigUpdatedEvent;
use App\Services\TvVersionCacheService;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates grouped by themes.
     */
    public function index()
    {
        $templates = TvTemplate::orderBy('theme_id', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        // Fetch distinct registered themes
        $existingThemes = TvTemplate::select('theme_id', 'theme_name', 'preview_image')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('theme_id')
            ->sortBy('theme_id')
            ->values();

        // Calculate the next suggested theme ID
        $maxThemeId = TvTemplate::max('theme_id');
        $nextThemeId = $maxThemeId ? ((int) $maxThemeId + 1) : 1;

        return view('super_admin.templates.index', compact('templates', 'existingThemes', 'nextThemeId'));
    }

    /**
     * Store a newly uploaded zip template for a specific Theme ID.
     */
    public function store(Request $request)
    {
        // Increase limits for large file processing on shared hosting
        @set_time_limit(300);
        @ini_set('memory_limit', '256M');

        $request->validate([
            'theme_id' => 'required|integer|min:1',
            'theme_name' => 'nullable|string|max:100',
            'custom_version' => 'nullable|string|max:20',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB
            'template_file' => 'required|file|mimes:zip|max:51200', // 50MB
        ]);

        $targetThemeId = (int) $request->input('theme_id');

        // Look for the latest build for this specific theme
        $latestForTheme = TvTemplate::where('theme_id', $targetThemeId)
            ->orderBy('id', 'desc')
            ->first();

        // Determine Version number
        if ($request->filled('custom_version')) {
            $nextVersion = trim($request->input('custom_version'));
        } elseif ($latestForTheme) {
            $nextVersion = number_format(floatval($latestForTheme->version) + 0.5, 1);
        } else {
            $nextVersion = '1.0';
        }

        // Determine Theme Name
        if ($request->filled('theme_name')) {
            $themeName = trim($request->input('theme_name'));
        } elseif ($latestForTheme && !empty($latestForTheme->theme_name)) {
            $themeName = $latestForTheme->theme_name;
        } else {
            $themeName = 'Theme ' . $targetThemeId;
        }

        // Handle Preview Image
        $previewImagePath = null;
        if ($request->hasFile('preview_image')) {
            $previewFile = $request->file('preview_image');
            $previewFileName = 'theme_' . $targetThemeId . '_preview_' . time() . '.' . $previewFile->getClientOriginalExtension();
            $previewImagePath = Storage::disk('public')->putFileAs('templates/previews', $previewFile, $previewFileName);
        } elseif ($latestForTheme && !empty($latestForTheme->preview_image)) {
            // Carry forward existing preview image if no new one was provided
            $previewImagePath = $latestForTheme->preview_image;
        }

        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            
            // Generate distinct zip file name
            $fileName = 'theme_' . $targetThemeId . '_v' . str_replace('.', '_', $nextVersion) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('templates', $file, $fileName);

            // Deactivate previous active builds ONLY for this specific theme_id
            TvTemplate::where('theme_id', $targetThemeId)->update(['is_active' => false]);
            
            // Clear TV version caches
            TvVersionCacheService::clearAllHotelsCache();

            // Save new template build
            $template = TvTemplate::create([
                'theme_id' => $targetThemeId,
                'theme_name' => $themeName,
                'version' => $nextVersion,
                'file_path' => $filePath,
                'preview_image' => $previewImagePath,
                'is_active' => true,
            ]);

            // Dispatch event for real-time TV FCM & Firestore sync
            event(new TvConfigUpdatedEvent(null, 'TEMPLATE', null, ['theme_id' => $targetThemeId, 'version' => $nextVersion]));

            $successMsg = "Theme {$targetThemeId} ({$themeName}) v{$nextVersion} uploaded and deployed successfully!";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMsg,
                    'data' => $template,
                ]);
            }

            return back()->with('success', $successMsg);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload template file.'
            ], 400);
        }

        return back()->with('error', 'Failed to upload template file.');
    }

    /**
     * Toggle the active status of a specific template build within its theme.
     */
    public function toggleActive(int $id)
    {
        $template = TvTemplate::findOrFail($id);
        
        if (!$template->is_active) {
            // Deactivate all others for this specific theme_id only
            TvTemplate::where('theme_id', $template->theme_id)->update(['is_active' => false]);
            $template->is_active = true;
            $template->save();
        } else {
            $template->is_active = false;
            $template->save();
        }

        TvVersionCacheService::clearAllHotelsCache();
        event(new TvConfigUpdatedEvent(null, 'TEMPLATE', null, ['theme_id' => $template->theme_id]));

        return back()->with('success', "Theme #{$template->theme_id} active status updated successfully!");
    }
}
