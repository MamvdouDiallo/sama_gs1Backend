<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcoleRequest;
use App\Models\Ecole;
use App\Traits\HttpResp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EcoleController extends Controller
{

    use HttpResp;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        // if ($request->user()->cannot('viewAny', Ecole::class)) {
        //     return response()->json([
        //         "message" => "Tu n'est pas autorisé a effectué cette action",
        //         "code"=>404
        //     ]);
        // }
        //$this->authorize('viewAny', Ecole::class);
        $ecoles = Ecole::all();
        return $this->success(200, "Liste des ecoles", $ecoles);
    }



    public function uploadImage(Request $request)
    {
        if ($request->has('photo') && !empty($request->photo)) {
            $image_64 = $request->photo;
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '.' . $extension;
            Storage::disk('public')->put($imageName, base64_decode($image));
            return $imageName;
        } else {
            return $imageName = "";
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(EcoleRequest $request)
    {
        $ecole = Ecole::create([
            "libelle" => $request->libelle,
            "date_creation" => $request->date_creation,
            "type_ecole" => $request->type_ecole,
            "logo" => $request->photo,
            "numero_bureau" => $request->numero_bureau,
            "numero_autorisation" => $request->numero_autorisation
        ]);
        return $this->success(200, "Ecole crée avec succés", $ecole);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
