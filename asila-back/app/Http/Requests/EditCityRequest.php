<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditCityRequest extends AddCityRequest
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
        $cityId = $this->route('id');

        return array_merge(parent::rules(), [
            'translations.*.name' => 'required|string|unique:city_translations,name,' . $cityId . ',city_id,locale_id,' . $this->getLocaleId(),

        ]);
    }
}
