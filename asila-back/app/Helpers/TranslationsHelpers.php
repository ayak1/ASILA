<?php
use App\Models\Locale;

 function validateTranslations($translations)
{
    $requiredLocales = ['en', 'ar', 'tr'];

    $providedLocales = collect($translations)->pluck('locale');

    if (!$providedLocales->contains($requiredLocales[0]) || !$providedLocales->contains($requiredLocales[1]) || !$providedLocales->contains($requiredLocales[2])) {
        return response()->json(['error' => 'Translations for all three languages are required.'], 422);
    }

    return null; // No validation errors
}

function addTranslationToModel($model, $translationModel, $translationData,$fk)
{
    // foreach ($translationsData as $translationData) {
        // }
        $locale = Locale::where('code', $translationData['locale'])->first();
        if ($locale) {
            $translationModel::updateOrCreate(
                [$fk => $model->id, 'locale_id' => $locale->id],
                $translationData
            );

        }
}

function updateOrCreateTranslation($model, $translationModel, $translationData,$fk)
{
    $locale = Locale::where('code', $translationData['locale'])->first();

    if ($locale) {
        return $translationModel::updateOrCreate(
            [$fk => $model->id, 'locale_id' => $locale->id],
            $translationData
        );
    }

    return null;
}

function addTranslation($dataTranslations, $translationModel,$model ){
        $locale = Locale::where('code', $translationData['locale'])->first();
        if ($locale) {
            $translation = new $translationModel($translationData);
            $translation->extraService()->associate($model);
            $translation->locale()->associate($locale);
            $translation->save();
        }
}
