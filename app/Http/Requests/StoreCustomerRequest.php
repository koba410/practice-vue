<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'kana' => 'required|max:50|regex:/^[ァ-ヶー]+$/u',
            'tel' => 'required|max:20|unique:customers,tel',
            'email' => 'required|max:255|email|unique:customers,email',
            'postcode' => 'required|max:7',
            'address' => 'required|max:100',
            'birthday' => 'nullable|date',
            'gender' => 'required',
            'memo' => 'nullable|max:1000',
        ];
    }
}
