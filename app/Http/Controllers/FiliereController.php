<?php

namespace App\Http\Controllers;

use App\Http\Resources\FiliereByEcoleResource;
use App\Http\Resources\FiliereResource;
use App\Models\Filiere;
use App\Models\FiliereEcole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FiliereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'code' => 200, 'message' => 'liste des niveaux',
            'data' => FiliereResource::collection(Filiere::all())
        ]);
    }

    public function filiereByEcole($id)
    {
        $niveauIds = FiliereEcole::where('ecole_id', $id)->with('filiere')->get();
        return response()->json([
            "code" => 200,
            'message' => 'liste des filieres',
            'data' => FiliereByEcoleResource::collection($niveauIds)
        ]);
    }




    public function Exist($libelle)
    {
        return Filiere::where('libelle', $libelle)->first();
    }
    public function ExistNiveauEcole($params1, $params2)
    {
        return FiliereEcole::where(['ecole_id' => $params1, 'filiere_id' => $params2])->first();
    }
    /**
     * Store a newly created resource in storage.
     */
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
                $filiere = Filiere::create($request->all());

                FiliereEcole::create([
                    'ecole_id' => $request->ecole_id,
                    'filiere_id' => $filiere->id,
                ]);

                DB::commit();

                return response()->json([
                    'code' => 200,
                    'message' => 'Crée avec Succés',
                    'data' => $filiere
                ]);
            } else if (!$this->ExistNiveauEcole($request->ecole_id, $this->Exist($request->libelle)->id)) {
                FiliereEcole::create([
                    'ecole_id' => $request->ecole_id,
                    'filiere_id' => $this->Exist($request->libelle)->id,
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
