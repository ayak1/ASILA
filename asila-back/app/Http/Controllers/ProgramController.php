<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Program;
use App\Models\ProgramTranslation;
use App\Models\Locale;
use App\Models\Activity;
use App\Models\City;
use App\Models\Area;
use App\Models\Tag;

use App\Http\Requests\AddProgramRequest;
use App\Http\Requests\EditProgramRequest;

class ProgramController extends Controller
{
    public function index($lang = null){
        $locale = getLocale($lang);

        $programs = Program::with(['translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        }, 'images', 'areas', 'activities', 'tags'])
        ->get();

        $transformedPrograms = $programs->map(function ($program) {
            return $this->transformProgram($program);
        });
        return response()->json(['programs' => $transformedPrograms]);
    }

    public function getByArea($areaId, Request $request, $lang = null){
        $locale = getLocale($lang);

        $area = Area::find($areaId);
        if(!$area){
            return response()->json(['error' => 'Area not found'], 404);
        }

        $programs = Program::whereHas('areas', function ($query) use ($areaId) {
            $query->where('areas.id', $areaId);
        })->with(['translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        }])
        ->get();

        $transformedPrograms = $programs->map(function ($program) {
            return $this->transformProgram($program);
        });
        return response()->json(['programs' => $transformedPrograms]);
    }

    public function getByCity($cityId, Request $request, $lang = null) {
        $locale = getLocale($lang);

        $city = City::find($cityId);
        if(!$city){
            return response()->json(['error' => 'City not found'], 404);
        }

        // Paginate the results
        $programs = Program::where('city_id', $city->id)
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'activities.translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images'])
            ->paginate(10); // Adjust the pagination limit as per your requirement

        // Transform programs as needed
        $transformedPrograms = $programs->map(function ($program) {
            return $this->transformProgram($program);
        });

        return response()->json([
            'programs' => $transformedPrograms,
            'meta' => [
                'current_page' => $programs->currentPage(),
                'last_page' => $programs->lastPage(),
                'total' => $programs->total(),
            ]
        ]);
    }



    public function getByTag($tagId, Request $request, $lang = null){
        $locale = getLocale($lang);

        $tag = Tag::findOrFail($tagId);

        $programs = Program::whereHas('tags', function ($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        })->with(['translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        }])
        ->get();

        $transformedPrograms = $programs->map(function ($program) {
            return $this->transformProgram($program);
        });
        return response()->json(['programs' => $transformedPrograms]);
    }

    public function getProgram($id, $lang = null){
        $locale = getLocale($lang);
        // dd("program",$id);
        $program = Program::where('id', $id)
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'activities.translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            },
            'images'])
            ->first();

        if (!$program) {
            return response()->json(['error' => 'Program not found'], 404);
        }

        $formattedProgram = $this->transformProgram($program);

        return response()->json(['program' => $formattedProgram]);
    }

    // AddProgram, EditProgram, and deleteProgram methods remain unchanged
    public function addProgram(AddProgramRequest $request){
        $data = $request->all();
        // Check if translations for all three languages are provided
        $validationError = validateTranslations($data['translations']);

        if ($validationError !== null) {
            return $validationError;
        }

        try {
            \DB::beginTransaction();

            $program = Program::create([
                'city_id' => $data['city_id'],
                'private_car_program' => $data['private_car_program'],
                'group_program' => $data['group_program'],
            ]);


            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($program, ProgramTranslation::class, $translationData,'program_id');
            }
            // Add or link tags to the program for all translations
            if (isset($data['activities'])) {

                foreach ($data['activities'] as $activityData) {
                    $activity = Activity::findOrFail($activityData['activity_id']);
                    if ($activity) {
                        if (!$program->activities()->where('activity_id', $activity->id)->exists()) {
                            // Check if the activity's city ID matches the program's city ID
                            if ($activity->city_id != $data['city_id']) {
                                \DB::rollBack();
                                return response()->json(['error' => 'Selected activity is not in the same city as the program'], 400);
                            }
                            // Attach the activity to the program
                            $program->activities()->attach($activity->id);
                        }
                    }
                }
            }
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($program,$tagData);
                }
            }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                addImageToModel($program, $coverImage, true,"program_images");
            }
            // Handle program images upload and storage
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    addImageToModel($program, $image, false,"program_images");
                }
            }
            if ($request->has('areas')) {
                $program->areas()->sync($request->input('areas'));
            }

            if ($request->has('activities')) {
                $program->activities()->sync($request->input('activities'));
            }
             // Attach activities to the program
            //  if (isset($data['activity_ids']) && is_array($data['activity_ids'])) {
            //     $program->activities()->attach($data['activity_ids']);
            // }


            \DB::commit();

            return response()->json(['message' => 'Program added successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to add program.'. $e->getMessage()], 500);
        }
    }

    public function editProgram(EditProgramRequest $request, $id){
        $data = $request->all();
        try {
            \DB::beginTransaction();
            // Find the program by ID
            $program = Program::findOrFail($id);
            // Check if translations for all three languages are provided
            $validationError = validateTranslations($data['translations']);
            if ($validationError !== null) {
                return $validationError;
            }
            // Update the main program record
            $program->update([
                'private_car_program' => $data['private_car_program'],
                'group_program' => $data['group_program'],
            ]);

            foreach ($data['translations'] as $translationData) {
                addTranslationToModel($program, ProgramTranslation::class, $translationData,'program_id');
            }
            if ($request->has('tags')) {
                foreach ($data['tags'] as $tagData) {
                    addOrUpdateTagToModel($program,$tagData);
                }
            }
            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image');
                updateImageInModel($program, $coverImage, true,'program_images');
            }
            // Handle program images upload and storage
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                updateImageInModel($program, $images , false,"program_images");
            }
            \DB::commit();

            return response()->json(['message' => 'Program updated successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to update program.' . $e->getMessage()], 500);
        }
    }



    public function deleteProgram($id)
    {
        try {
            $program = Program::findOrFail($id);

            // Delete all translations related to the program
            $program->translations()->delete();

            // Delete all images related to the program
            $program->images()->delete();

            // Detach all areas related to the program
            $program->areas()->detach();

            // Detach all activities related to the program
            $program->activities()->detach();

            // Detach all tags related to the program
            $program->tags()->detach();

            // Delete the program itself
            $program->delete();

            return response()->json(['message' => 'Program deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete program: ' . $e->getMessage()], 500);
        }
    }
    private function transformProgram($program) {
        $formattedImages = $program->images->map(function ($image) {
            return ['path' => asset('storage/' . $image->path)];
        });

        return [
            'id' => $program->id,
            'private_car_program' => $program->private_car_program,
            'group_program' => $program->group_program,
            'title' => $program->translations->first()->title,
            'short_description' => $program->translations->first()->short_description,
            'full_description' => $program->translations->first()->full_description,
            'cover_image' => asset('storage/' . $program->images->firstWhere('is_cover', true)->path),
            'images' => $formattedImages,
            'program_activities' => transformActivities($program->activities),
        ];
    }
}
