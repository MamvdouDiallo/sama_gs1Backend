<?php

namespace App\Http\Controllers;

use App\Http\Requests\EtudiantRequest;
use App\Http\Resources\EtudiantResource;
use App\Models\Departement;
use App\Models\Etudiant;
use App\Models\EtudiantEcole;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Traits\HttpResp;
use Illuminate\Http\Response;
use Error;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EtudiantController extends Controller
{

    use HttpResp;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // if ($request->user()->cannot('viewAny', Etudiant::class)) {
        //     return response()->json([
        //         "message" => "Tu n'est pas autorisé a effectué cette action",
        //         "code"=>404
        //     ]);
        // }

        return response()->json([
            'code' => 200,
            'message' => 'Etudiant',
            'data' => Etudiant::all(),
        ]);
    }


    public function elevesByEcole($id)
    {
        $eleves = EtudiantEcole::where('ecole_id', $id)->with('etudiant')->get();
        return response()->json([
            "code" => 200,
            'message' => 'liste des filieres',
            'data' => EtudiantResource::collection($eleves)
        ]);
    }


    public function elevesEcoleByGtin(Request $request)
    {

        $id = $request->id_ecole;
        $gtin = $request->numero_gtin;
        try {
            $eleve = EtudiantEcole::where('ecole_id', $id)
                ->whereHas('etudiant', function ($query) use ($gtin) {
                    $query->where('numero_gtin', $gtin);
                })
                ->with('etudiant')
                ->firstOrFail();
            return response()->json([
                "code" => 200,
                'message' => 'Élève trouvé',
                'data' => new EtudiantResource($eleve)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "code" => 400,
                'message' => 'Aucun élève trouvé avec ce numéro GTIN',
            ]);
        }
    }



    public function IsExistGtin(Request $request)
    {
        try {
            $gtin = Etudiant::where('numero_gtin', $request->numero_gtin)->firstOrFail();
            return response()->json([
                "code" => 200,
                "message" => "Ce numero est déjà attribué a un étudiant",
                "data" => $gtin
            ]);
        } catch (ModelNotFoundException $th) {
            return response()->json([
                "code" => 400,
                'message' => 'Aucun élève trouvé avec ce numéro GTIN',
            ]);
        }
    }


    public function uploadImage(Request $request, $photoKey)
    {
        if ($request->has($photoKey) && !empty($request->$photoKey)) {
            $image_64 = $request->$photoKey;
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




    public function modifier(Request $request)
    {

        $filiere = Filiere::where('libelle', $request->filiere)->first();
        $niveau = Niveau::where('libelle', $request->niveau)->first();

        Etudiant::where('id', $request->id)->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'civilite' => $request->civilite,
            'numero_gtin' => $request->numero_gtin,
            'niveau_id' => $niveau->id,
            'matricule' => $request->matricule,
            'date_obtention' => $request->date_obtention,
            'filiere_id' => $filiere->id,
            'photo' => $this->uploadImage($request, 'photo'),
            'photo_diplome' => $this->uploadImage($request, 'photo_diplome'),
            'date_obtention' => $request->date_obtention
        ]);
        $user1 = EtudiantEcole::where('etudiant_id', $request->id)->with('etudiant')->first();
        return response()->json([
            'message' => 'modifié avec succès',
            'code' => 200,
            'data' => new EtudiantResource($user1)
        ], Response::HTTP_OK);
    }











    public function detDepartment(Request $request)
    {
        $query = $request->input('query');
        $suggestions = Departement::where('libelle', 'like', '%' . $query . '%')->pluck('libelle');
        return response()->json($suggestions);
    }



    public function existDepartement($libelle)
    {
        return Departement::where('libelle', $libelle)->first();
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(EtudiantRequest $request)
    {
        DB::beginTransaction();
        try {
            $image_64 = $request->photo;
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '.' . $extension;
            Storage::disk('public')->put($imageName, base64_decode($image));


            $image64 = $request->photo_diplome;
            $extension1 = explode('/', explode(':', substr($image64, 0, strpos($image64, ';')))[1])[1];
            $replace1 = substr($image64, 0, strpos($image64, ',') + 1);
            $image1 = str_replace($replace1, '', $image64);
            $image1 = str_replace(' ', '+', $image1);
            $imageName1 = 'diplome' . time() . '.' . $extension1;
            Storage::disk('public')->put($imageName1, base64_decode($image1));

            if (!$this->existDepartement($request->departement)) {
                Departement::create([
                    'libelle' => ucfirst($request->departement),
                ]);
            }
            $student =  Etudiant::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'civilite' => $request->civilite,
                'departement' => $request->departement,
                'filiere_id' => $request->filiere_id,
                'numero_gtin' => $request->numero_gtin,
                'niveau_id' => $request->niveau_id,
                'matricule' => $request->matricule,
                'date_obtention' => $request->date_obtention,
                //'id_system' => $request->id_system,
                'photo' => $imageName,
                'photo_diplome' => $imageName1
            ]);
            EtudiantEcole::create([
                'ecole_id' => $request->ecole_id,
                'etudiant_id' => $student->id
            ]);
            DB::commit();
            return $this->success(200, 'created succesfully', $student);
        } catch (\Exception $th) {
            DB::rollback();
            throw new Error($th->getMessage());
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
        dd($id);
    }

    public function supprimer($id)
    {

        try {
            DB::beginTransaction();
            $etudiant = Etudiant::findOrFail($id);
            $etudiant->ecoles()->detach();
            $etudiant->delete();
            DB::commit();
            return response()->json(['code' => 200, 'message' => 'Etudiant supprimé avec succes', 'data' => []]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => '404', 'error' => 'Erreur '
                . $e->getMessage(), 'data' => $e->getMessage()]);
        }
    }
}
