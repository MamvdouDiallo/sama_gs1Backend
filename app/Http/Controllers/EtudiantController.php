<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLog;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EtudiantController extends Controller
{

    use HttpResp;
    protected $activityLogService;

    public function __construct(ActivityLog $activityLog)
    {
        $this->activityLogService = $activityLog;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->user()->cannot('viewAny', Etudiant::class)) {
            return response()->json([
                "message" => "Tu n'est pas autorisé a effectué cette action",
                "code" => 404
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Etudiant',
            'data' => Etudiant::all(),
        ]);
    }


    public function elevesByEcole(Request $request, $id)
    {
        // if ($request->user()->cannot('viewEtudiantByEcole', Etudiant::class)) {
        //     return response()->json([
        //         "message" => "Tu n'es pas autorisé à voir cette liste",
        //         "code" => 404
        //     ]);
        // }
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
                    $query->where(['numero_gtin' => $gtin, 'etat' => 'valide']);
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

    public function existDepartement($libelle)
    {
        return Departement::where('libelle', $libelle)->first();
    }
    public function existFiliere($libelle)
    {
        return Filiere::where('libelle', $libelle)->first();
    }

    public function existNiveau($libelle)
    {
        return Niveau::where('libelle', $libelle)->first();
    }

    // public function IsExistGtin(Request $request)
    // {
    //     try {
    //         $gtin = Etudiant::where('numero_gtin', $request->numero_gtin)->firstOrFail();
    //         return response()->json([
    //             "code" => 200,
    //             "message" => "Ce numero est déjà attribué a un étudiant",
    //             "data" => $gtin
    //         ]);
    //     } catch (ModelNotFoundException $th) {
    //         return response()->json([
    //             "code" => 400,
    //             'message' => 'Aucun élève trouvé avec ce numéro GTIN',
    //         ]);
    //     }
    // }


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

        if ($request->user()->cannot('update', Etudiant::class)) {
            return response()->json([
                "message" => "Tu n'est pas autorisé à modifier  quoi que ce soit",
                "code" => 404
            ]);
        }

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

        if (!$this->existDepartement(ucfirst($request->departement))) {
            Departement::create([
                'libelle' => ucfirst($request->departement),
            ]);
        }

        if (!$this->existFiliere(ucfirst($request->filiere))) {
            Filiere::create([
                'libelle' => ucfirst($request->filiere),
            ]);
        }

        if (!$this->existNiveau(ucfirst($request->niveau))) {
            Niveau::create([
                'libelle' => ucfirst($request->niveau),
            ]);
        }

        $filiere = Filiere::where('libelle', $request->filiere)->First();
        $niveau = Niveau::where('libelle', $request->niveau)->First();

        Etudiant::where('id', $request->id)->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'civilite' => $request->civilite,
            'numero_gtin' => $request->numero_gtin,
            'niveau_id' => $niveau->id,
            'matricule' => $request->matricule,
            'date_obtention' => $request->date_obtention,
            'filiere_id' => $filiere->id,
            'departement' => $request->departement,
            'photo' => $imageName,
            'photo_diplome' => $imageName1,
            'date_obtention' => $request->date_obtention
        ]);
        $user1 = EtudiantEcole::where('etudiant_id', $request->id)->with('etudiant')->first();
        return response()->json([
            'message' => 'étudiant modifié avec succès',
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

    public function detFiliere(Request $request)
    {
        $query = $request->input('query');
        $suggestions = Filiere::where('libelle', 'like', '%' . $query . '%')->pluck('libelle');
        return response()->json($suggestions);
    }


    public function detNiveau(Request $request)
    {
        $query = $request->input('query');
        $suggestions = Niveau::where('libelle', 'like', '%' . $query . '%')->pluck('libelle');
        return response()->json($suggestions);
    }

    public function valider(Request $request)
    {


        Etudiant::where('id', $request->id)->update([
            'etat' => "valide",
        ]);
        $user1 = EtudiantEcole::where('etudiant_id', $request->id)->with('etudiant')->first();
        return response()->json([
            'message' => 'étudiant validé avec succès',
            'code' => 200,
            'data' => new EtudiantResource($user1)
        ], Response::HTTP_OK);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(EtudiantRequest $request)
    {
        if ($request->user()->cannot('create', Etudiant::class)) {
            return response()->json([
                "message" => "Tu n'es pas autorisé à ajouter ",
                "code" => 404
            ]);
        }

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

            if (!$this->existDepartement(ucfirst($request->departement))) {
                Departement::create([
                    'libelle' => ucfirst($request->departement),
                ]);
            }

            if (!$this->existFiliere(ucfirst($request->filiere))) {
                Filiere::create([
                    'libelle' => ucfirst($request->filiere),
                ]);
            }

            if (!$this->existNiveau(ucfirst($request->niveau))) {
                Niveau::create([
                    'libelle' => ucfirst($request->niveau),
                ]);
            }
            $etat = ($request->role_user != "Admin") ? "enAttente" : "valide";
            $filiere = Filiere::where('libelle', $request->filiere)->First();
            $niveau = Niveau::where('libelle', $request->niveau)->First();
            $student =  Etudiant::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'civilite' => $request->civilite,
                'date_de_naissance' => $request->date_de_naissance,
                'lieu_de_naissance' => $request->lieu_de_naissance,
                'departement' => $request->departement,
                'filiere_id' => $filiere->id,
                'numero_gtin' => $request->numero_gtin,
                'niveau_id' => $niveau->id,
                'matricule' => $request->matricule,
                'date_obtention' => $request->date_obtention,
                'photo' => $imageName,
                'etat' => $etat,
                'photo_diplome' => $imageName1
            ]);
            EtudiantEcole::create([
                'ecole_id' => $request->ecole_id,
                'etudiant_id' => $student->id
            ]);
            DB::commit();
            return $this->success(200, 'Etudiant créé avec succés', $student);
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

    public function supprimer(Request $request, $id)
    {
        if ($request->user()->cannot('delete', Etudiant::class)) {
            return response()->json([
                "message" => "Tu n'est pas autorisé à supprimer",
                "code" => 404
            ]);
        }
        try {
            DB::beginTransaction();
            $etudiant = Etudiant::findOrFail($id);
            $etudiant->ecoles()->detach();
            $etudiant->delete();
            $this->activityLogService->createLog("l'utilisateur s'est connecté", Auth::user());
            DB::commit();
            return response()->json(['code' => 200, 'message' => 'Etudiant supprimé avec succes', 'data' => []]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => '404', 'error' => 'Erreur '
                . $e->getMessage(), 'data' => $e->getMessage()]);
        }
    }
}
