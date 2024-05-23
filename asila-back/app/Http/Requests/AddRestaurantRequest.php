<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\locale;

class AddRestaurantRequest extends FormRequest
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
            'opening_hours' => 'nullable|string',
            'closing_hours' => 'nullable|string',
            'translations' => 'required|array',
            'images' => 'nullable|array',
            'images.*.' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'translations.*.locale' => 'required|string',
            'translations.*.name' => 'required|string|unique:restaurant_translations,name,NULL,id,locale_id,' . $this->getLocaleId(),
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
