<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;

class HotelFacilityController extends Controller
{
    /**
     * Display a listing of hotel facilities / information media.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('hotel.login');
        }

        $facilities = $this->getNormalizedGallery($hotel);

        return view('hotel_admin.facilities.index', compact('facilities'));
    }

    /**
     * Ensure all gallery items have persistent, stable IDs and consistent structure.
     */
    protected function getNormalizedGallery($hotel): array
    {
        $rawGallery = $hotel->hotel_gallery_images ?? [];
        $gallery = [];
        $modified = false;

        foreach ($rawGallery as $k => $item) {
            if (is_array($item)) {
                $hasId = !empty($item['id']);
                $stableId = $hasId ? (string)$item['id'] : ('gal_' . ($k + 1) . '_' . substr(md5(json_encode($item) . $k), 0, 6));
                if (!$hasId) {
                    $modified = true;
                }

                $gallery[$k] = [
                    'id' => $stableId,
                    'title' => $item['title'] ?? ('Hotel Facility #' . ($k + 1)),
                    'description' => $item['description'] ?? '',
                    'features' => is_array($item['features'] ?? null) ? array_values($item['features']) : [],
                    'image' => $item['image'] ?? '',
                    'created_at' => $item['created_at'] ?? now()->toIso8601String(),
                ];
            } else {
                $modified = true;
                $gallery[$k] = [
                    'id' => 'gal_' . ($k + 1) . '_' . substr(md5((string)$item . $k), 0, 6),
                    'title' => 'Hotel Facility #' . ($k + 1),
                    'description' => '',
                    'features' => [],
                    'image' => (string) $item,
                    'created_at' => now()->toIso8601String(),
                ];
            }
        }

        if ($modified) {
            $hotel->update(['hotel_gallery_images' => array_values($gallery)]);
        }

        return $gallery;
    }

    /**
     * Store a newly created facility item.
     */
    public function store(Request $request)
    {
        $hotel = Auth::guard('hotel_admin')->user();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:250',
            'features' => 'nullable|array|max:4',
            'features.*' => 'nullable|string|max:100',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp,svg|max:5120',
        ], [
            'description.max' => 'Description payload cannot exceed 250 characters.',
            'features.max' => 'You can specify a maximum of 4 features.',
            'image.required' => 'Please upload a 16:9 image for this facility.',
            'image.max' => 'The image file size must not exceed 5MB.',
        ]);

        $imagePath = ImageHelper::compressAndConvertToWebp(
            $request->file('image'),
            'uploads/hotel_gallery',
            800,
            'facility',
            1920
        );

        $features = [];
        if ($request->has('features') && is_array($request->features)) {
            $features = array_values(array_filter($request->features, function($f) {
                return !empty(trim($f));
            }));
            $features = array_slice($features, 0, 4);
        }

        $gallery = $this->getNormalizedGallery($hotel);
        if (count($gallery) >= 20) {
            return back()->with('error', 'Maximum limit of 20 hotel facilities reached.');
        }

        $newItem = [
            'id' => 'gal_' . uniqid(),
            'title' => $request->title,
            'description' => $request->description,
            'features' => $features,
            'image' => $imagePath,
            'created_at' => now()->toIso8601String(),
        ];

        $gallery[] = $newItem;
        $hotel->update(['hotel_gallery_images' => array_values($gallery)]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Hotel facility added & synced to TVs in real-time!'
            ]);
        }

        return redirect()->route('hotel.facilities.index')
                         ->with('success', 'Hotel facility added successfully!');
    }

    /**
     * Update the specified facility item.
     */
    public function update(Request $request, $id)
    {
        $hotel = Auth::guard('hotel_admin')->user();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:250',
            'features' => 'nullable|array|max:4',
            'features.*' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:5120',
        ], [
            'description.max' => 'Description payload cannot exceed 250 characters.',
            'features.max' => 'You can specify a maximum of 4 features.',
            'image.max' => 'The image file size must not exceed 5MB.',
        ]);

        $gallery = $this->getNormalizedGallery($hotel);
        $found = false;

        $features = [];
        if ($request->has('features') && is_array($request->features)) {
            $features = array_values(array_filter($request->features, function($f) {
                return !empty(trim($f));
            }));
            $features = array_slice($features, 0, 4);
        }

        foreach ($gallery as $key => $item) {
            if ($this->matchGalleryItem($item, $key, $id)) {
                $oldImagePath = is_array($item) ? ($item['image'] ?? '') : $item;
                $newImagePath = $oldImagePath;

                if ($request->hasFile('image')) {
                    if ($oldImagePath) {
                        ImageHelper::deleteFile($oldImagePath);
                    }
                    $newImagePath = ImageHelper::compressAndConvertToWebp(
                        $request->file('image'),
                        'uploads/hotel_gallery',
                        800,
                        'facility',
                        1920
                    );
                }

                $gallery[$key] = [
                    'id' => is_array($item) && !empty($item['id']) ? $item['id'] : ('gal_' . ($key + 1)),
                    'title' => $request->title,
                    'description' => $request->description,
                    'features' => $features,
                    'image' => $newImagePath,
                    'created_at' => is_array($item) && isset($item['created_at']) ? $item['created_at'] : now()->toIso8601String(),
                ];
                $found = true;
                break;
            }
        }

        if ($found) {
            $hotel->update(['hotel_gallery_images' => array_values($gallery)]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Hotel facility updated & synced to TVs in real-time!'
                ]);
            }

            return redirect()->route('hotel.facilities.index')
                             ->with('success', 'Hotel facility updated successfully!');
        }

        return back()->with('error', 'Hotel facility not found.');
    }

    /**
     * Remove the specified facility item from storage.
     */
    public function destroy($id)
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $gallery = $this->getNormalizedGallery($hotel);
        $found = false;

        foreach ($gallery as $key => $item) {
            if ($this->matchGalleryItem($item, $key, $id)) {
                $imagePath = is_array($item) ? ($item['image'] ?? '') : $item;
                if ($imagePath) {
                    ImageHelper::deleteFile($imagePath);
                }
                unset($gallery[$key]);
                $found = true;
                break;
            }
        }

        if ($found) {
            $hotel->update(['hotel_gallery_images' => array_values($gallery)]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Hotel facility deleted & synced to TVs in real-time!'
                ]);
            }

            return redirect()->route('hotel.facilities.index')
                             ->with('success', 'Hotel facility deleted successfully.');
        }

        return back()->with('error', 'Hotel facility not found.');
    }

    /**
     * Helper to match a gallery item by any ID or index representation
     */
    private function matchGalleryItem($item, $key, $targetId): bool
    {
        $targetId = trim((string) $targetId);
        $cleanTargetId = str_starts_with($targetId, 'gal_') ? substr($targetId, 4) : $targetId;
        $numTargetId = ltrim($targetId, '#');

        // 1. Match by 0-based index: 0, 1, 2
        if ((string)$key === $targetId || (string)$key === $cleanTargetId || (string)$key === $numTargetId || ('gal_' . $key) === $targetId) {
            return true;
        }

        // 2. Match by 1-based index (e.g., #2, 2, gal_2)
        $oneBased = (string)($key + 1);
        if ($oneBased === $targetId || $oneBased === $cleanTargetId || $oneBased === $numTargetId || ('gal_' . $oneBased) === $targetId || ('#' . $oneBased) === $targetId) {
            return true;
        }

        $itemId = is_array($item) ? (string)($item['id'] ?? '') : '';
        $cleanItemId = str_starts_with($itemId, 'gal_') ? substr($itemId, 4) : $itemId;

        // 3. Match by item ID
        if (!empty($itemId)) {
            if ($itemId === $targetId || $itemId === $cleanTargetId || $itemId === $numTargetId || ('gal_' . $itemId) === $targetId) {
                return true;
            }
            if ($cleanItemId === $cleanTargetId || $cleanItemId === $targetId || $cleanItemId === $numTargetId) {
                return true;
            }
        }

        // 4. Match by title (e.g. "Hotel Facility #2")
        $itemTitle = is_array($item) ? (string)($item['title'] ?? '') : '';
        if (!empty($itemTitle)) {
            if (strcasecmp($itemTitle, $targetId) === 0) {
                return true;
            }
            if (strcasecmp($itemTitle, 'Hotel Facility #' . $oneBased) === 0 && ($targetId === $oneBased || $targetId === '#' . $oneBased || $targetId === 'gal_' . $oneBased)) {
                return true;
            }
        }

        // 5. Match by image path or md5 hash
        $imagePath = is_array($item) ? ($item['image'] ?? '') : (string) $item;
        if (!empty($imagePath)) {
            $hash = md5($imagePath);
            if ($hash === $targetId || $hash === $cleanTargetId || ('gal_' . $hash) === $targetId) {
                return true;
            }
            if ($imagePath === $targetId || $imagePath === $cleanTargetId) {
                return true;
            }
        }

        return false;
    }
}
