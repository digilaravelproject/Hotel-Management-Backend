<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OttPlatform;

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
            'icon' => 'nullable|string|max:500',
        ]);

        OttPlatform::create([
            'name' => $request->name,
            'package_name' => $request->package_name,
            'icon' => $request->icon,
            'status' => true,
        ]);

        return redirect()->route('super-admin.ott-master.index')
                         ->with('success', 'Application added to master catalog successfully.');
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
            'icon' => 'nullable|string|max:500',
        ]);

        $platform->update([
            'name' => $request->name,
            'package_name' => $request->package_name,
            'icon' => $request->icon,
        ]);

        return redirect()->route('super-admin.ott-master.index')
                         ->with('success', 'Application updated successfully.');
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

        return redirect()->route('super-admin.ott-master.index')
                         ->with('success', 'Application deleted from master catalog.');
    }
}
