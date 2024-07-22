<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\locale;
use App\Models\City;
use App\Models\Image;
use App\Models\CityTranslation;
use App\Models\TagTranslation;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AddCityRequest;
use App\Http\Requests\EditCityRequest;


class CityController extends Controller
{

    public function index($lang = null)
    {
        $lang = $lang ?? request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $cities = City::with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images',
        ])->get();

        $formattedCities = $cities->map(function ($city) {
            $translation = $city->translations->first(); // Assuming you want the first translation
            // $formattedTags = $city->tags->map(function ($tag) use ($city, $translation) {
            //     $tagTranslation = $tag->translations->where('locale_id', $translation->locale_id)->first();

            //     return [
            //         'id' => $tag->id,
            //         'name' => $tagTranslation ? $tagTranslation->name : null,
            //         'other_tag_data' => $tag->other_tag_data,
            //         // Add any other tag-related data you need
            //     ];
            // });
            $formattedImages = [];
            foreach ($city->images as $image) {
                if ($image->is_cover != true) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
            }
            return [
                'id' => $city->id,
                'name' => $translation ? $translation->name : null,
                'description' => $translation ? $translation->description : null,
                'cover_image' => asset('storage/' . $city->images->firstWhere('is_cover', true)->path),
                'images' => $formattedImages,
            ];
        });

        return response()->json([
            'cities' => $formattedCities
        ]);
    }

    public function getCity($Id,$lang  = null){
        $lang = $lang ?? request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

       $city = City::where('id', $Id)
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },'images'])
        ->first();
        // $formattedImages = $city->images->map(function ($image) {
        //     return [
        //         'id' => $image->id,
        //         'path' => asset('storage/' . $image->path),
        //         // 'path' => asset('storage/app/public/' . $image->path),
        //         'is_cover' => $image->is_cover,
        //         // Add any other image-related data you need
        //     ];
        // });
        $formattedImages = [];
        $city->images->map(function ($image) {
            if($image){
                if ($image->is_cover != true) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
            }
        });
        $translation = $city->translations->first();
        $formattedCity=[
            'id' => $city->id,
            'name' => $translation ? $translation->name : null,
            'description' => $translation ? $translation->description : null,
            'cover_image' => asset('storage/' . $city->images->firstWhere('is_cover', true)->path),
            'images'=>$formattedImages
        ];
        return response()->json(['city' => $formattedCity]);
    }

    public function addCity(AddCityRequest $request){
        $data = $request->all();

        $validationError = validateTranslations($data['translations']);
        if ($validationError !== null) {
            return $validationError;
        }
        try {
            \DB::beginTransaction();
            $city = City::create($data);
            // Create translations for the city
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($city, CityTranslation::class, $translationData,'city_id');
            }
            // Add or link tags to the city for all translations
            // if ($request->has('tags')) {
            //     foreach ($data['tags'] as $tagData) {
            //         addOrUpdateTagToModel($city,$tagData);
            //     }
            // }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                addImageToModel($city, $coverImage, true,"city_images");
            }
            // Handle city images upload and storage
            if ($request->hasFile('images') && $request->hasFile('images')!=null) {
                foreach ($request->file('images') as $image) {
                    addImageToModel($city, $image, false,"city_images");
                }
            }


            \DB::commit();

            return response()->json(['message' => 'City added successfully']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['error' => 'Failed to add city.' . $e->getMessage()], 500);
        }
    }

    public function editCity(EditCityRequest $request, $id){
        $data = $request->all();
        try {
            \DB::beginTransaction();
            $city = City::findOrFail($id);
            $validationError = validateTranslations($data['translations']);
            if ($validationError !== null) {
                return $validationError;
            }
            $city->update($data);
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($city, CityTranslation::class, $translationData,'city_id');
            }
            // if ($request->has('tags')) {
            //     foreach ($data['tags'] as $tagData) {
            //         addOrUpdateTagToModel($city,$tagData);
            //     }
            // }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                updateImageInModel($city, $coverImage, true,'city_images');
            }
            // Handle city images upload and storage
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                updateImageInModel($city, $images , false,"city_images");
            }
            \DB::commit();
            return response()->json(['message' => 'City updated successfully']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['error' => 'Failed to update city.' . $e->getMessage()], 500);
        }
    }

    public function deleteCity($id){
        try {
            $city = City::findOrFail($id);
            $city->translations()->delete();
            $city->delete();
            return response()->json(['message' => 'city deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed find city to delete.'], 500);
        }
    }
}
