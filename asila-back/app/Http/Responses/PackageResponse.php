<?php
namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class PackageResponse
{
    public static function package($name,$package)
    {
        $formattedPackage = [];

        foreach ($package as $item) {
            $formattedPackageDays = [];

            foreach ($item->packageDays as $packageDay) {
                $formattedActivities = [];

                foreach ($packageDay->activities as $activity) {
                    $coverImage = $activity->images->where('is_cover', true)->first();

                    // Customize the structure of the activity data as needed
                    $formattedActivity = [
                        'id' => $activity->id,
                        'name' => optional($activity->translations->first())->name,
                        'short_description' => optional($activity->translations->first())->short_description,
                        'full_description' => optional($activity->translations->first())->full_description,

                        'cover_image' => $coverImage ? $coverImage->path : null,
                    ];

                    $formattedActivities[] = $formattedActivity;
                }

                // Customize the structure of the package_day data as needed
                $formattedPackageDay = [
                    'id' => $packageDay->id,
                    'day_number' => $packageDay->day_number,
                    'activities' => $formattedActivities,
                ];

                $formattedPackageDays[] = $formattedPackageDay;
            }
            $formattedPackageContains = [];

            foreach ($item->packageContains as $packageContain) {
                // Customize the structure of the package_contain data as needed
                $formattedPackageContain = [
                    // 'id' => $packageContain->id,
                    'content' => $packageContain->translations->first()->content ?? null,
                ];

                $formattedPackageContains[] = $formattedPackageContain;
            }
            $formattedTags = [];

            foreach ($item->tags as $tag) {
                // Customize the structure of the tag data as needed
                $formattedTag = [
                    'id' => $tag->id,
                    'name' => $tag->translations->first()->name ?? null,
                ];

                $formattedTags[] = $formattedTag;
            }
            $formattedImages = [];

            foreach ($item->images as $image) {
                // Customize the structure of the tag data as needed
                $formattedImage = [
                    'id' => $image->id,
                    'is_cover' => $image->is_cover ,
                    'image_path' => $image->path ,
                ];

                $formattedImages[] = $formattedImage;
            }
            // Customize the structure of the response as needed
            $formattedItem = [
                'id' => $item->id,
                'duration' => $item->duration,
                'price' => $item->price,
                'title' => $item->translations->first()->title ?? null,
                'description' => $item->translations->first()->short_description ?? null,
                'package_days' => $formattedPackageDays,
                'package_contains' => $formattedPackageContains,
                'tags' => $formattedTags,
                'images' => $formattedImages,
            ];

            $formattedPackage[] = $formattedItem;
        }

        return response()->json([$name => $formattedPackage]);
    }
}
// {
//     "package": [
//         {
//             "translations": [
//                 {
//                     "id": 6,
//                     "package_id": 3,
//                     "locale_id": 2,
//                     "title": "مثال على الباكج",
//                     "short_description": "وصف قصير باللغة العربية",
//                     "long_description": "وصف طويل باللغة العربية",
//                     "created_at": "2023-12-18T15:11:03.000000Z",
//                     "updated_at": "2023-12-18T15:11:03.000000Z"
//                 }
//             ],
//             "package_days": [
//                 {
//                     "id": 5,
//                     "package_id": 3,
//                     "day_number": 1,
//                     "created_at": "2023-12-18T15:11:03.000000Z",
//                     "updated_at": "2023-12-18T15:11:03.000000Z",
//                     "activities": [
//                         {
//                             "id": 1,
//                             "city_id": 1,
//                             "area_id": 1,
//                             "created_at": "2023-12-18T15:09:33.000000Z",
//                             "updated_at": "2023-12-18T15:09:33.000000Z",
//                             "pivot": {
//                                 "package_day_id": 5,
//                                 "activity_id": 1
//                             },
//                             "translations": [
//                                 {
//                                     "id": 1,
//                                     "activity_id": 1,
//                                     "locale_id": 2,
//                                     "name": "ed",
//                                     "short_description": "Experience luxury like never before.",
//                                     "full_description": "Our luxury hotel in Antalya offers unparalleled comfort and sophistication.",
//                                     "created_at": "2023-12-18T15:09:33.000000Z",
//                                     "updated_at": "2023-12-18T15:09:33.000000Z"
//                                 }
//                             ]
//                         },
//                         {
//                             "id": 2,
//                             "city_id": 1,
//                             "area_id": 1,
//                             "created_at": "2023-12-18T15:09:51.000000Z",
//                             "updated_at": "2023-12-18T15:09:51.000000Z",
//                             "pivot": {
//                                 "package_day_id": 5,
//                                 "activity_id": 2
//                             },
//                             "translations": [
//                                 {
//                                     "id": 4,
//                                     "activity_id": 2,
//                                     "locale_id": 2,
//                                     "name": "sss",
//                                     "short_description": "Experience luxury like never before.",
//                                     "full_description": "Our luxury hotel in Antalya offers unparalleled comfort and sophistication.",
//                                     "created_at": "2023-12-18T15:09:51.000000Z",
//                                     "updated_at": "2023-12-18T15:09:51.000000Z"
//                                 }
//                             ]
//                         }
//                     ]
//                 },
//                 {
//                     "id": 6,
//                     "package_id": 3,
//                     "day_number": 2,
//                     "created_at": "2023-12-18T15:11:03.000000Z",
//                     "updated_at": "2023-12-18T15:11:03.000000Z",
//                     "activities": []
//                 }
//             ],
//             "package_contains": [
//                 {
//                     "id": 4,
//                     "created_at": "2023-12-18T15:11:03.000000Z",
//                     "updated_at": "2023-12-18T15:11:03.000000Z",
//                     "pivot": {
//                         "package_id": 3,
//                         "package_contain_id": 4
//                     },
//                     "translations": [
//                         {
//                             "id": 3,
//                             "package_contain_id": 4,
//                             "locale_id": 2,
//                             "content": "Content 1",
//                             "created_at": "2023-12-18T15:11:03.000000Z",
//                             "updated_at": "2023-12-18T15:11:03.000000Z"
//                         }
//                     ]
//                 },
//                 {
//                     "id": 5,
//                     "created_at": "2023-12-18T15:11:03.000000Z",
//                     "updated_at": "2023-12-18T15:11:03.000000Z",
//                     "pivot": {
//                         "package_id": 3,
//                         "package_contain_id": 5
//                     },
//                     "translations": [
//                         {
//                             "id": 4,
//                             "package_contain_id": 5,
//                             "locale_id": 2,
//                             "content": "Content 2",
//                             "created_at": "2023-12-18T15:11:03.000000Z",
//                             "updated_at": "2023-12-18T15:11:03.000000Z"
//                         }
//                     ]
//                 }
//             ],
//             "tags": [
//                 {
//                     "id": 5,
//                     "created_at": "2023-12-18T15:11:03.000000Z",
//                     "updated_at": "2023-12-18T15:11:03.000000Z",
//                     "pivot": {
//                         "taggable_type": "App\\Models\\Package",
//                         "taggable_id": 3,
//                         "tag_id": 5
//                     },
//                     "translations": [
//                         {
//                             "id": 5,
//                             "tag_id": 5,
//                             "locale_id": 2,
//                             "name": "Tag 1",
//                             "created_at": "2023-12-18T15:11:03.000000Z",
//                             "updated_at": "2023-12-18T15:11:03.000000Z"
//                         }
//                     ]
//                 },
//                 {
//                     "id": 6,
//                     "created_at": "2023-12-18T15:11:03.000000Z",
//                     "updated_at": "2023-12-18T15:11:03.000000Z",
//                     "pivot": {
//                         "taggable_type": "App\\Models\\Package",
//                         "taggable_id": 3,
//                         "tag_id": 6
//                     },
//                     "translations": [
//                         {
//                             "id": 6,
//                             "tag_id": 6,
//                             "locale_id": 2,
//                             "name": "Tag 2",
//                             "created_at": "2023-12-18T15:11:03.000000Z",
//                             "updated_at": "2023-12-18T15:11:03.000000Z"
//                         }
//                     ]
//                 }
//             ],
//             "images": []
//         }
//     ]

