<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            'image' => 'required|image|max:5048',
            'name' => 'required|max:200',
            'category' => 'required',
            // 'brand'=>'required',
            'purchase_price' => 'nullable',
            'price' => 'required',
            'qty' => 'required',
            'sku' => 'required',
            'short_description' => 'required|max:600',
            'long_description' => 'required',
            'status' => 'required',
            'meta_title' => 'nullable|max:200',
            'meta_description' => 'nullable|max:250',
            //color_image
            'proColor.*'=>'nullable|integer|exists:colors,id',
            'color_image.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
        ];
    }
}