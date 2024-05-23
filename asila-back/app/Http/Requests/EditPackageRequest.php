<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditPackageRequest extends AddPackageRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $packageId = $this->route('id');

        return array_merge(parent::rules(), [
            'translations.*.title' => 'required|string|unique:package_translations,title,' . $packageId . ',program_id,locale_id,' . $this->getLocaleId(),
        ]);
    }
}
