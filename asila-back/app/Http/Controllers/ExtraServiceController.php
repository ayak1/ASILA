<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ExtraService;
use App\Models\ExtraServiceSection;
use App\Models\ExtraServiceTranslation;
use App\Models\ExtraServiceSectionTranslation;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\TagTranslation;
use Illuminate\Support\Str;

use App\Http\Requests\AddExtraServiceRequest;
use App\Http\Requests\EditExtraServiceRequest;

class ExtraServiceController extends Controller
{
    public function index($lang = null){
        $lang = $lang ?? request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $extraServices = ExtraService::with([
            'translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'sections.translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images'
            ])->get();
            $formattedExtraServices = $extraServices->map(function ($extraService) {
                $formattedImages = [];
                foreach ($extraService->images as $image) {
                    if ($image->is_cover != true) {
                        $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                    }
                }
                $coverImage = $extraService->images->firstWhere('is_cover', true);

                $coverImageUrl = $coverImage ? asset('storage/' . $coverImage->path) : null;
                return [
                    'id' => $extraService->id,
                    'title' => $extraService->translations->first()->title,
                    'main_description' => $extraService->translations->first()->main_description,
                    'sections' => $extraService->sections->map(function ($section) {
                        return [
                            'title' => $section->translations->first()->section_title,
                            'description' => $section->translations->first()->section_description,
                            'list_of_adv' => $section->translations->first()->list_of_adv,
                        ];
                    }),
                    'cover_image' => $coverImageUrl,
                    'images' => $formattedImages,
                ];
            });
        return response()->json(['extra_services' => $formattedExtraServices],200);
    }

    // public function getExtraService($id, $lang = null){
    //     $lang = $lang ?? request()->header('Accept-Language') ?? 'ar';
    //     $locale = Locale::where('code', $lang)->first();
    //     $extraService = ExtraService::where('id', $id)
    //         ->with([
    //             'translations' => function ($query) use ($locale) {
    //             $query->where('locale_id', $locale->id);
    //             },
    //             'sections.translations' => function ($query) use ($locale) {
    //             $query->where('locale_id', $locale->id);
    //             },
    //             'tags',
    //             'images',
    //         ])
    //         ->first();
    //         $formattedImages = [];
    //         foreach ($extraService->images as $image) {
    //             if ($image->is_cover != true) {
    //                 $formattedImages[] = ['path' => asset('storage/' . $image->path)];
    //             }
    //         }
    //         $coverImage = $extraService->images->firstWhere('is_cover', true);

    //         $coverImageUrl = $coverImage ? asset('storage/' . $coverImage->path) : null;
    //         $formattedExtraService = [
    //                 'id' => $extraService->id,
    //                 'title' => $extraService->translations->first()->title,
    //                 'main_description' => $extraService->translations->first()->main_description,
    //                 'sections' => $extraService->sections->map(function ($section) {
    //                     return [
    //                         'title' => $section->translations->first()->section_title,
    //                         'description' => $section->translations->first()->section_description,
    //                         'list_of_adv' => $section->translations->first()->list_of_adv,
    //                     ];
    //                 }),
    //                 'cover_image' => $coverImageUrl,
    //                 'images' => $formattedImages,
    //             ];
    //     return response()->json(['extra_service' => $formattedExtraService]);
    // }
    public function getExtraServiceBySlug($slug){
        try {

        $lang = request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $extraService = ExtraService::where('slug', $slug)
            ->with([
                'translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                },
                'sections.translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                },
                'images',
            ])
            ->first();
            $formattedImages = [];
            foreach ($extraService->images as $image) {
                if ($image->is_cover != true) {
                    $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                }
            }
            $coverImage = $extraService->images->firstWhere('is_cover', true);

            $coverImageUrl = $coverImage ? asset('storage/' . $coverImage->path) : null;
            $formattedExtraService = [
                    'id' => $extraService->id,
                    'title' => $extraService->translations->first()->title,
                    'main_description' => $extraService->translations->first()->main_description,
                    'sections' => $extraService->sections->map(function ($section) {
                        return [
                            'title' => $section->translations->first()->section_title,
                            'description' => $section->translations->first()->section_description,
                            'list_of_adv' => $section->translations->first()->list_of_adv,
                        ];
                    }),
                    'cover_image' => $coverImageUrl,
                    'images' => $formattedImages,
                ];

        return response()->json(['extra_service' => $formattedExtraService]);
    } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to find this extra service.'] );
        }
    }
    public function addExtraService(AddExtraServiceRequest $request){
        $data = $request->all();

        // try {
            \DB::beginTransaction();

            // Create the main extra service record
            $englishTranslation = collect($data['translations'])->firstWhere('locale', 'en');
            $title = $englishTranslation['title'] ?? '';

            // Generate the slug from the English translation title
            $slug = Str::slug($title);

            // Create the main extra service record
            $extraService = ExtraService::create([
                'slug' => $slug,
            ]);

            // Add or link tags to the extra service
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($extraService,$tagData);
                }
            }
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($extraService, ExtraServiceTranslation::class, $translationData,'extra_service_id');
            }

            // Handle extra service cover image upload and storage
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                addImageToModel($extraService, $coverImage, true, "extraService_images");
            }
            // Handle extra service images upload and storage
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    addImageToModel($extraService, $image, false, "extraService_images");
                }
            }
            // Add sections for the extra service
            if ($request->has('sections')) {
                foreach ($data['sections'] as $sectionData) {
                    $section = ExtraServiceSection::create(['extra_service_id' => $extraService->id]);
                    $extraService->sections()->save($section);
                    foreach($sectionData['translations'] as $translationData){
                        addTranslationToModel($section, ExtraServiceSectionTranslation::class, $translationData,'e_service_section_id');
                    }
                }
            }
            \DB::commit();

            return response()->json(['message' => 'Extra service added successfully']);
        // } catch (\Exception $e) {
        //     \DB::rollBack();
        //     return response()->json(['error' => 'Failed to add extra service.' . $e->getMessage()], 500);
        // }
    }

    public function editExtraService(EditExtraServiceRequest $request, $id){
        $data = $request->all();
        try {
            \DB::beginTransaction();

            // Find the extra service by ID
            $extraService = ExtraService::findOrFail($id);

            $validationError = validateTranslations($data['translations']);

            if ($validationError !== null) {
                return $validationError;
            }
            // Update or create tags for the extra service
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($extraService,$tagData);
                }
            }
            // Update or create translations for the extra service
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($extraService, ExtraServiceTranslation::class, $translationData,'extra_service_id');
            }

            // Update or create sections for the extra service
            if ($request->has('sections')) {
                foreach ($data['sections'] as $sectionData) {
                    $section = updateOrCreateTranslation($extraService, ExtraServiceSection::class, $sectionData,'extra_service_id');
                    // Update or create translations for the section
                    addTranslationToModel($section, ExtraServiceSectionTranslation::class, $sectionData,'e_service_section_id');
                }
            }

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                updateImageInModel($extraService, $coverImage, true,'extraService_images');
            }
            // Handle extraService images upload and storage
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                updateImageInModel($extraService, $images , false,"extraService_images");
            }

            \DB::commit();

            return response()->json(['message' => 'Extra service updated successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to update extra service.' . $e->getMessage()], 500);
        }
    }

    public function deleteExtraService($id){
        try {
            $extraService = ExtraService::findOrFail($id);

            // Delete tags, translations, sections, and images
            $extraService->tags()->detach();
            $extraService->translations()->delete();
            $extraService->sections()->delete();
            $extraService->images()->delete();

            // Delete the extra service
            $extraService->delete();

            return response()->json(['message' => 'Extra service deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed find extra service to delete.'], 500);
        }
    }
}
