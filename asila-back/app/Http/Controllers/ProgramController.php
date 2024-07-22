<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Program;
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
