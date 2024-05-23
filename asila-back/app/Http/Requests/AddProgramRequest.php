<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\locale;

class AddProgramRequest extends FormRequest
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
        // dd("dddddddddddddd",$this->route);
        return [
            'city_id' => 'required|exists:cities,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images' => 'nullable|array',
            'private_car_program' => 'required|boolean',
            'group_program' => 'required|boolean',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string',
            // 'translations.*.title' => 'required|string',
            'translations.*.title' => 'required|string|unique:program_translations,title,NULL,id,locale_id,' . $this->getLocaleId(),
            'translations.*.short_description' => 'nullable|string',
            'translations.*.long_description' => 'nullable|string',
            // 'tags' => 'required|array',
            // 'tags.*.name' => 'required|string',
        ];
    }
    public function getLocaleId(): ?int
    {
        $localeCode = $this->input('translations.*.locale');
        $locale = Locale::where('code', $localeCode)->first();

        return $locale ? $locale->id : null;
    }
}
