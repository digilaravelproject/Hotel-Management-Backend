<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use File;

class ProfileController extends Controller
{
    /**
     * Show the update profile form
     */
    public function showProfileForm()
    {
        $hotelAdmin = Auth::guard('hotel_admin')->user();
        return view('hotel_admin.profile', compact('hotelAdmin'));
    }

    /**
     * Update the profile details
     */
    public function updateProfile(Request $request)
    {
        $hotelAdmin = Auth::guard('hotel_admin')->user();

        $request->validate([
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:hotel_admins,email,' . $hotelAdmin->id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $hotelAdmin->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the update hotel info form
     */
    public function showHotelInfoForm()
    {
        $hotelAdmin = Auth::guard('hotel_admin')->user();
        return view('hotel_admin.hotel_info', compact('hotelAdmin'));
    }

    /**
     * Update the hotel details
     */
    public function updateHotelInfo(Request $request)
    {
        $hotelAdmin = Auth::guard('hotel_admin')->user();

        $request->validate([
            'hotel_name' => 'required|string|max:255',
            'hotel_location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'slider_images' => 'nullable|array|max:10',
            'slider_images.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $data = [
            'hotel_name' => $request->hotel_name,
            'hotel_location' => $request->hotel_location,
            'description' => $request->description,
        ];

        // Handle logo replacement
        if ($request->hasFile('hotel_logo')) {
            // Delete old file if exists
            if ($hotelAdmin->hotel_logo && file_exists(public_path($hotelAdmin->hotel_logo))) {
                @unlink(public_path($hotelAdmin->hotel_logo));
            }

            $logo = $request->file('hotel_logo');
            $logoName = time() . '_logo_' . Str::random(8) . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/hotel_logos'), $logoName);
            $data['hotel_logo'] = 'uploads/hotel_logos/' . $logoName;
        }

        // Handle image replacement
        if ($request->hasFile('hotel_image')) {
            // Delete old file if exists
            if ($hotelAdmin->hotel_image && file_exists(public_path($hotelAdmin->hotel_image))) {
                @unlink(public_path($hotelAdmin->hotel_image));
            }

            $image = $request->file('hotel_image');
            $imageName = time() . '_image_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/hotel_images'), $imageName);
            $data['hotel_image'] = 'uploads/hotel_images/' . $imageName;
        }

        // Handle slider uploads
        if ($request->hasFile('slider_images')) {
            $existingSliders = $hotelAdmin->slider_images ?? [];
            
            // Check limit
            if (count($existingSliders) + count($request->file('slider_images')) > 10) {
                return back()->withErrors(['slider_images' => 'Maximum limit of 10 slider images reached.'])->withInput();
            }

            foreach ($request->file('slider_images') as $file) {
                $fileName = time() . '_slider_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/hotel_sliders'), $fileName);
                $existingSliders[] = 'uploads/hotel_sliders/' . $fileName;
            }
            
            $data['slider_images'] = $existingSliders;
        }

        $hotelAdmin->update($data);

        return back()->with('success', 'Hotel information updated successfully!');
    }

    /**
     * Delete an individual slider image
     */
    public function deleteSliderImage(Request $request)
    {
        $hotelAdmin = Auth::guard('hotel_admin')->user();
        
        $request->validate([
            'image_path' => 'required|string',
        ]);

        $imagePath = $request->image_path;
        $sliders = $hotelAdmin->slider_images ?? [];

        if (($key = array_search($imagePath, $sliders)) !== false) {
            // Delete file from disk
            if (file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }

            // Remove from array and reindex
            unset($sliders[$key]);
            $sliders = array_values($sliders);

            $hotelAdmin->update(['slider_images' => $sliders]);

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Slider image removed successfully.');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
        }

        return back()->with('error', 'Slider image not found.');
    }
}
