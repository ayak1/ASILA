<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\locale;

class AddExtraServiceRequest extends FormRequest
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
            'translations' => 'required|array|min:1',
            'translations.*.locale' => 'required|string|exists:locales,code',
            'translations.*.title' => 'required|string|unique:extra_service_translations,title,NULL,id,locale_id,' . $this->getLocaleId(),
            'translations.*.main_description' => 'required|string',
            // 'tags' => 'nullable|array|min:1',
            // 'tags.*.locale' => 'required|string|exists:locales,code',
            // 'tags.*.name' => 'required|string',
            'sections' => 'nullable|array|min:1',
            'sections.*.translations' => 'required|array|min:1',
            'sections.*.translations.*.locale' => 'required|string|exists:locales,code',
            'sections.*.translations.*.section_title' => 'nullable|string',
            'sections.*.translations.*.section_description' => 'nullable|string',
            'sections.*.translations.*.list_of_adv' => 'nullable|array',
            'sections.*.translations.*.list_of_adv.*' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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
