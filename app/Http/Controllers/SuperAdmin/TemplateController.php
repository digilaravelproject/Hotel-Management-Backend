<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvTemplate;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates.
     */
    public function index()
    {
        $templates = TvTemplate::orderBy('id', 'desc')->paginate(10);
        return view('super_admin.templates.index', compact('templates'));
    }

    /**
     * Store a newly uploaded zip template.
     */
    public function store(Request $request)
    {
        $request->validate([
            'template_file' => 'required|file|mimes:zip|max:51200', // Max 50MB
        ]);

        // Get latest template version
        $latest = TvTemplate::orderBy('id', 'desc')->first();
        if (!$latest) {
            $nextVersion = '1.0';
        } else {
            $nextVersion = number_format(floatval($latest->version) + 0.5, 1);
        }

        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            
            // Generate distinct zip file name
            $fileName = 'template_v' . str_replace('.', '_', $nextVersion) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/templates'), $fileName);
            $filePath = 'uploads/templates/' . $fileName;

            // Deactivate previous active templates
            TvTemplate::query()->update(['is_active' => false]);

            // Save new template record
            TvTemplate::create([
                'version' => $nextVersion,
                'file_path' => $filePath,
                'is_active' => true,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'TV Template version ' . $nextVersion . ' uploaded successfully!'
                ]);
            }

            return back()->with('success', 'TV Template version ' . $nextVersion . ' uploaded successfully!');
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
     * Toggle the active status of a template.
     */
    public function toggleActive(int $id)
    {
        $template = TvTemplate::findOrFail($id);
        
        if (!$template->is_active) {
            // Deactivate all others first
            TvTemplate::query()->update(['is_active' => false]);
            $template->is_active = true;
            $template->save();
        } else {
            $template->is_active = false;
            $template->save();
        }

        return back()->with('success', 'Template active status updated successfully!');
    }
}
