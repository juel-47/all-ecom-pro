<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorCreateRequest extends FormRequest
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
            'color_name' => 'required|max:200|unique:colors,color_name',
            'color_code' => 'required|max:200',
            'status' => 'required',
            'price' => 'nullable|numeric',
            'is_default' => 'nullable|integer',

        ];
    }
}
