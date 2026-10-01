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
     * Display a listing of templates (newest builds first).
     */
    public function index(Request $request)
    {
        $query = TvTemplate::query();

        if ($request->filled('theme_id')) {
            $query->where('theme_id', $request->theme_id);
        }

        // Always show the newest/latest uploaded builds first so Theme 2 or any update isn't buried
        $templates = $query->orderBy('id', 'desc')->paginate(15);

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
     * After storing, the zip is auto-extracted to public/themes/ so it
     * can be accessed directly via URL (no login required).
     */
    public function store(Request $request)
    {
        // Increase limits for large file processing on shared hosting
        @set_time_limit(300);
        @ini_set('memory_limit', '256M');

        $request->validate([
            'theme_id'       => 'required|integer|min:1',
            'theme_name'     => 'nullable|string|max:100',
            'custom_version' => 'nullable|string|max:20',
            'preview_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB
            'template_file'  => 'required|file|mimes:zip|max:51200', // 50MB
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
            $previewFile     = $request->file('preview_image');
            $previewFileName = 'theme_' . $targetThemeId . '_preview_' . time() . '.' . $previewFile->getClientOriginalExtension();
            $previewImagePath = Storage::disk('public')->putFileAs('templates/previews', $previewFile, $previewFileName);
        } elseif ($latestForTheme && !empty($latestForTheme->preview_image)) {
            // Carry forward existing preview image if no new one was provided
            $previewImagePath = $latestForTheme->preview_image;
        }

        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');

            // ── 1. Store the original zip ──────────────────────────────────────
            $fileName = 'theme_' . $targetThemeId . '_v' . str_replace('.', '_', $nextVersion) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('templates', $file, $fileName);

            // ── 2. Extract zip into public/themes/theme_{id}/v{safe_version}/ ─
            $safeVersion   = str_replace('.', '_', $nextVersion);
            $extractFolder = 'themes/theme_' . $targetThemeId . '/v' . $safeVersion . '_' . time();
            $extractFullPath = public_path($extractFolder);

            $extractedPath = null;
            if (class_exists('ZipArchive')) {
                $zip    = new \ZipArchive();
                $zipFullPath = Storage::disk('public')->path($filePath);

                if ($zip->open($zipFullPath) === true) {
                    // Remove old extracted folder for this theme (cleanup)
                    if ($latestForTheme && !empty($latestForTheme->extracted_path)) {
                        $oldExtractedFullPath = public_path($latestForTheme->extracted_path);
                        if (is_dir($oldExtractedFullPath)) {
                            $this->deleteDirectory($oldExtractedFullPath);
                        }
                    }

                    @mkdir($extractFullPath, 0755, true);
                    $zip->extractTo($extractFullPath);
                    $zip->close();
                    $extractedPath = $extractFolder;
                }
            }

            // ── 3. Deactivate previous active builds for this theme_id ─────────
            TvTemplate::where('theme_id', $targetThemeId)->update(['is_active' => false]);

            // Clear TV version caches
            TvVersionCacheService::clearAllHotelsCache();

            // ── 4. Save new template build ─────────────────────────────────────
            $template = TvTemplate::create([
                'theme_id'       => $targetThemeId,
                'theme_name'     => $themeName,
                'version'        => $nextVersion,
                'file_path'      => $filePath,
                'extracted_path' => $extractedPath,
                'preview_image'  => $previewImagePath,
                'is_active'      => true,
            ]);

            // Dispatch event for real-time TV FCM & Firestore sync
            event(new TvConfigUpdatedEvent(null, 'TEMPLATE', null, ['theme_id' => $targetThemeId, 'version' => $nextVersion]));

            $previewUrl   = $extractedPath ? url($extractedPath . '/index.html') : null;
            $successMsg   = "Theme {$targetThemeId} ({$themeName}) v{$nextVersion} uploaded and deployed successfully!";
            if ($previewUrl) {
                $successMsg .= " Preview: {$previewUrl}";
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success'     => true,
                    'message'     => $successMsg,
                    'preview_url' => $previewUrl,
                    'data'        => $template,
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
     * Recursively delete a directory and its contents.
     */
    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
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

    /**
     * Redirect to the live preview URL of an extracted theme.
     * Route: GET /super-admin/templates/{id}/preview
     */
    public function preview(int $id)
    {
        $template = TvTemplate::findOrFail($id);

        if (empty($template->extracted_path)) {
            return back()->with('error', 'This template has no extracted preview. Please re-upload the zip to generate a preview.');
        }

        $indexFile = public_path($template->extracted_path . '/index.html');

        if (!file_exists($indexFile)) {
            // Try index.htm fallback
            $indexFile = public_path($template->extracted_path . '/index.htm');
        }

        if (!file_exists($indexFile)) {
            return back()->with('error', 'index.html not found inside the extracted theme folder. Make sure your zip contains an index.html at the root level.');
        }

        // Redirect to the public URL of the extracted theme
        $previewUrl = url($template->extracted_path . '/index.html');

        return redirect($previewUrl);
    }
}
