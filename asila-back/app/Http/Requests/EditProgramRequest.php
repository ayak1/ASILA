<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\locale;

class EditProgramRequest extends AddProgramRequest
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

        $programId = $this->route('id');

            return array_merge(parent::rules(), [
                'translations.*.title' => 'required|string|unique:program_translations,title,' . $programId . ',program_id,locale_id,' . $this->getLocaleId(),
            ]);
    }


}
