<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerAddressRequest extends FormRequest
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
            'name'    => 'required|string|max:200',
            'email'   => 'required|string|email|max:200',
            'phone'   => 'required|string|max:200',
            'country' => 'required|string|max:200',
            'state'   => 'required|string|max:200',
            'city'    => 'required|string|max:200',
            'zip'     => 'required|string|max:200',
            'address' => 'required|string',
        ];
    }
}
