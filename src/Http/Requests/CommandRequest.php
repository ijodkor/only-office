<?php

namespace OnlyOffice\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommandRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'c' => 'required|string|in:forcesave,info,meta,version',
            'key' => 'exclude_if:c,version|required|string',
            'meta' => 'exclude_unless:c,meta|required'
        ];
    }
}
