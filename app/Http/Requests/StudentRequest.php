<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            "ecole" => "required|string|min:3",
            "departement" => "required|string|min:4",
            "niveau_etude" => "required|string|min:2",
            "num_gtin" => "required|string",
            "id_system" => "required|string",
            "civilite" => "required|string",

        ];
    }
}
