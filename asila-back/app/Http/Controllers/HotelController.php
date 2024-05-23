<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\locale;
use App\Models\Hotel;
use App\Models\Image;
use App\Models\HotelTranslation;

use App\Http\Requests\AddHotelRequest;

class HotelController extends Controller
{
    public function index(){
        $lang = request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $hotels = Hotel::with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images',
        ])->get();
        $formattedHotels = $hotels->map(function ($hotel) {
            $translation = $hotel->translations->first(); // Assuming you want the first translation
            $formattedImages = [];
            foreach ($hotel->images as $image) {
                if ($image->is_cover != true) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
            }
            return [
                'id' => $hotel->id,
                'has_parking' => $hotel->has_parking,
                'has_free_breakfast' => $hotel->has_free_breakfast,
                'has_swimming_pool' => $hotel->has_swimming_pool,
                'has_spa' => $hotel->has_spa,
                'has_fitness_center' => $hotel->has_fitness_center,
                'has_free_internet' => $hotel->has_free_internet,
                'has_restaurant' => $hotel->has_restaurant,
                'pets_allowed' => $hotel->has_restaurant,
                'name' => $translation ? $translation->name : null,
                'address' => $translation ? $translation->address : null,
                'short_description' => $translation ? $translation->short_description : null,
                'full_description' => $translation ? $translation->full_description : null,
                'cover_image' => asset('storage/' . $hotel->images->firstWhere('is_cover', true)->path),
                'images' => $formattedImages,
            ];
        });
        return response()->json(['hotels' => $formattedHotels]);

    }
    public function getByCity($cityId){
        $lang = request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $perPage = 10;

        $hotels = Hotel::where('city_id',$cityId)->with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images',
        ])->orderBy('id', 'desc')
        ->paginate($perPage);
        $formattedHotels = $hotels->map(function ($hotel) {
            $translation = $hotel->translations->first(); // Assuming you want the first translation
            $formattedImages = [];
            foreach ($hotel->images as $image) {
                if ($image->is_cover != true && $image->path!=null) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
            }
            $cover = $hotel->images->firstWhere('is_cover', true);
            $coverImage = null ;
            if($cover!=null){
                $coverImage = asset('storage/' . $cover->path);
            }
            return [
                'id' => $hotel->id,
                'has_parking' => $hotel->has_parking,
                'has_free_breakfast' => $hotel->has_free_breakfast,
                'has_swimming_pool' => $hotel->has_swimming_pool,
                'has_spa' => $hotel->has_spa,
                'has_fitness_center' => $hotel->has_fitness_center,
                'has_free_internet' => $hotel->has_free_internet,
                'has_restaurant' => $hotel->has_restaurant,
                'pets_allowed' => $hotel->has_restaurant,
                'name' => $translation ? $translation->name : null,
                'address' => $translation ? $translation->address : null,
                'short_description' => $translation ? $translation->short_description : null,
                'full_description' => $translation ? $translation->full_description : null,
                'cover_image' => $coverImage,
                'images' => $formattedImages,
            ];
        });
        return response()->json(['hotels' => $formattedHotels]);

    }
    public function getHotel($id){
        $lang = request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $hotel = Hotel::where('id',$id)->with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images',
        ])->first();
        // $formattedHotels = $hotels->map(function ($hotel) {
            $translation = $hotel->translations->first(); // Assuming you want the first translation
            $formattedImages = [];
            foreach ($hotel->images as $image) {
                if ($image->is_cover != true && $image->path!=null) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
            }
            $cover = $hotel->images->firstWhere('is_cover', true);
            $coverImage = null ;
            if($cover!=null){
                $coverImage = asset('storage/' . $cover->path);
            }
            $formattedHotel= [
                'id' => $hotel->id,
                'has_parking' => $hotel->has_parking,
                'has_free_breakfast' => $hotel->has_free_breakfast,
                'has_swimming_pool' => $hotel->has_swimming_pool,
                'has_spa' => $hotel->has_spa,
                'has_fitness_center' => $hotel->has_fitness_center,
                'has_free_internet' => $hotel->has_free_internet,
                'has_restaurant' => $hotel->has_restaurant,
                'pets_allowed' => $hotel->has_restaurant,
                'name' => $translation ? $translation->name : null,
                'address' => $translation ? $translation->address : null,
                'short_description' => $translation ? $translation->short_description : null,
                'full_description' => $translation ? $translation->full_description : null,
                'cover_image' => $coverImage,
                'images' => $formattedImages,
            ];
        return response()->json(['hotel' => $formattedHotel]);

    }
    public function addHotel(AddHotelRequest $request){
        $data = $request->all();
        $validationError = validateTranslations($data['translations']);
        if ($validationError !== null) {
            return $validationError;
        }
        try{
            \DB::beginTransaction();
            $hotel = Hotel::create($data);
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($hotel, HotelTranslation::class, $translationData,'hotel_id');
            }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                if($coverImage) addImageToModel($hotel, $coverImage, true,"hotel_images");
            }
            // Handle hotel images upload and storage
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if($image)addImageToModel($hotel, $image, false,"hotel_images");
                }
            }
            \DB::commit();
            return response()->json(['message' => 'hotel added successfully']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['error' => 'Failed to add hotel.' . $e->getMessage()], 500);
        }
    }
    public function deleteHotel($id){
        try {
            $hotel = Hotel::findOrFail($id);
            $hotel->translations()->delete();
            $hotel->delete();
            return response()->json(['message' => 'Hotel deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed find hotel to delete.'], 500);
        }
    }
}
