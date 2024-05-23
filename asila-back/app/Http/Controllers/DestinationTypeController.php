<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locale;
use App\Models\Program;
use App\Models\Package;
use App\Models\Activity;
use App\Models\City;
use App\Models\DestinationType;
use App\Models\DestinationTypeTranslation;

use App\Http\Requests\AddDestinationTypeRequest;

class DestinationTypeController extends Controller
{
    public function index($lang = null){
        $lang = $lang ?? request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $destinations = DestinationType::with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images'
        ])->get();
        $formattedDestinations = $destinations->map(function ($destination) {
            $translation = $destination->translations->first(); // Assuming you want the first translation
            $formattedImages = [];
            $coverImage = null;
            if($destination->images){
                foreach ($destination->images as $image) {
                    if ($image->is_cover != true) {
                        $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                    }
                }
                $coverImage = asset('storage/' . $destination->images->firstWhere('is_cover', true)->path);
            }
            return [
                'id' => $destination->id,
                'name' => $translation ? $translation->name : null,
                'cover_image' => $coverImage,
                'images' => $formattedImages,
            ];
        });

        return response()->json(['destinations' => $formattedDestinations]);
    }
    public function addDestinationType(AddDestinationTypeRequest $request){
        $data = $request->all();

        $validationError = validateTranslations($data['translations']);
        if ($validationError !== null) {
            return $validationError;
        }
        try {
            \DB::beginTransaction();
            $destination = DestinationType::create();
            // Create translations for the city
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($destination, DestinationTypeTranslation::class, $translationData,'destination_type_id');
            }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                addImageToModel($destination, $coverImage, true,"destination_type_images");
            }
            // Handle city images upload and storage
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    addImageToModel($destination, $image, false,"destination_type_images");
                }
            }


            \DB::commit();

            return response()->json(['message' => 'destination type added successfully']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['error' => 'Failed to add destination type.' . $e->getMessage()], 500);
        }
    }

    public function getByCityAndDestinationType($cityId, $destinationTypeId, Request $request){
        $lang = $request->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $destination = DestinationType::where('id',$destinationTypeId)->with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
        ])->first();

        $destinationInfo = [
            'id'=>$destination->id,
            'name' => $destination->translations->first()->name,
        ];
        
        $programs = $this->getProgramsByCityAndDestinationType($cityId, $destinationTypeId, $request, $lang);
        $packages = $this->getPackagesByCityAndDestinationType($cityId, $destinationTypeId, $request, $lang);
        $activities = $this->getActivitiesByCityAndDestinationType($cityId, $destinationTypeId, $request, $lang);
        return response()->json([
            'destination'=> $destinationInfo,
            'programs' => $programs,
            'packages' => $packages,
            'activities' => $activities,
        ]);
    }

    public function getProgramsByCityAndDestinationType($cityId, $destinationTypeId, Request $request, $lang = null){
        $lang = $lang ?? $request->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $city = City::find($cityId);
        if (!$city) {
            return response()->json(['error' => 'City not found'], 404);
        }

        $destinationType = DestinationType::find($destinationTypeId);
        if (!$destinationType) {
            return response()->json(['error' => 'Destination type not found'], 404);
        }

        // Retrieve programs where at least one activity has the same destination type id
        $programs = Program::where('city_id', $cityId)
            ->whereHas('activities', function ($query) use ($destinationTypeId) {
                $query->where('destination_type_id', $destinationTypeId);
            })
            ->with([
                'translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                },
                'activities.translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                },
                'images'
            ])
            ->get();

        $formattedPrograms = [];

        foreach ($programs as $program) {
            $formattedImages = [];

            foreach ($program->images as $image) {
                if (!$image->is_cover) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
            }

            $formattedProgram = [
                'id' => $program->id,
                // 'private_car_program' => $program->private_car_program,
                // 'group_program' => $program->group_program,
                'title' => $program->translations->first()->title,
                // 'short_description' => $program->translations->first()->short_description,
                // 'full_description' => $program->translations->first()->full_description,
                'cover_image' => asset('storage/' . $program->images->firstWhere('is_cover', true)->path),
                // 'images' => $formattedImages,
                // 'program_activities' => []
            ];

            // $formattedActivities = [];

            // foreach ($program->activities as $activity) {
            //     $formattedActivities[] = [
            //         'name' => $activity->translations->first()->name,
            //         'description' => $activity->translations->first()->full_description,
            //         'image' => asset('storage/' . $activity->images->firstWhere('is_cover', true)->path),
            //     ];
            // }

            // $formattedProgram['program_activities'] = $formattedActivities;
            $formattedPrograms[] = $formattedProgram;
        }

        return  $formattedPrograms;
    }

    public function getPackagesByCityAndDestinationType($cityId, $destinationTypeId, Request $request, $lang = null){
        $lang = $lang ?? $request->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $city = City::find($cityId);
        $destinationType = DestinationType::find($destinationTypeId)->with([
            'translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        },]);

        if (!$city) {
            return response()->json(['error' => 'City not found'], 404);
        }

        if (!$destinationType) {
            return response()->json(['error' => 'Destination type not found'], 404);
        }

        // Retrieve all programs with translations, activities, and images for the specified city and destination type
        $packages = Package::where('city_id', $cityId)
            ->whereHas('packageDays.activities', function ($query) use ($destinationTypeId) {
                $query->where('destination_type_id', $destinationTypeId);
            })
            ->with([
                'translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                },
                // 'packageDays.activities.translations' => function ($query) use ($locale) {
                //     $query->where('locale_id', $locale->id);
                // },
                // 'packageDays.translations' => function ($query) use ($locale) {
                //     $query->where('locale_id', $locale->id);
                // },
                // 'packageContains.translations' => function ($query) use ($locale) {
                //     $query->where('locale_id', $locale->id);
                // },
                'images',
            ])->get();



            $formattedPackages = [];

            foreach ($packages as $package) {
                $formattedImages = [];
                foreach ($package->images as $image) {
                    if ($image->is_cover != true) {
                        $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                    }
                }
                $formattedPackage = [
                    'id'=>$package->id,
                    // 'can_choose_hotel'=>$package->can_choose_hotel,
                    // 'duration'=>$package->duration,
                    // 'price'=> $package->price,
                    'title' => $package->translations->first()->title,
                    // 'short_description' => $package->translations->first()->short_description,
                    // 'full_description' => $package->translations->first()->full_description,
                    'cover_image' => asset('storage/' . $package->images->firstWhere('is_cover', true)->path),
                    // 'images' => $formattedImages,
                    // 'days_activities' => [],
                ];

                // foreach ($package->packageDays as $day) {
                //     $formattedActivities = [];
                //     foreach ($day->activities as $activity) {
                //         $formattedActivities[] = [
                //             'name' => $activity->translations->first()->name,
                //             'description' => $activity->translations->first()->full_description,
                //             'image' => asset('storage/' . $activity->images->firstWhere('is_cover', true)->path),
                //         ];
                //     }

                //     $formattedPackage['days_activities'][] = [
                //         'day_number' => $day->day_number,
                //         'content' => $day->translations->first()->content,
                //         'activities' => $formattedActivities,
                //     ];
                // }

                $formattedPackages[] = $formattedPackage;
            }

            return $formattedPackages;
    }

    public function getActivitiesByCityAndDestinationType($cityId, $destinationTypeId, Request $request, $lang = null){
        $lang = $lang ?? request('lang') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $city = City::where('id',$cityId)->first();
        if(!$city){
            return response()->json(['error' => 'city not found']);
        }
        // Retrieve all activities with translations and image URLs for the specified locale and city
        $activities = Activity::where('city_id', $city->id)->where('destination_type_id',$destinationTypeId)
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },'images','tags'])
            ->get();

        $formattedActivities = [];

        foreach ($activities as $activity) {
            $formattedImages = [];
            foreach ($activity->images as $image) {
                if ($image->is_cover != true) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
            }
            $formattedActivity = [
                'id'=>$activity->id,
                // 'price'=> $program->price,
                'name' => $activity->translations->first()->name,
                // 'short_description' => $activity->translations->first()->short_description,
                // 'full_description' => $activity->translations->first()->full_description,
                'cover_image' => asset('storage/' . $activity->images->firstWhere('is_cover', true)->path),
                // 'images' => $formattedImages,
                // 'city_id' => $activity->city_id,
                // 'area_id' => $activity->area_id,
            ];
            $formattedActivities[] = $formattedActivity;

        }


        return  $formattedActivities;
    }
}
