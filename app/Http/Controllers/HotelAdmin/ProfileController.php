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
            'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $data = [
            'hotel_name' => $request->hotel_name,
            'hotel_location' => $request->hotel_location,
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

        $hotelAdmin->update($data);

        return back()->with('success', 'Hotel information updated successfully!');
    }
}
