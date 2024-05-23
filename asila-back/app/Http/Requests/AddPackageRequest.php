<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\locale;

class AddPackageRequest extends FormRequest
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
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'can_choose_hotel' => 'required|boolean',
            'price' => 'nullable|numeric|min:0',
            'translations' => 'required|array|min:1',
            'translations.*.locale' => 'required|exists:locales,code',
            'translations.*.title' => 'required|string|unique:package_translations,title,NULL,id,locale_id,' . $this->getLocaleId(),
            'translations.*.short_description' => 'nullable|string',
            'translations.*.long_description' => 'nullable|string',
            // 'tags' => 'required|array',
            // 'tags.*' => 'required|array',
            // 'tags.*.*.name' => 'required|string',
            'package_days' => 'required|array|min:1',
            'package_days.*.day_number' => 'required|integer|min:1',
            'package_days.*.translations.*.content' => 'required|string',
            'package_days.*.activities' => 'array|min:1',
            'package_contains' => 'required|array',
            'package_contains.*.content' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'city_id.required' => 'validation.required.city_id',
            'can_choose_hotel.boolean' => 'validation.boolean.can_choose_hotel',
            'can_choose_hotel.required' => 'validation.required.can_choose_hotel',

            // 'name.min' => 'validation.min.name',
            // 'name.max' => 'validation.max.name',
            // 'surname.required' => 'validation.required.surname',
            // 'surname.string' => 'validation.string.surname',
            // 'surname.min' => 'validation.min.surname',
            // 'surname.max' => 'validation.max.surname',
        ];
    }
    public function getLocaleId(): ?int
    {
        $localeCode = $this->input('translations.*.locale');
        $locale = Locale::where('code', $localeCode)->first();

        return $locale ? $locale->id : null;
    }
}
