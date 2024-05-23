<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Locale;

class AddCarServiceRequest extends FormRequest
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
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string',
            'translations.*.title' => 'required|string|unique:car_service_translations,title,NULL,id,locale_id,' . $this->getLocaleId(),
            'translations.*.description' => 'required|string',
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
