<?php

namespace App\Http\Controllers;

use App\Http\Resources\NiveauByEcoleResource;
use App\Http\Resources\NiveauResource;
use App\Models\Niveau;
use App\Models\NiveauEcole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NiveauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'code' => 200, 'message' => 'liste des niveaux',
            'data' => NiveauResource::collection(Niveau::all())
        ]);
    }


    public function niveauByEcole($id)
    {
        $niveauIds = NiveauEcole::where('ecole_id', $id)->with('niveau')->get();
        return response()->json([
            "code" => 200,
            'message' => 'liste des niveaux',
            'data' => NiveauByEcoleResource::collection($niveauIds)
        ]);
    }


    

    /**
     * Store a newly created resource in storage.
     */
    public function Exist($libelle)
    {
        return Niveau::where('libelle', $libelle)->first();
    }
    public function ExistNiveauEcole($params1, $params2)
    {
        return NiveauEcole::where(['ecole_id' => $params1, 'niveau_id' => $params2])->first();
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'libelle' => 'required|min:3',
            'ecole_id' => 'required|exists:ecoles,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Champs invalid',
            ], 422);
        }
        DB::beginTransaction();
        try {
            if (!$this->Exist($request->libelle)) {
                $niveau = Niveau::create($request->all());
                NiveauEcole::create([
                    'ecole_id' => $request->ecole_id,
                    'niveau_id' => $niveau->id,
                ]);
                DB::commit();
                return response()->json([
                    'code' => 200,
                    'message' => 'Crée avec Succés',
                    'data' => $niveau
                ]);
            } else if (!$this->ExistNiveauEcole($request->ecole_id, $this->Exist($request->libelle)->id)) {
                NiveauEcole::create([
                    'ecole_id' => $request->ecole_id,
                    'niveau_id' => $this->Exist($request->libelle)->id,
                ]);
                DB::commit();
                return response()->json([
                    'code' => 200,
                    'message' => 'Crée avec Succés',
                ]);
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => 'Enregistrement existe deja',
                    'data' => null
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
        }
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
