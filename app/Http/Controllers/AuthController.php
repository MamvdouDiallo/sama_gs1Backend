<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Traits\HttpResp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResp;
    public function login(Request $request)
    {

        if (!Auth::attempt($request->only("email", "password"))) {
            return response([
                "message" => "Email ou mot de passe incorrect"
            ],);
        }
        $user = Auth::user();
        $token = $user->createToken("token")->plainTextToken;
        $data = [
            "user" =>  new UserResource($user),
            "token" => $token
        ];
        return $this->success(200, "", $data);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json([
            'message' => 'Déconnecté avec succés'
        ]);
    }
}
