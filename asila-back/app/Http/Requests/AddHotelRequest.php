<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\locale;

class AddHotelRequest extends FormRequest
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
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'has_parking'=> 'required|boolean',
            'has_free_breakfast'=> 'required|boolean',
            'has_swimming_pool'=> 'required|boolean',
            'has_spa'=> 'required|boolean',
            'has_fitness_center'=> 'required|boolean',
            'has_free_internet'=> 'required|boolean',
            'has_restaurant'=> 'required|boolean',
            'pets_allowed'=> 'required|boolean',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string',
            'translations.*.name' => 'required|string|unique:hotel_translations,name,NULL,id,locale_id,' . $this->getLocaleId(),
            'translations.*.address' => 'required|string',
            'translations.*.short_description' => 'required|string',
            'translations.*.full_description' => 'required|string',
           ];
    }
    public function getLocaleId(): ?int
    {
        $localeCode = $this->input('translations.*.locale');
        $locale = Locale::where('code', $localeCode)->first();

        return $locale ? $locale->id : null;
    }
}
