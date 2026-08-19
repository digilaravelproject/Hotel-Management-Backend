<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;
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
            'city' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'reception_contact' => 'nullable|string|max:255',
            'dining_contact' => 'nullable|string|max:255',
            'medical_contact' => 'nullable|string|max:255',
            'emergency_email' => 'nullable|email|max:255',
            'hotel_amenities' => 'nullable|array',
            'hotel_amenities.*' => 'nullable|string|max:255',
            'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'slider_images' => 'nullable|array|max:10',
            'slider_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'hotel_gallery_images' => 'nullable|array|max:20',
            'hotel_gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
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
            'city' => $request->city,
            'description' => $request->description,
            'emergency_contacts' => $emergencyContacts,
            'hotel_amenities' => $amenities,
        ];

        // Handle logo replacement with WebP conversion
        if ($request->hasFile('hotel_logo')) {
            if ($hotelAdmin->hotel_logo) {
                ImageHelper::deleteFile($hotelAdmin->hotel_logo);
            }
            $data['hotel_logo'] = ImageHelper::compressAndConvertToWebp(
                $request->file('hotel_logo'),
                'uploads/hotel_logos',
                500,
                'logo',
                1200
            );
        }

        // Handle cover image replacement with WebP conversion
        if ($request->hasFile('hotel_image')) {
            if ($hotelAdmin->hotel_image) {
                ImageHelper::deleteFile($hotelAdmin->hotel_image);
            }
            $data['hotel_image'] = ImageHelper::compressAndConvertToWebp(
                $request->file('hotel_image'),
                'uploads/hotel_images',
                1000,
                'cover',
                2560
            );
        }

        // Handle slider uploads with WebP conversion
        if ($request->hasFile('slider_images')) {
            $existingSliders = $hotelAdmin->slider_images ?? [];
            
            // Check limit
            if (count($existingSliders) + count($request->file('slider_images')) > 10) {
                return back()->withErrors(['slider_images' => 'Maximum limit of 10 slider images reached.'])->withInput();
            }

            foreach ($request->file('slider_images') as $file) {
                $savedSlider = ImageHelper::compressAndConvertToWebp(
                    $file,
                    'uploads/hotel_sliders',
                    800,
                    'slider',
                    2560
                );
                $existingSliders[] = $savedSlider;
            }
            
            $data['slider_images'] = $existingSliders;
        }

        // Handle hotel gallery images upload with WebP conversion
        if ($request->hasFile('hotel_gallery_images')) {
            $existingGallery = $hotelAdmin->hotel_gallery_images ?? [];
            
            if (count($existingGallery) + count($request->file('hotel_gallery_images')) > 20) {
                return back()->withErrors(['hotel_gallery_images' => 'Maximum limit of 20 hotel gallery images reached.'])->withInput();
            }

            foreach ($request->file('hotel_gallery_images') as $file) {
                $savedGallery = ImageHelper::compressAndConvertToWebp(
                    $file,
                    'uploads/hotel_gallery',
                    800,
                    'gallery',
                    2560
                );
                $existingGallery[] = $savedGallery;
            }
            
            $data['hotel_gallery_images'] = $existingGallery;
        }

        $hotelAdmin->update($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Hotel information updated & synced to TVs in real-time!'
            ]);
        }

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
            // Delete file from disk safely
            ImageHelper::deleteFile($imagePath);

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
            ImageHelper::deleteFile($imagePath);

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
