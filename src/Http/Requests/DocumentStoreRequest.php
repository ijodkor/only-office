<?php

namespace OnlyOffice\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class DocumentStoreRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array {
        return [
            'shared_key' => 'required|string',
            'url' => 'nullable|string',
            'date' => '',
            'status' => 'required|numeric',
            Str::lower('notModified') => '',
            Str::lower('changesUrl') => '',
            'history' => '',
            'key' => '',
            'actions' => '',
            Str::lower('forceSaveType') => ''
        ];
    }
}
