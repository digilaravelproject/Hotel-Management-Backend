<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OurCity;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OurCityController extends Controller
{
    /**
     * Display a listing of city attractions/places for logged-in hotel admin.
     */
    public function index()
    {
        try {
            $hotel = Auth::guard('hotel_admin')->user();
            $cityPlaces = OurCity::where('hotel_admin_id', $hotel->id)
                                 ->orderBy('sr_no', 'asc')
                                 ->orderBy('created_at', 'desc')
                                 ->get();

            return view('hotel_admin.our_city.index', compact('cityPlaces'));
        } catch (\Throwable $e) {
            Log::error('OurCityController@index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Unable to load city attractions: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created city place/attraction with 16:9 WebP image compression.
     */
    public function store(Request $request)
    {
        try {
            $hotel = Auth::guard('hotel_admin')->user();
            $this->validateRequest($request);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = ImageHelper::compressAndConvertToWebp(
                    $request->file('image'),
                    'uploads/our_city',
                    800,
                    'city_place',
                    1920
                );
            }

            $attractions = $this->extractAttractions($request);

            $saveData = [
                'hotel_admin_id' => $hotel->id,
                'sr_no' => $request->sr_no,
                'title' => $request->title,
                'image' => $imagePath,
                'description' => $request->description,
                'attractions' => $attractions,
                'status' => true,
            ];

            OurCity::create($saveData);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'City attraction added & synced to TVs in real-time!'
                ]);
            }

            return redirect()->route('hotel.our-city.index')
                             ->with('success', 'City attraction added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Throwable $e) {
            Log::error('OurCityController@store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'payload' => $request->except(['image']),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to add city attraction: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to add city attraction: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified city attraction item.
     */
    public function update(Request $request, $id)
    {
        try {
            $hotel = Auth::guard('hotel_admin')->user();
            $cityPlace = OurCity::where('hotel_admin_id', $hotel->id)->findOrFail($id);
            $this->validateRequest($request);

            $imagePath = $cityPlace->image;
            if ($request->hasFile('image')) {
                if ($cityPlace->image) {
                    ImageHelper::deleteFile($cityPlace->image);
                }
                $imagePath = ImageHelper::compressAndConvertToWebp(
                    $request->file('image'),
                    'uploads/our_city',
                    800,
                    'city_place',
                    1920
                );
            }

            $attractions = $this->extractAttractions($request);

            $updateData = [
                'sr_no' => $request->sr_no,
                'title' => $request->title,
                'image' => $imagePath,
                'description' => $request->description,
                'attractions' => $attractions,
            ];

            $cityPlace->update($updateData);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'City attraction updated & synced to TVs in real-time!'
                ]);
            }

            return redirect()->route('hotel.our-city.index')
                             ->with('success', 'City attraction updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Throwable $e) {
            Log::error('OurCityController@update Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'id' => $id,
                'payload' => $request->except(['image']),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update city attraction: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to update city attraction: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified city attraction item.
     */
    public function destroy($id)
    {
        try {
            $hotel = Auth::guard('hotel_admin')->user();
            $cityPlace = OurCity::where('hotel_admin_id', $hotel->id)->findOrFail($id);

            if ($cityPlace->image) {
                ImageHelper::deleteFile($cityPlace->image);
            }

            $cityPlace->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'City attraction deleted & synced to TVs in real-time!'
                ]);
            }

            return redirect()->route('hotel.our-city.index')
                             ->with('success', 'City attraction deleted successfully!');
        } catch (\Throwable $e) {
            Log::error('OurCityController@destroy Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'id' => $id,
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete city attraction: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to delete city attraction: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status (active/inactive).
     */
    public function toggleStatus($id)
    {
        try {
            $hotel = Auth::guard('hotel_admin')->user();
            $cityPlace = OurCity::where('hotel_admin_id', $hotel->id)->findOrFail($id);

            $cityPlace->status = !$cityPlace->status;
            $cityPlace->save();

            return response()->json([
                'success' => true,
                'status' => $cityPlace->status,
                'message' => 'City attraction status updated to ' . ($cityPlace->status ? 'Active' : 'Inactive')
            ]);
        } catch (\Throwable $e) {
            Log::error('OurCityController@toggleStatus Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to cleanly extract and format attractions/highlights array (max 4).
     */
    private function extractAttractions(Request $request): array
    {
        $rawAttractions = $request->input('attractions') 
            ?? $request->input('features') 
            ?? $request->input('specifications') 
            ?? [];

        if (is_string($rawAttractions)) {
            $decoded = json_decode($rawAttractions, true);
            $rawAttractions = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$rawAttractions];
        }

        if (!is_array($rawAttractions)) {
            return [];
        }

        $attractions = array_values(array_filter($rawAttractions, function ($val) {
            return !empty(trim((string) $val));
        }));

        return array_slice($attractions, 0, 4);
    }

    /**
     * Validate incoming request parameters for Our City attractions.
     */
    private function validateRequest(Request $request): void
    {
        $request->validate([
            'sr_no' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'attractions' => 'nullable|array|max:4',
            'attractions.*' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:5120',
        ], [
            'description.max' => 'Description payload cannot exceed 500 characters.',
            'attractions.max' => 'You can specify a maximum of 4 highlights or tags.',
            'image.max' => 'The image file size must not exceed 5MB.',
            'image.mimes' => 'Only JPG, JPEG, PNG, WEBP, and SVG image formats are allowed.',
        ]);
    }
}
