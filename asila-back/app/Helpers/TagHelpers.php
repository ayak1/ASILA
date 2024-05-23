<?php
use App\Models\Locale;
use App\Models\Tag;
use App\Models\TagTranslation;

function addOrUpdateTagToModel($model, $tagData){
    $locale = Locale::where('code', $tagData['locale'])->first();

    if ($locale) {
        // Check if the tag with the same name exists for any translation
        $existingTagTranslation = TagTranslation::where('name', $tagData['name'])
            ->where('locale_id', $locale->id)
            ->first();

        if ($existingTagTranslation) {
            // Link the existing tag to the model
            $model->tags()->attach($existingTagTranslation->tag_id);
        } else {
            // Create a new tag and link it to the model
            $tag = Tag::create();
            $tag->save();
            // Create the tag translation
            $tagTranslation = new TagTranslation($tagData);
            $tagTranslation->tag()->associate($tag);
            $tagTranslation->locale()->associate($locale);
            $tagTranslation->save();
            // Link the new tag to the model
            $model->tags()->attach($tag->id);
        }
    }
}
