<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->all());
        return $this->success(200, "user crée avec succés",  $user);
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
        $image_64 = $request->photo;
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '.' . $extension;
        Storage::disk('public')->put($imageName, base64_decode($image));
        return $imageName;
    }
    public function modifier(Request $request)
    {
        User::where('id', $request->id_system)->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'civilite' => $request->civilite,
            'telephone' => $request->telephone,
            'telephone_bureau' => $request->telephone_bureau,
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
