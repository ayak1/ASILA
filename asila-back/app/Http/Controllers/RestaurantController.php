<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\locale;
use App\Models\Restaurant;
use App\Models\Image;
use App\Models\RestaurantTranslation;

use App\Http\Requests\AddRestaurantRequest;


class RestaurantController extends Controller
{
    public function index(){
        $lang = request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $restaurants = Restaurant::with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images',
        ])->get();
        $formattedRestaurants = $restaurants->map(function ($restaurant) {
            $translation = $restaurant->translations->first(); // Assuming you want the first translation
            $formattedImages = [];
            foreach ($restaurant->images as $image) {
                if ($image->is_cover != true) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
            }
            $cover = $restaurant->images->firstWhere('is_cover', true);
            $coverImage = null ;
            if($cover!=null){
                $coverImage = asset('storage/' . $cover->path);
            }
            return [
                'id' => $restaurant->id,
                'rating' => $restaurant->rating,
                'opening_hours' => $restaurant->opening_hours,
                'closing_hours' => $restaurant->closing_hours,
                'name' => optional($translation)->name,
                'address' => optional($translation)->address,
                'short_description' => optional($translation)->short_description,
                'full_description' => optional($translation)->full_description,
                'cover_image' => $coverImage,
                'images' => $formattedImages,
            ];
        });
        return response()->json(['restaurants' => $formattedRestaurants]);

    }
    public function getByCity($cityId){
        $lang = request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $restaurants = Restaurant::where('city_id',$cityId)->with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images',
        ])->get();
        $formattedRestaurants = $restaurants->map(function ($restaurant) {
            $translation = $restaurant->translations->first(); // Assuming you want the first translation
            $formattedImages = [];
            $coverImage = null ;
            $images= $restaurant->images()->get();
            if($images){
                foreach ($images as $image) {
                if ($image->is_cover != true && $image->path!=null) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
                }
                $cover = $restaurant->images()->firstWhere('is_cover', true);
                if($cover!=null){
                    $coverImage = asset('storage/' . $cover->path);
                }
            }
            return [
                'id' => $restaurant->id,
                'rating' => $restaurant->rating,
                'opening_hours' => $restaurant->opening_hours,
                'closing_hours' => $restaurant->closing_hours,
                'name' => optional($translation)->name,
                'address' => optional($translation)->address,
                'short_description' => optional($translation)->short_description,
                'full_description' => optional($translation)->full_description,
                'cover_image' => $coverImage,
                'images' => $formattedImages,
            ];
        });
        return response()->json(['restaurants' => $formattedRestaurants]);

    }
    public function getRestaurant($id){
        $lang = request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $restaurant = Restaurant::where('id',$id)->with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images',
        ])->first();
        // $formattedRestaurants = $restaurants->map(function ($restaurant) {
            $formattedRestaurant=[];
            $translation = $restaurant->translations->first(); // Assuming you want the first translation
            $formattedImages = [];
            $coverImage = null ;
            $images= $restaurant->images()->get();
            if($images){
                foreach ($images as $image) {
                if ($image->is_cover != true && $image->path != null) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
                }
                $cover = $restaurant->images()->firstWhere('is_cover', true);
                if($cover!=null){
                    $coverImage = asset('storage/' . $cover->path);
                }
            }
            $formattedRestaurant= [
                'id' => $restaurant->id,
                'rating' => $restaurant->rating,
                'opening_hours' => $restaurant->opening_hours,
                'closing_hours' => $restaurant->closing_hours,
                'name' => optional($translation)->name,
                'address' => optional($translation)->address,
                'short_description' => optional($translation)->short_description,
                'full_description' => optional($translation)->full_description,
                'cover_image' => $coverImage,
                'images' => $formattedImages,
            ];
        // });
        return response()->json(['restaurant' => $formattedRestaurant]);

    }
    public function addRestaurant(AddRestaurantRequest $request){
        $data = $request->all();
        $validationError = validateTranslations($data['translations']);
        if ($validationError !== null) {
            return $validationError;
        }
        try{
            \DB::beginTransaction();
            $restaurant = Restaurant::create($data);
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($restaurant, RestaurantTranslation::class, $translationData,'restaurant_id');
            }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                if($coverImage) addImageToModel($restaurant, $coverImage, true,"restaurant_images");
            }
            // Handle hotel images upload and storage
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if($image)addImageToModel($restaurant, $image, false,"restaurant_images");
                }
            }
            \DB::commit();
            return response()->json(['message' => 'restaurant added successfully']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['error' => 'Failed to add restaurant.' . $e->getMessage()], 500);
        }
    }

    public function deleteRestaurant($id){
        try {
            $restaurant = Restaurant::findOrFail($id);
            $restaurant->translations()->delete();
            $restaurant->delete();
            return response()->json(['message' => 'restaurant deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed find restaurant to delete.'], 500);
        }
    }
}


