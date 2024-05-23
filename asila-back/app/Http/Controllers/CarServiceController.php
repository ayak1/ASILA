<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarService;
use App\Models\CarServiceTranslation;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\TagTranslation;

use App\Http\Requests\AddCarServiceRequest;
use App\Http\Requests\EditCarServiceRequest;
class CarServiceController extends Controller
{
    public function index($lang = null){
        $lang = $lang ?? request()->header('Accept-Language')  ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $carServices = CarService::with(['translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        },'images'])
        ->get();
        $formattedCarServices = $carServices->map(function ($carService) {
            $coverImage = $carService->images->firstWhere('is_cover', true);
            return [
                'title' => $carService->translations->first()->title,
                'description' => $carService->translations->first()->description,
                'image' => asset('storage/' . $coverImage->path),
            ];
        });
        return response()->json(['carServices' => $formattedCarServices]);
    }

    public function getCarService($id, $lang = null){
        $lang = $lang ?? request()->header('Accept-Language')  ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $carService = CarService::where('id', $id)
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            }])
            ->first();

        // $transformedCarService = [
        //     'id' => $carService->id,
        //     'name' => $carService->translations->first()->name ?? null,
        //     // Add more fields as needed
        // ];

        return response()->json(['carService' => $carService]);
    }

    public function addCarService(AddCarServiceRequest $request){
        $data = $request->all();
        $validationError = validateTranslations($data['translations']);

        if ($validationError !== null) {
            return $validationError;
        }

        try {
            \DB::beginTransaction();
            $carService = CarService::create();
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($carService, CarServiceTranslation::class, $translationData,'car_service_id');
            }
            // Add or link tags to the program
            //  if ($request->has('tags')) {
            //     foreach ($data['tags'] as $tagData) {
            //         addOrUpdateTagToModel($carService,$tagData);
            //     }
            // }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                addImageToModel($carService, $coverImage, true,"carService_images");
            }
            // Handle carService images upload and storage
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    addImageToModel($carService, $image, false,"carService_images");
                }
            }
            $carService->save();
            \DB::commit();
            return response()->json(['message' => 'car Service added successfully']);

        }catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to add car Service'. $e->getMessage()], 500);
        }
    }

    public function editCarService(EditCarServiceRequest $request, $id){
        $data = $request->all();

        try {
            \DB::beginTransaction();

            $carService = CarService::findOrFail($id);
            $validationError = validateTranslations($data['translations']);

            if ($validationError !== null) {
                return $validationError;
            }

            // Update translations for the tag
            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($carService, CarServiceTranslation::class, $translationData,'car_service_id');
            }
            // Add or link tags to the carService
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($carService,$tagData);
                }
            }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                updateImageInModel($carService, $coverImage, true,'carService_images');
            }
            // Handle city images upload and storage
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                updateImageInModel($carService, $images , false,"carService_images");
            }
            \DB::commit();

            return response()->json(['message' => 'car service updated successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to update car service. ' . $e->getMessage()], 500);
        }
    }

    public function deleteCarService($id){
        try {
            $carService = CarService::findOrFail($id);
            $carService->translations()->delete();
            $carService->delete();

            return response()->json(['message' => 'car service deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete car service. ' . $e->getMessage()], 500);
        }
    }
}
