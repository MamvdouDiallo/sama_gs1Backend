<?php

namespace App\Http\Resources;

use App\Models\Ecole;
use App\Models\Filiere;
use App\Models\Niveau;
use DateTime;
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
        $date = new DateTime($this->etudiant->date_obtention);

        return [
            'id' => $this->id,
            "civilite" => $this->etudiant->civilite,
            'nom' => $this->etudiant->nom,
            'prenom' => $this->etudiant->prenom,
            "photo" => $this->getImage($this->etudiant->photo),
            "photo_diplome" => $this->getImage($this->etudiant->photo_diplome),
            "departement" => $this->etudiant->departement,
            "ecole" => Ecole::find($this->ecole_id)->libelle,
            "filiere" =>   Filiere::find($this->etudiant->filiere_id)->libelle,
            "niveau" => Niveau::find($this->etudiant->niveau_id)->libelle,
            "matricule" => $this->etudiant->matricule,
            "date_obtention" => $date->format("Y-m-d"),
            "numero_gtin" => $this->etudiant->numero_gtin,
            "etat" => $this->etudiant->etat,
        ];
    }
}
