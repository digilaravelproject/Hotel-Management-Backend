<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            'icon' => 'nullable',
        ]);

        $iconPath = null;

        // Check if an image file was uploaded
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = Str::slug($request->name) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('images/ott');
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $iconPath = 'images/ott/' . $filename;
        } elseif (is_string($request->icon) && !empty($request->icon)) {
            $iconPath = $request->icon;
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
            'icon' => 'nullable',
        ]);

        $updateData = [
            'name' => $request->name,
            'package_name' => $request->package_name,
        ];

        // Handle new icon upload if provided
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = Str::slug($request->name) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('images/ott');
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $updateData['icon'] = 'images/ott/' . $filename;
        } elseif (is_string($request->icon) && !empty($request->icon)) {
            $updateData['icon'] = $request->icon;
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
}
