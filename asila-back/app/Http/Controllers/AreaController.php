<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\locale;
use App\Models\Area;
use App\Models\AreaTranslation;
use App\Http\Requests\AddAreaRequest;
use App\Http\Requests\EditAreaRequest;


class AreaController extends Controller
{
    public function index($lang = null){
        $lang = $lang ?? request()->header('Accept-Language')  ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $areas = Area::with(['translations' => function ($query) use ($locale){
            $query->where('locale_id',$locale->id);
        }])->get();
        return response()->json(['areas'=> $areas]);
    }
    public function getAreaPopular($cityId,$lang = null){
        $lang = $lang ?? request()->header('Accept-Language')  ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $areas = Area::where('city_id',$cityId)->where('is_popular',true)->with(['translations' => function ($query) use ($locale){
            $query->where('locale_id',$locale->id);
        },'images'])->get();

        $formattedPopularAreas = $areas->map(function ($popularArea) {
            $translation = $popularArea->translations->first(); // Assuming you want the first translation
            // $formattedTags = $popularArea->tags->map(function ($tag) use ($popularArea, $translation) {
            //     $tagTranslation = $tag->translations->where('locale_id', $translation->locale_id)->first();

            //     return [
            //         'id' => $tag->id,
            //         'name' => $tagTranslation ? $tagTranslation->name : null,
            //         'other_tag_data' => $tag->other_tag_data,
            //         // Add any other tag-related data you need
            //     ];
            // });
            $formattedImages = $popularArea->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'path' => asset('storage/' . $image->path),
                    // 'path' => asset('storage/app/public/' . $image->path),
                    'is_cover' => $image->is_cover,
                    // Add any other image-related data you need
                ];
            });
            return [
                'id' => $popularArea->id,
                'name' => $translation ? $translation->name : null,
                'description' => $translation ? $translation->description : null,
                'cover_image' => asset('storage/' . $popularArea->images->firstWhere('is_cover', true)->path),
                // 'tags' => $formattedTags,
                'images' => $formattedImages,
            ];
        });

        return response()->json(['popularAreas'=> $formattedPopularAreas]);
    }
    public function getArea($Id,$lang  = null){
        $lang = $lang ?? request()->header('Accept-Language')  ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

       $areas = Area::where('id', $Id)
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            }])
            ->get();

        return response()->json(['areas' => $areas]);
    }

    public function getByCity($cityId, Request $request,$lang  = null){
        $lang = $lang ?? request('lang') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        // Retrieve all apartments with translations for the specified locale
        $areas = Area::where('city_id', $cityId)
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            }])
            ->get();
        return response()->json(['areas' => $areas]);
    }

    public function addArea(AddAreaRequest $request){
        $data = $request->all();
        $validationError = validateTranslations($data['translations']);
        if ($validationError !== null) {
            return $validationError;
        }
        try{
            \DB::beginTransaction();
            $area = area::create($data);
            foreach($data['translations'] as $translationData){
                addTranslationToModel($area, AreaTranslation::class, $translationData,'area_id');
            }
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($area,$tagData);
                }
            }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                addImageToModel($area, $coverImage, true,'area_images');
            }
            // Handle area images upload and storage
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    addImageToModel($area, $image, false,"area_images");
                }
            }
            \DB::commit();
            return response()->json(['message' => 'area added successfully']);
        }
        catch(\Exception $e){
            \DB::rollback();
            return response()->json(['error'=>'Failed to add area.' . $e->getMessage() ], 500);
        }
    }

    public function editArea(EditAreaRequest $request, $id){
        $data = $request->all();
        try {
            \DB::beginTransaction();

            // Find the city by ID
            $area = Area::findOrFail($id);
            $validationError = validateTranslations($data['translations']);

            if ($validationError !== null) {
                return $validationError;
            }

            // Update the main area record
            $area->update($data);

            // Update or create translations for the area
            foreach($data['translations'] as $translationData){
                addTranslationToModel($area, AreaTranslation::class, $translationData,'area_id');
            }
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($area,$tagData);
                }
            }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                updateImageInModel($area, $coverImage, true,'area_images');
            }
            // Handle area images upload and storage
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                updateImageInModel($area, $images , false,"area_images");
            }
            \DB::commit();

            return response()->json(['message' => 'Area updated successfully']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['error' => 'Failed to update area.' . $e->getMessage()], 500);
        }
    }

    public function deleteArea($id){
        try {
            $area = Area::findOrFail($id);
            // Delete translations
            $area->translations()->delete();
            // Delete the area
            $area->delete();
            return response()->json(['message' => 'Area deleted successfully']);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Failed find area to delete.'], 500);
        }
    }
}
