<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\locale;

class AddAreaRequest extends FormRequest
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
            'is_popular' => 'required|boolean',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string',
            'translations.*.name' => 'required|string|unique:area_translations,name,NULL,id,locale_id,' . $this->getLocaleId(),
            'translations.*.description' => 'required|string',
        ];
    }

    public function getLocaleId(): ?int
    {
        $localeCode = $this->input('translations.*.locale');
        $locale = Locale::where('code', $localeCode)->first();

        return $locale ? $locale->id : null;
    }
}
