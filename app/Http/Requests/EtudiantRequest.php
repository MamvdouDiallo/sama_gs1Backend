<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EtudiantRequest extends FormRequest
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
            "departement" => "required|string|min:1",
            "ecole_id" => "required|integer|exists:ecoles,id",
            "photo" => "required|string",
            "numero_gtin" => "required|string|min:8|unique:etudiants",
            "date_obtention" => "required",
            "matricule" => "required|string|min:2",
            "niveau" => "required",
            "filiere" => "required",
            "photo_diplome" => "required|string"
        ];
    }
}
