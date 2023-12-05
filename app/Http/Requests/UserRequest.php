<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            "nom" => "required|string|min:2",
            "prenom" => "required|string|min:2",
            "email" => "required|email|unique:users",
            "photo" => "string",
            "telephone" => 'required|unique:users|regex:/^7[5678]\d{7}$/',
            "role_id" => 'required|integer|exists:roles,id',
            "user_id" => 'required|integer|exists:users,id'
        ];
    }
}
