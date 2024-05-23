<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditExtraServiceRequest extends AddExtraServiceRequest
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
        $extraServiceId = $this->route('id');

        return array_merge(parent::rules(), [
            'translations.*.title' => 'required|string|unique:extra_service_translations,title,' . $extraServiceId . ',extra_service_id,locale_id,' . $this->getLocaleId(),
        ]);
    }
}
