<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApartmentType;
use App\Models\Locale;
use App\Http\Requests\ApartmentTypeRequest;
use Illuminate\Support\Facades\App;


class ApartmentTypeController extends Controller
{
    public function getAllTypes($lang  = null)
    {
        $lang = $lang ?? request('lang') ?? 'ar';

        $locale = Locale::where('code', $lang)->first();

        if (!$locale) {
            return response()->json(['error' => 'Locale not found'], 404);
        }
        $apartmentTypes = ApartmentType::whereHas('translations', function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        })->get();

        $formattedTypes = $apartmentTypes->map(function ($apartmentType) use ($locale) {
            $translation = $apartmentType->translations->where('locale_id', $locale->id)->first();
            return [
                'id' => $apartmentType->id,
                'type' => optional($translation)->type,
            ];
        });

        return response()->json(['apartment_types' => $formattedTypes]);
    }

    public function getType($id,$lang  = null)
    {
        $lang = $lang ?? request('lang') ?? 'ar';

        $locale = Locale::where('code', $lang)->first();

        if (!$locale) {
            return response()->json(['error' => 'Locale not found'], 404);
        }
        $apartmentType = ApartmentType::where('id',$id)->with(['translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        }])
        ->get();

        return response()->json(['apartment_type' => $apartmentType]);
    }

    public function addType(ApartmentTypeRequest $request)
    {
        $validatedData = $request->validated();

        $apartmentType = ApartmentType::create($validatedData);

        return response()->json(['apartment_type' => $apartmentType]);
    }

    public function deleteType($id)
    {
        $apartmentType = ApartmentType::findOrFail($id);

        $apartmentType->delete();

        return response()->json(['message' => 'Apartment type deleted successfully']);
    }

    public function editType(ApartmentTypeRequest $request, $id)
    {
        $apartmentType = ApartmentType::findOrFail($id);

        $validatedData = $request->validated();

        $apartmentType->update($validatedData);

        return response()->json(['apartment_type' => $apartmentType]);
    }
}
