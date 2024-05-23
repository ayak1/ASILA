<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\TagTranslation;
use App\Models\Package;
use App\Models\PackageTranslation;
use App\Models\PackageDay;
use App\Models\PackageDayTranslation;
use App\Models\Activity;
use App\Models\Image;
use App\Models\PackageContain;
use App\Models\PackageContainTranslation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use \DB;

//requests
use App\Http\Requests\AddPackageRequest;
use App\Http\Requests\EditPackageRequest;
// responses
use App\Http\Responses\PackageResponse;

class PackageController extends Controller{
    public function index($lang = null){
    $lang = $lang ?? request()->header('Accept-Language') ?? 'ar';
    $locale = Locale::where('code', $lang)->first();

    $packages = Package::with([
        'translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        },
        'packageDays.activities.translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        },
        'packageContains.translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        },
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
            'title' => $package->translations->first()->title,
            'description' => $package->translations->first()->short_description,
            'cover_image' => asset('storage/' . $package->images->firstWhere('is_cover', true)->path),
            'images' => $formattedImages,
            'days_activities' => [],
        ];

        foreach ($package->packageDays as $day) {
            $formattedActivities = [];
            foreach ($day->activities as $activity) {
                $formattedActivities[] = [
                    'name' => $activity->translations->first()->name,
                    'description' => $activity->translations->first()->full_description,
                    'image' => asset('storage/' . $activity->images->firstWhere('is_cover', true)->path),
                ];
            }

            $formattedPackage['days_activities'][] = [
                'day_number' => $day->day_number,
                'activities' => $formattedActivities,
            ];
        }

        $formattedPackages[] = $formattedPackage;
    }

    return response()->json(['packages' => $formattedPackages]);
    }
    public function getByCity($cityId, Request $request)
    {
        $lang = $request->header('Accept-Language', 'ar');
        $locale = Locale::where('code', $lang)->firstOrFail();
        if(!$locale){
            return response()->json([
                'status' => 'failed',
                'message' => 'this lang is not available'
            ]);
        }
        // Define the number of items per page
        $perPage = 10;

        $packages = Package::where('city_id', $cityId)
            ->with([
                'translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                },
                'packageDays.activities.translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                },
                'packageDays.translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                },
                'packageContains.translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                },
                'images',
            ])->orderBy('id', 'desc')
            ->paginate($perPage);

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
                'can_choose_hotel'=>$package->can_choose_hotel,
                'duration'=>$package->duration,
                'price'=> $package->price,
                'title' => $package->translations->first()->title,
                'short_description' => $package->translations->first()->short_description,
                'full_description' => $package->translations->first()->full_description,
                'cover_image' => asset('storage/' . $package->images->firstWhere('is_cover', true)->path),
                'images' => $formattedImages,
                'days_activities' => [],
                'meta' => [
                    'title' => $package->translations->first()->title,
                    'description' => $package->translations->first()->short_description,
                    'keywords' => $package->keywords->pluck('keyword')->implode(','),
                ]
            ];

            foreach ($package->packageDays as $day) {
                $formattedActivities = [];
                foreach ($day->activities as $activity) {
                    $formattedActivities[] = [
                        'name' => $activity->translations->first()->name,
                        'description' => $activity->translations->first()->full_description,
                        'image' => asset('storage/' . $activity->images->firstWhere('is_cover', true)->path),
                    ];
                }

                $formattedPackage['days_activities'][] = [
                    'day_number' => $day->day_number,
                    'content' => $day->translations->first()->content,
                    'activities' => $formattedActivities,
                ];
            }

            $formattedPackages[] = $formattedPackage;
        }
        return response()->json([
            'status' => 'success',
            'packages' => $formattedPackages,

        ]);
    }
    public function getPackage($id , $lang = null){
        $lang = $lang ?? request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $package = Package::where('id' ,$id)->with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'packageDays.activities.translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'packageDays.translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'packageContains.translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images',
        ])->first();
        $formattedPackage ;

        $formattedImages = [];
        foreach ($package->images as $image) {
            if ($image->is_cover != true) {
                $formattedImages[] = ['path' => asset('storage/' . $image->path)];
            }
        }
            $formattedPackage = [
                'id'=>$package->id,
                'can_choose_hotel'=>$package->can_choose_hotel,
                'duration'=>$package->duration,
                'price'=> $package->price,
                'title' => $package->translations->first()->title,
                'short_description' => $package->translations->first()->short_description,
                'full_description' => $package->translations->first()->full_description,
                'cover_image' => asset('storage/' . $package->images->firstWhere('is_cover', true)->path),
                'images' => $formattedImages,
                'days_activities' => [],
                'meta' => [
                    'title' => $package->translations->first()->title,
                    'description' => $package->translations->first()->short_description,
                    'keywords' => $package->keywords->pluck('keyword')->implode(','),
                ]
            ];
            foreach ($package->packageDays as $day) {
                $formattedActivities = [];
                foreach ($day->activities as $activity) {
                    $formattedActivities[] = [
                        'name' => $activity->translations->first()->name,
                        'description' => $activity->translations->first()->full_description,
                        'image' => asset('storage/' . $activity->images->firstWhere('is_cover', true)->path),
                    ];
                }
                $formattedPackage['days_activities'][] = [
                    'day_number' => $day->day_number,
                    'content' => $day->translations->first()->content,
                    'activities' => $formattedActivities,
                ];
            }

        // return PackageResponse::package('package',$package);
        return response()->json(['package' => $formattedPackage]);
    }

    public function addPackage(AddPackageRequest $request){
        $data = $request->all();
        $validationError = validateTranslations($data['translations']);
        if ($validationError !== null) {
            return $validationError;
        }
        try {
            DB::beginTransaction();

            // Create the main package record
            $package = Package::create([
                'city_id' => $data['city_id'],
                'can_choose_hotel' => $data['can_choose_hotel'],
                'duration' => count($data['package_days']),
                'price' => $data['price'],
            ]);

            // Add translations for the package
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($package, PackageTranslation::class, $translationData,'package_id');
            }
            if ($request->has('keywords')) {
                foreach ($data['keywords'] as $keyword) {
                    $package->keywords()->create(['keyword' => $keyword]);
                }
            }
            // Add days for the package
            foreach ($data['package_days'] as $dayData) {
                $packageDay = PackageDay::create([
                    'package_id' => $package->id,
                    'day_number' => $dayData['day_number'],
                ]);
                // Add activities for the day
                if (isset($dayData['activities'])) {

                    foreach ($dayData['activities'] as $activityData) {
                        $activity = Activity::findOrFail($activityData['activity_id']);
                        if ($activity) {
                            // dd($packageDay->activities());
                            // $activity->packageDays()->attach($packageDay->id);
                            $packageDay->activities()->attach($activity->id);
                        }
                    }
                }
                foreach ($dayData['translations'] as $translationData) {
                    addTranslationToModel($packageDay, PackageDayTranslation::class, $translationData,'package_day_id');
                }
            }

            // Add or link contains to the package=
            foreach ($data['package_contains'] as $containData) {
                $locale = Locale::where('code', $containData['locale'])->first();

                if ($locale) {
                    // Check if the translation for the content already exists
                    $existingTranslation = PackageContainTranslation::where('content', $containData['content'])
                        ->where('locale_id', $locale->id)
                        ->first();

                    if ($existingTranslation) {
                        // If the translation exists, link it to the package
                        $package->packageContains()->attach($existingTranslation->package_contain_id);
                    } else {
                        // If the translation doesn't exist, create a new one and link it to the package
                        $packageContain = PackageContain::create();
                        $package->packageContains()->attach($packageContain->id);

                        // Create the translation
                        $containTranslation = new PackageContainTranslation([
                            'locale_id' => $locale->id,
                            'content' => $containData['content'],
                        ]);

                        $packageContain->translations()->save($containTranslation);
                    }
                }
            }

            // Add or link tags to the package
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                foreach ($tagData as $tagI) {
                    $tagTranslation = TagTranslation::where('name', $tagI['name'])->first();

                    if ($tagTranslation) {
                        $package->tags()->attach($tagTranslation->tag_id);
                    } else {
                        $validationError = validateTranslations($tagData);

                        if ($validationError !== null) {
                            return $validationError;
                        }
                        $locale = Locale::where('code', $tagI['locale'])->first();

                        if ($locale) {
                            $tag = Tag::create();
                            $tag->save();
                            // Create the tag translation
                            $tagTranslation = new TagTranslation($tagI);
                            $tagTranslation->tag()->associate($tag);
                            $tagTranslation->locale()->associate($locale);
                            $tagTranslation->save();
                            // Link the tag to the package
                            $package->tags()->attach($tag->id);
                        }
                    }
                }
            }}

             // Handle cover image upload
             if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                addImageToModel($package, $coverImage, true,"package_images");
            }
            // Handle package images upload and storage
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    addImageToModel($package, $image, false,"package_images");
                }
            }
                        DB::commit();

            return response()->json(['message' => 'Package added successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to add package.' . $e->getMessage()], 500);
        }
    }

    public function editPackage(EditPackageRequest $request, $id){
        $data = $request->all();

        try {
            DB::beginTransaction();

            // Find the package by ID
            $package = Package::findOrFail($id);
            $package->update($data);
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                updateImageInModel($package, $coverImage, true,'package_images');
            }
            // Handle package images upload and storage
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                updateImageInModel($package, $images , false,"package_images");
            }
            // Update or create translations for the package
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($package, PackageTranslation::class, $translationData,'package_id');
            }
            // Update or link tags to the package
            foreach ($data['tags'] as $tagData) {
                addOrUpdateTagToModel($package,$tagData);
            }
            // Update or create days for the package
            if (isset($data['package_days'])) {
                foreach ($data['package_days'] as $dayData) {
                    $day = $this->addOrUpdateTranslation($package, PackageDay::class, $dayData);

                    // Update or create activities for the day
                    if (isset($dayData['activities'])) {
                        $activities = [];
                        foreach ($dayData['activities'] as $activityData) {
                            $activity = $this->addOrUpdateActivity($activityData);
                            $activities[] = $activity->id;
                        }
                        $day->activities()->sync($activities);
                    }
                }
            }

            DB::commit();

            return response()->json(['message' => 'Package updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update package.' . $e->getMessage()], 500);
        }
    }

    public function deletePackage( $id){
        try {
            DB::beginTransaction();

            // Find the package by ID
            $package = Package::findOrFail($id);

            // Delete related package translations
            $package->translations()->delete();

            // Delete related package day translations and activities
            foreach ($package->packageDays as $day) {
                // Delete related activity_package_day records
                $day->activities()->detach();
                $day->translations()->delete();
            }

            // Delete package days
            $package->packageDays()->delete();

            // Detach other relationships
            $package->tags()->detach();
            $package->packageContains()->detach();
            $package->images()->delete();

            // Delete the package
            $package->delete();

            DB::commit();

            return response()->json(['message' => 'Package deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete package. ' . $e->getMessage()], 500);
        }
    }

    public function filterPackages(Request $request){
        $query = Package::query();

        // Filter by the number of days
        if ($request->has('days')) {
            $query->whereHas('packageDays', function ($dayQuery) use ($request) {
                $dayQuery->whereIn('day_number', $request->input('days'));
            });
        }

        // Filter by tags
        if ($request->has('tags')) {
            $query->whereHas('tags', function ($tag) use ($request) {
                $tag.translations()->whereIn('name', $request->input('tags'));
            });
        }

        // Filter by activities
        if ($request->has('activities')) {
            $query->whereHas('packageDays.activities', function ($activityQuery) use ($request) {
                $activityQuery->whereIn('name', $request->input('activities'));
            });
        }

        // Filter by starting price
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        // Filter by city
        if ($request->has('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }

        // Filter by region (You need to have the region field in your packages table)
        if ($request->has('region')) {
            $query->where('region', $request->input('region'));
        }

        $packages = $query->get();

        return response()->json(['packages' => $packages]);
    }

}

