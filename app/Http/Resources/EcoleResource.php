<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EcoleResource extends JsonResource
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

    public function getPdf($pdfName)
    {
        $path = storage_path('app/public/' . $pdfName);

        if (!file_exists($path)) {
            abort(404);
        }

        $fileContents = file_get_contents($path);
        $base64 = base64_encode($fileContents);
        return "data:application/pdf;base64," . $base64;
    }
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'date_creation' => $this->date_creation,
            'adresse' => $this->adresse,
            'type_ecole' => $this->type_ecole,
            "logo" => $this->getImage($this->logo),
            "numero_bureau" => $this->numero_bureau,
            "numero_autorisation" => $this->getPdf($this->numero_autorisation),
            "email" => $this->email,
            "numero_personnel" => $this->numero_personnel,
        ];
    }
}
