<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'image'=>'image|max:3000',
            'name' => 'required|max:200',
            'category'=>'required',
            // 'brand'=>'required',
            'price'=>'required',
            'qty'=>'required',
            'short_description'=>'required|max:600',
            'long_description'=>'required',
            'status'=>'required',
            'seo_title'=>'nullable|max:200',
            'seo_description'=>'nullable|max:250',

            //color image
            'proColor'=>'array',
            'color_image'=>'array',
            'proColor.*'=>'nullable|integer|exists:colors,id',
            'color_image.*'=>'nullable|image|mimes:jpeg,png,jpg,webp|max:5048'
        ];
    }
}