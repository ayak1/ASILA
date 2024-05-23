<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\locale;

class AddActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'city_id' => 'required|exists:cities,id',
            // 'area_id' => 'nullable|exists:areas,id',
            'destination_type_id' => 'required|exists:destination_types,id',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string',
            'translations.*.name' => 'required|string|unique:activity_translations,name,NULL,id,locale_id,' . $this->getLocaleId(),
            'translations.*.short_description' => 'nullable|string',
            'translations.*.full_description' => 'nullable|string',
        ];
    }
    public function getLocaleId(): ?int
    {
        // Implement a method to retrieve the ID of the current locale
        // based on the locale code provided in the request.
        $localeCode = $this->input('translations.*.locale');
        $locale = Locale::where('code', $localeCode)->first();

        return $locale ? $locale->id : null;
    }
}
