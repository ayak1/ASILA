<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tag;
use App\Models\TagTranslation;
use App\Models\Locale;
use App\Models\Area;
use App\Models\City;

use App\Http\Requests\AddTagRequest;
use App\Http\Requests\EditTagRequest;


class TagController extends Controller
{
    public function index($lang = null){
        $lang = $lang ?? request('lang') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();
        $tags = Tag::with(['translations' => function ($query) use ($locale) {
            $query->where('locale_id', $locale->id);
        }])->get();
        return response()->json(['tags' => $tags]);
    }

    public function getTag($id, $lang = null){
        $lang = $lang ?? request('lang') ?? 'ar';
        $locale = Locale::where('code', $lang)->first();

        $tag = Tag::where('id', $id)
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale_id', $locale->id);
            }])
            ->first();

        $transformedTag = [
            'id' => $tag->id,
            'name' => $tag->translations->first()->name ?? null,
        ];

        return response()->json(['tag' => $transformedTag]);
    }

    public function addTag(AddTagRequest $request){
        $data = $request->all();
        $validationError = validateTranslations($data['translations']);

        if ($validationError !== null) {
            return $validationError;
        }

        try {
            \DB::beginTransaction();
            $tag = Tag::create();
            foreach ($data['translations'] as $translationData) {
                $locale = Locale::where('code', $translationData['locale'])->first();

                if ($locale) {
                    // Create the tag translation
                    $tagTranslation = new TagTranslation($translationData);
                    $tagTranslation->tag()->associate($tag);
                    $tagTranslation->locale()->associate($locale);
                    $tagTranslation->save();
                }
            }
            $tag->save();
            \DB::commit();
            return response()->json(['message' => 'Tag added successfully']);

        }catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to add tag'. $e->getMessage()], 500);
        }
    }

    public function editTag(Request $request, $id){
        $data = $request->all();
        $validationError = validateTranslations($data['translations']);

        if ($validationError !== null) {
            return $validationError;
        }

        try {
            \DB::beginTransaction();

            $tag = Tag::findOrFail($id);

            // Update translations for the tag
            foreach ($data['translations'] as $translationData) {
                $locale = Locale::where('code', $translationData['locale'])->first();

                if ($locale) {
                    // Check if the tag name already exists for other rows (excluding the current tag's translations)
                    $existingTag = TagTranslation::where('name', $translationData['name'])
                        ->where('locale_id', '!=', $locale->id)
                        ->whereNotIn('tag_id', [$tag->id])
                        ->first();

                    if (!$existingTag) {
                        $tagTranslation = $tag->translations()->where('locale_id', $locale->id)->first();

                        if ($tagTranslation) {
                            // Update the existing tag translation
                            $tagTranslation->update($translationData);
                        } else {
                            // Create the tag translation if it doesn't exist
                            $tagTranslation = new TagTranslation($translationData);
                            $tagTranslation->tag()->associate($tag);
                            $tagTranslation->locale()->associate($locale);
                            $tagTranslation->save();
                        }
                    } else {
                        // Tag name already exists for another row, handle accordingly
                        // For now, let's return an error response
                        return response()->json(['error' => 'Tag name already exists for another row.'], 400);
                    }
                }
            }

            \DB::commit();

            return response()->json(['message' => 'Tag updated successfully']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to update tag. ' . $e->getMessage()], 500);
        }
    }

    public function deleteTag($id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->translations()->delete();
            $tag->delete();

            return response()->json(['message' => 'Tag deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete tag. ' . $e->getMessage()], 500);
        }
    }
}
