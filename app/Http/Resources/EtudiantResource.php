<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EtudiantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function getImage($imageName)
    {
        $path = storage_path('app/public/' . $imageName);
        if (!file_exists($path)) {
            abort(404);
        }
        $fileContents = file_get_contents($path);
        $base64 = base64_encode($fileContents);
        return "data:image/png;base64," . $base64;
    }
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "civilite" => $this->etudiant->civilite,
            'nom' => $this->etudiant->nom,
            'prenom' => $this->etudiant->prenom,
            "photo" => $this->getImage($this->etudiant->photo),
            "departement" => $this->etudiant->departement,
            "ecole_id" => $this->ecole_id,
            "filiere_id" => $this->etudiant->filiere_id,
            "niveau_id" => $this->etudiant->niveau_id,
            "numero_gtin" => $this->etudiant->numero_gtin
        ];
    }
}
