<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;
use App\Models\OttPlatform;
use Illuminate\Support\Str;

class OttMasterController extends Controller
{
    /**
     * Display a listing of all master OTT / Applications.
     */
    public function index()
    {
        $platforms = OttPlatform::orderBy('id', 'desc')->get();
        return view('super_admin.ott_master.index', compact('platforms'));
    }

    /**
     * Store a newly created OTT / Application master in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'package_name' => 'required|string|max:255|unique:ott_platforms,package_name',
            'icon' => $request->hasFile('icon')
                ? 'nullable|file|mimes:png,jpg,jpeg,webp|max:2048'
                : 'nullable|string|max:500',
        ]);

        $iconPath = null;

        // Handle icon upload securely (rejects SVG/PHP; re-encodes via GD into WebP)
        if ($request->hasFile('icon')) {
            $iconPath = ImageHelper::compressAndConvertToWebp(
                $request->file('icon'),
                'images/ott',
                250,
                Str::slug($request->name),
                512
            );
        } elseif (is_string($request->icon) && !empty($request->icon)) {
            $iconPath = $this->sanitizeIconPath($request->icon);
        } else {
            // Check if default SVG exists for this name
            $defaultSvg = 'images/ott/' . Str::slug($request->name) . '.svg';
            if (file_exists(public_path($defaultSvg))) {
                $iconPath = $defaultSvg;
            }
        }

        $platform = OttPlatform::create([
            'name' => $request->name,
            'package_name' => $request->package_name,
            'icon' => $iconPath,
            'status' => true,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Application added to master catalog successfully.',
                'platform' => $platform
            ]);
        }

        return redirect()->back()->with('success', 'Application added to master catalog successfully.');
    }

    /**
     * Update the specified OTT / Application master record.
     */
    public function update(Request $request, int $id)
    {
        $platform = OttPlatform::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'package_name' => 'required|string|max:255|unique:ott_platforms,package_name,' . $id,
            'icon' => $request->hasFile('icon')
                ? 'nullable|file|mimes:png,jpg,jpeg,webp|max:2048'
                : 'nullable|string|max:500',
        ]);

        $updateData = [
            'name' => $request->name,
            'package_name' => $request->package_name,
        ];

        // Handle new icon upload securely (rejects SVG/PHP; re-encodes via GD into WebP)
        if ($request->hasFile('icon')) {
            $updateData['icon'] = ImageHelper::compressAndConvertToWebp(
                $request->file('icon'),
                'images/ott',
                250,
                Str::slug($request->name),
                512
            );
            if ($platform->icon) {
                ImageHelper::deleteFile($platform->icon);
            }
        } elseif (is_string($request->icon) && !empty($request->icon)) {
            $safeIcon = $this->sanitizeIconPath($request->icon);
            if ($safeIcon !== null) {
                $updateData['icon'] = $safeIcon;
            }
        }

        $platform->update($updateData);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Application updated successfully.',
                'platform' => $platform
            ]);
        }

        return redirect()->back()->with('success', 'Application updated successfully.');
    }

    /**
     * Toggle active/inactive status of an application.
     */
    public function toggleStatus(int $id)
    {
        $platform = OttPlatform::findOrFail($id);
        $platform->status = !$platform->status;
        $platform->save();

        return response()->json([
            'success' => true,
            'status' => $platform->status,
            'message' => 'Application status updated to ' . ($platform->status ? 'Active' : 'Inactive')
        ]);
    }

    /**
     * Remove the specified OTT / Application master record.
     */
    public function destroy(int $id)
    {
        $platform = OttPlatform::findOrFail($id);
        $platform->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Application deleted from master catalog.'
            ]);
        }

        return redirect()->back()->with('success', 'Application deleted from master catalog.');
    }

    /**
     * Validate preset icon path/URL strings to block traversal and active URI schemes.
     */
    private function sanitizeIconPath(?string $icon): ?string
    {
        if ($icon === null) {
            return null;
        }

        $trimmed = trim($icon);
        if ($trimmed === '' || str_contains($trimmed, "\0") || str_contains($trimmed, '..')) {
            return null;
        }

        if (preg_match('/^(javascript|vbscript|data):/i', $trimmed)) {
            return null;
        }

        return $trimmed;
    }
}
