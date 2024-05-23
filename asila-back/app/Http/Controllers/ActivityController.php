<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\Activity;
use App\Models\ActivityTranslation;
use App\Models\Locale;
use App\Models\City;
use App\Models\Area;
use App\Models\Image;
use App\Models\Tag;
use App\Models\TagTranslation;

use App\Http\Requests\AddActivityRequest;
use App\Http\Requests\EditActivityRequest;

class ActivityController extends Controller
{
    public function index($lang = null){
        $lang = $lang ?? request('lang') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $activities = Activity::with(['translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        },
        'images',
        'tags'])
        ->get();
        return response()->json(['activities' => $activities]);
    }

    public function getActivity($id , $lang = null){
        $lang = $lang ?? request('lang') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $activity = Activity::where('id',$id)->with(['translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        },
        'images',
        'tags'])
        ->get();
        return response()->json(['activity' => $activity]);
    }

    public function getByArea($areaId, Request $request, $lang = null){
        $lang = $lang ?? request('lang') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $area = Area::where('id',$areaId)->first();
        if(!$area){
            return response()->json(['error' => 'area not found']);
        }

        // Retrieve all programs with translations and image URLs for the specified locale and area
        $activities = Activity::whereHas('areas', function ($query) use ($areaId) {
            $query->where('areas.id', $areaId);
        })->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },'images','tags'])
            ->get();

        // // Transform the activity to include image URLs
        // $transformedActivity = $activity->map(function ($activity) {
        //     return [
        //         'id' => $activity->id,
        //         'city_id' => $activity->city_id,
        //         'cover_image' => $activity->cover_image ? asset("storage/{$activity->cover_image}") : null,
        //         'private_car_activity' => $activity->private_car_activity,
        //         'group_activity' => $activity->group_activity,
        //         'translations' => $activity->translations,
        //         'tags' => $activity->tags,
        //         // Add more fields as needed
        //     ];
        // });

        return response()->json(['activities' => $activities]);
    }

    public function getByCity($cityId, Request $request, $lang = null){
        $lang = $lang ?? request('lang') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $city = City::where('id',$cityId)->first();
        if(!$city){
            return response()->json(['error' => 'city not found']);
        }
        // Retrieve all activities with translations and image URLs for the specified locale and city
        $activities = Activity::where('city_id', $city->id)
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },'images','tags'])
            ->get();

        // Transform the activities to include image URLs
        // $transformedActivities = $activities->map(function ($activity) {
        //     return [
        //         'id' => $activity->id,
        //         'city_id' => $activity->city_id,
        //         'cover_image' => $activity->cover_image ? asset("storage/{$activity->cover_image}") : null,
        //         'private_car_activity' => $activity->private_car_activity,
        //         'group_activity' => $activity->group_activity,
        //         'translations' => $activity->translations,
        //         'tags' => $activity->tags,
        //         // Add more fields as needed
        //     ];
        // });

        return response()->json(['activities' => $activities]);
    }

    public function getByTag($tagId, Request $request, $lang = null){
        $lang = $lang ?? request('lang') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $tag = Tag::findOrFail($tagId);

        // Retrieve all activities with translations and image URLs for the specified locale and tag
        $activities = Activity::whereHas('tags', function ($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        })->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },'images','tags'])
            ->get();

        // Transform the activity to include image URLs
        // $transformedActivity = $activities->map(function ($activity) {
        //     return [
        //         'id' => $activity->id,
        //         'city_id' => $activity->city_id,
        //         'cover_image' => $activity->cover_image ? asset("storage/{$activity->cover_image}") : null,
        //         'private_car_activity' => $activity->private_car_activity,
        //         'group_activity' => $activity->group_activity,
        //         'translations' => $activity->translations,
        //         'tags' => $activity->tags,
        //         // Add more fields as needed
        //     ];
        // });

        return response()->json(['activities' => $activities]);
    }

    public function addActivity(AddActivityRequest $request){
        $data = $request->all();
        $validationError = validateTranslations($data['translations']);
        if ($validationError !== null) {
            return $validationError;
        }
        // try {
            \DB::beginTransaction();
            // Create the main activity record
            $activity = Activity::create([
                'city_id' => $data['city_id'],
                'area_id' => $data['area_id'] ?? null,
                'destination_type_id' => $data['destination_type_id'],
            ]);
             // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                addImageToModel($activity, $coverImage, true,'activity_images');
            }
             // Validate and store the images
             if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    addImageToModel($activity, $image, false,"activity_images");
                }
            }
            // Add or link tags to the activity
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($activity,$tagData);
                }
            }
            // Add translations for the activity
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($activity, ActivityTranslation::class, $translationData,'activity_id');
            }
            $activity->save();
            \DB::commit();

            return response()->json(['message' => 'Activity added successfully']);
        // } catch (\Exception $e) {
        //     \DB::rollBack();
        //     return response()->json(['error' => 'Failed to add activity.' . $e->getMessage()], 500);
        // }
    }

    public function editActivity(EditActivityRequest $request, $id){
        $data = $request->all();

        try {
            \DB::beginTransaction();

            // Find the activity by ID
            $activity = Activity::findOrFail($id);
            $validationError = validateTranslations($data['translations']);

            if ($validationError !== null) {
                return $validationError;
            }

            // Update the main activity record
            $activity->update($data);

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                updateImageInModel($activity, $coverImage, true,'activity_images');
            }
            // Handle activity images upload and storage
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                updateImageInModel($activity, $images , false,"activity_images");
            }

            // Add or link tags to the activity
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($activity,$tagData);
                }
            }

            // Update or create translations for the activity
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($activity, ActivityTranslation::class, $translationData,'activity_id');
            }
            $activity->save();
            \DB::commit();

            return response()->json(['message' => 'Activity updated successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to update activity.' . $e->getMessage()], 500);
        }
    }

    public function deleteActivity($id){
        try {
            $activity = Activity::findOrFail($id);
            // Delete translations
            $activity->translations()->delete();
            // Delete the activity
            $activity->delete();
            return response()->json(['message' => 'activity deleted successfully']);
        }
        catch (\Exception $e){
            return response()->json(['error' => 'Failed find activity to delete.'], 500);
        }
    }
}
