<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Ecole;
use App\Models\Role;
use App\Models\User;
use App\Traits\HttpResp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use HttpResp;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(200, "liste des users", UserResource::collection(User::all()));
    }



    public function getUsersByEcole($ecole_id)
    {

        $users = User::where('ecole_id', $ecole_id)->get();
        return $this->success(200, "liste des users", UserResource::collection($users));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $role = Role::where('libelle', $request->role_id)->first();
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'civilite' => $request->civilite,
            "email" => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'photo' => $this->uploadImage($request),
            "password" => $request->password,
            "ecole_id" => $request->ecole_id,
            "role_id" => $role->id
        ]);
        return $this->success(200, "user crée avec succés", new UserResource($user));
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


    public function uploadImage1(Request $request, $photoKey)
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
        User::where('id', $request->id_system)->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'civilite' => $request->civilite,
            "email" => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'photo' => $this->uploadImage($request)
        ]);

        $user1 = User::where('id', $request->id_system)->first();
        return response()->json([
            'message' => 'modifié avec succès',
            'code' => 200,
            'data' => new UserResource($user1)
        ], Response::HTTP_OK);
    }
    public function modifierUser(Request $request)
    {
        $role = Role::where('libelle', $request->role_id)->first();
        User::where('id', $request->id)->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'civilite' => $request->civilite,
            "email" => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'photo' => $this->uploadImage($request),
            'role_id' => $role->id,
            'password' => Hash::make($request->password)
        ]);

        $user1 = User::where('id', $request->id)->first();
        return response()->json([
            'message' => 'modifié avec succès',
            'code' => 200,
            'data' => new UserResource($user1)
        ], Response::HTTP_OK);
    }


    public function modifierEcole(Request $request)
    {
        Ecole::where('id', $request->id_system)->update([
            'libelle' => $request->nom,
            'date_creation' => $request->date_creation,
            'adresse' => $request->adresse,
            'numero_personnel' => $request->telephone,
            'numero_bureau' => $request->telephone_bureau,
            'logo' => $this->uploadImage($request),
            "email" => $request->email,
            "numero_autorisation" => $this->uploadImage1($request, 'numero_autorisation'),
        ]);
        $user1 = User::where('id', $request->id_user)->first();
        return response()->json([
            'message' => 'modifié avec succès',
            'code' => 200,
            'data' => new UserResource($user1)
        ], Response::HTTP_OK);
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function supprimer($id)
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->delete();
            DB::commit();
            return response()->json(['code' => 200, 'message' => 'Etudiant supprimé avec succes', 'data' => []]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => '404', 'error' => 'Erreur '
                . $e->getMessage(), 'data' => $e->getMessage()]);
        }
    }
    public function checkEmail(Request $request)
    {
        try {
            User::where('email', $request->email)->firstOrFail();
            return response()->json([
                "code" => 200,
                'message' => 'Cet adresse mail est déja utilisé',

            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "code" => 400,
                'message' => 'adresse email valide',
            ]);
        }
    }
}
