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
            'reception_contact' => 'nullable|string|max:255',
            'dining_contact' => 'nullable|string|max:255',
            'medical_contact' => 'nullable|string|max:255',
            'emergency_email' => 'nullable|email|max:255',
            'hotel_amenities' => 'nullable|array',
            'hotel_amenities.*' => 'nullable|string|max:255',
            'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'slider_images' => 'nullable|array|max:10',
            'slider_images.*' => 'image|mimes:jpeg,png,jpg|max:4096',
            'hotel_gallery_images' => 'nullable|array|max:20',
            'hotel_gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $emergencyContacts = [
            'reception' => $request->input('reception_contact') ?? ('Ext. 0 / ' . $hotelAdmin->phone),
            'dining' => $request->input('dining_contact') ?? 'Ext. 102 (24x7 Available)',
            'medical_sos' => $request->input('medical_contact') ?? 'Ext. 999 (Emergency Desk)',
            'email' => $request->input('emergency_email') ?? $hotelAdmin->email,
        ];

        // Filter empty amenity items if any
        $amenities = array_values(array_filter($request->input('hotel_amenities', []), function($item) {
            return !empty(trim($item));
        }));

        $data = [
            'hotel_name' => $request->hotel_name,
            'hotel_location' => $request->hotel_location,
            'description' => $request->description,
            'emergency_contacts' => $emergencyContacts,
            'hotel_amenities' => $amenities,
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

        // Handle hotel gallery images upload
        if ($request->hasFile('hotel_gallery_images')) {
            $existingGallery = $hotelAdmin->hotel_gallery_images ?? [];
            
            if (count($existingGallery) + count($request->file('hotel_gallery_images')) > 20) {
                return back()->withErrors(['hotel_gallery_images' => 'Maximum limit of 20 hotel gallery images reached.'])->withInput();
            }

            foreach ($request->file('hotel_gallery_images') as $file) {
                $fileName = time() . '_gallery_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/hotel_gallery'), $fileName);
                $existingGallery[] = 'uploads/hotel_gallery/' . $fileName;
            }
            
            $data['hotel_gallery_images'] = $existingGallery;
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

    /**
     * Delete an individual gallery image
     */
    public function deleteGalleryImage(Request $request)
    {
        $hotelAdmin = Auth::guard('hotel_admin')->user();
        
        $request->validate([
            'image_path' => 'required|string',
        ]);

        $imagePath = $request->image_path;
        $gallery = $hotelAdmin->hotel_gallery_images ?? [];

        if (($key = array_search($imagePath, $gallery)) !== false) {
            if (file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }

            unset($gallery[$key]);
            $gallery = array_values($gallery);

            $hotelAdmin->update(['hotel_gallery_images' => $gallery]);

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Hotel gallery image removed successfully.');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
        }

        return back()->with('error', 'Gallery image not found.');
    }
}
