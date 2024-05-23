<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\ApartmentTranslation;
use App\Models\locale;
use Illuminate\Support\Str;

use App\Http\Requests\AddApartmentRequest;
use Illuminate\Support\Facades\App;


class ApartmentController extends Controller
{
    public function getByType($typeId, Request $request,$lang  = null)
    {
        $lang = $lang ?? request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        if (!$locale) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }

        try {
            // Retrieve all apartments with translations for the specified locale
            $apartments = Apartment::where('apartment_type_id', $typeId)
                ->with(['translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                }])
                ->get();

            return response()->json(['apartments' => $apartments]);
        } catch (\Exception $e) {
            // \Log::error('Failed to fetch apartments by type. ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch apartments by type.'], 500);
        }
    }

    public function getApartment($id, Request $request,$lang  = null)
    {
        $lang = $lang ??  request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        if (!$locale) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }

        try {
            $apartment = Apartment::where('id', $id)
                ->with(['translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                }])
                ->first();

            if (!$apartment) {
                return response()->json(['error' => 'Apartment not found'], 404);
            }
            // $formattedApartment = $apartmentsForSell->map(function ($apartment) {
                $formattedImages = [];
                foreach ($apartment->images as $image) {
                    if ($image->is_cover != true) {
                        $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                    }
                }
                $coverImage = $apartment->images->firstWhere('is_cover', true);

                $coverImageUrl = $coverImage ? asset('storage/' . $coverImage->path) : null;
                $formattedApartment =  [
                    'id' => $apartment->id,
                    'is_for_rent'=>$apartment->is_for_rent,
                    'is_for_sell'=>$apartment->is_for_sell,
                    'cover_image' => $coverImageUrl,
                    'images' => $formattedImages,
                    'longitude' => $apartment->longitude,
                    'latitude' => $apartment->latitude,
                    'space' => $apartment->space,
                    'has_parking' => $apartment->has_parking,
                    'room_number' => $apartment->room_number,
                    'baths_number' => $apartment->baths_number,
                    'parking_number' => $apartment->parking_number,
                    'pools_number' => $apartment->pools_number,
                    'in_installments' => $apartment->in_installments,
                    'floor' => $apartment->floor,
                    'available_for_rent_at' => $apartment->available_for_rent_at,
                    'sell_price' => $apartment->sell_price,
                    'sell_per_month' => $apartment->sell_per_month,
                    'rent_per_month' => $apartment->rent_per_month,
                    'address' => $apartment->translations->first()->address,
                    'description' => $apartment->translations->first()->description,
                    'title' => $apartment->translations->first()->title,

                ];
            // });
            return response()->json(['apartment' => $formattedApartment]);
        } catch (\Exception $e) {
            // \Log::error('Failed to fetch apartment. ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch apartment.'], 500);
        }
    }
    // Retrieve all apartments available for rent
    public function getForRent($lang = null)
    {

        $lang = $lang ?? request()->header('Accept-Language') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        try {
            $apartmentsForSell = Apartment::where('is_for_rent', true)
                ->with(['translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                }])->get();

            $formattedApartments = $apartmentsForSell->map(function ($apartment) {
                $formattedImages = [];
                foreach ($apartment->images as $image) {
                    if ($image->is_cover != true) {
                        $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                    }
                }
                $coverImage = $apartment->images->firstWhere('is_cover', true);

                $coverImageUrl = $coverImage ? asset('storage/' . $coverImage->path) : null;
                return [
                    'id' => $apartment->id,
                    'is_for_rent'=>$apartment->is_for_rent,
                    'is_for_sell'=>$apartment->is_for_sell,
                    'cover_image' => $coverImageUrl,
                    'images' => $formattedImages,
                    'longitude' => $apartment->longitude,
                    'latitude' => $apartment->latitude,
                    'space' => $apartment->space,
                    'has_parking' => $apartment->has_parking,
                    'room_number' => $apartment->room_number,
                    'baths_number' => $apartment->baths_number,
                    'parking_number' => $apartment->parking_number,
                    'pools_number' => $apartment->pools_number,
                    'floor' => $apartment->floor,
                    'available_for_rent_at' => $apartment->available_for_rent_at,
                    // 'rent_price' => $apartment->rent_price,
                    'rent_per_month' => $apartment->rent_per_month,
                    'address' => $apartment->translations->first()->address,
                    'description' => $apartment->translations->first()->description,
                    'title' => $apartment->translations->first()->title,

                ];
            });

            return response()->json([
                'apartments' => $formattedApartments,
            ]);
        } catch (\Exception $e) {
            // Log the error if needed
            // \Log::error('Failed to fetch apartments for sell. ' . $e->getMessage());

            return response()->json(['error' => 'Failed to fetch apartments for sell.'], 500);
        }
    }
    // Retrieve all apartments available for sale
    public function getForSell($lang = null){
        $lang = $lang ??request()->header('Accept-Language')  ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        if (!$locale) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }
        try {
            $apartmentsForSell = Apartment::where('is_for_sell', true)
                ->with(['translations' => function ($query) use ($locale) {
                    $query->where('locale_id', $locale->id);
                }])->get();

            $formattedApartments = $apartmentsForSell->map(function ($apartment) {
                $formattedImages = [];
                foreach ($apartment->images as $image) {
                    if ($image->is_cover != true) {
                        $formattedImages[] = ['path' => asset('storage/' . $image->path)];
                    }
                }
                $coverImage = $apartment->images->firstWhere('is_cover', true);

                $coverImageUrl = $coverImage ? asset('storage/' . $coverImage->path) : null;
                return [
                    'id' => $apartment->id,
                    'is_for_rent'=>$apartment->is_for_rent,
                    'is_for_sell'=>$apartment->is_for_sell,
                    'cover_image' => $coverImageUrl,
                    'images' => $formattedImages,
                    'longitude' => $apartment->longitude,
                    'latitude' => $apartment->latitude,
                    'space' => $apartment->space,
                    'has_parking' => $apartment->has_parking,
                    'room_number' => $apartment->room_number,
                    'baths_number' => $apartment->baths_number,
                    'parking_number' => $apartment->parking_number,
                    'pools_number' => $apartment->pools_number,
                    'floor' => $apartment->floor,
                    'available_for_rent_at' => $apartment->available_for_rent_at,
                    'sell_price' => $apartment->sell_price,
                    'sell_per_month' => $apartment->sell_per_month,
                    'address' => $apartment->translations->first()->address,
                    'description' => $apartment->translations->first()->description,
                    'title' => $apartment->translations->first()->title,
                ];
            });

            return response()->json([
                'apartments' => $formattedApartments,
            ]);
        } catch (\Exception $e) {
            // Log the error if needed
            // \Log::error('Failed to fetch apartments for sell. ' . $e->getMessage());

            return response()->json(['error' => 'Failed to fetch apartments for sell.'], 500);
        }
    }

    public function addApartment(AddApartmentRequest $request){
        // If the request data fails validation, Laravel will automatically return a response with the validation errors.
        $data = $request->all();

        // Check if translations for all three languages are provided
         $validationError = validateTranslations($data['translations']);

        if ($validationError !== null) {
            return $validationError;
        }

        // Add logic to create the main apartment record only if the request is valid
        try {
            \DB::beginTransaction();

            // Create the main apartment record
            $apartment = Apartment::create($data);
            // Add translations for the apartment
            foreach ($data['translations'] as $translationData) {
                // Generate a slug for the address
            $slug = Str::slug($translationData['address']);
            // Append "for-rent" or "for-sale" based on the apartment type
            $slug .= ($apartment->is_for_rent == 1) ? '-for-rent' : '-for-sale';
            // Ensure the slug is unique
            $uniqueSlug = $slug;
            $count = 1;
            while (ApartmentTranslation::where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $slug . '-' . $count;
                $count++;
            }
            $translationData['slug'] = $uniqueSlug;
                addTranslationToModel($apartment, ApartmentTranslation::class, $translationData,'apartment_id');
            }
            // Add or link tags to the apartment for all translations
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($apartment,$tagData);
                }
            }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                addImageToModel($apartment, $coverImage, true,"apartment_images");
            }
            // Handle apartment images upload and storage
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    addImageToModel($apartment, $image, false,"apartment_images");
                }
            }
            \DB::commit();

            return response()->json(['message' => 'Apartment added successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();
            // \Log::error('Failed to add apartment. ' . $e->getMessage());
            // Log the exception or handle it appropriately
            return response()->json(['error' => 'Failed to add apartment.' . $e->getMessage() ], 500);
        }
    }

    public function editApartment(AddApartmentRequest $request, $id){
        $data = $request->all();
        try {
            $apartment = Apartment::findOrFail($id);
            $validationError = validateTranslations($data['translations']);
            if ($validationError !== null) {
                return $validationError;
            }
            // Update the main apartment record
            $apartment->update($data);

            // Update translations
            foreach ($request->input('translations', []) as $translationData) {
                addTranslationToModel($apartment, ApartmentTranslation::class, $translationData,'apartment_id');
            }
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($apartment,$tagData);
                }
            }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                updateImageInModel($apartment, $coverImage, true,'apartment_images');
            }
            // Handle apartment images upload and storage
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                updateImageInModel($apartment, $images , false,"apartment_images");
            }
            return response()->json(['message' => 'Apartment updated successfully']);
        } catch (\Exception $e) {
            \Log::error('Failed to update apartment. ' . $e->getMessage());

            return response()->json(['error' => 'Failed to update apartment.'], 500);
        }
    }

    public function deleteApartment($id){
        try {
            $apartment = Apartment::findOrFail($id);
            // Delete translations
        $apartment->translations()->delete();

        // Delete the apartment
        $apartment->delete();
        return response()->json(['message' => 'Apartment deleted successfully']);
        } catch (\Exception $e) {
            // \Log::error('Failed to delete apartment. ' . $e->getMessage());

            return response()->json(['error' => 'Failed to delete apartment.'], 500);
        }
    }

}
