<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "civilite" => $this->civilite,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            "email" => $this->email,
            "telephone" => $this->telephone,
            "photo" => $this->getImage($this->photo),
            // "telephone_bureau" => $this->telephone_bureau,
            "adresse" => $this->adresse,
            "role" => $this->role->libelle,
            "ecole" => new EcoleResource($this->ecole),
            "ecole_id" => $this->ecole->id
        ];
    }
}
