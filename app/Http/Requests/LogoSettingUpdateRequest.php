<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogoSettingUpdateRequest extends FormRequest
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
            // 'logo' => 'mimes:jpg,jpeg,png,webp,svg,gif,ico,bmp,tiff|max:3072',
            // 'favicon' => 'mimes:jpg,jpeg,png,webp,svg,gif,ico,bmp,tiff|max:3072'
            'logo' => 'nullable|max:3072',
            'favicon' => 'nullable|max:3072',
        ];
    }
}
