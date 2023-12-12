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
            'telephone' => ['required', 'unique:users', 'regex:/^(?:\+221)?(?:77|78|70|75)\d{7}$/'],
            'telephone_bureau' => ['required', 'unique:users', 'regex:/^(?:\+221)?(?:77|78|70|75|33)\d{7}$/'],
            "adresse" => 'required|string',
            "civilite" => 'required|string|min:2',
            "password" => 'required|string',
            "role_id" => 'required|integer|exists:roles,id',
            "ecole_id" => 'required|integer|exists:ecoles,id'
        ];
    }
}
