<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetRequestController extends Controller
{


    public function sendPasswordResetEmail(Request $request)
    {
        if (!$this->validEmail($request->email)) {
            return response()->json([
                'message' => 'Cet email n\'existe pas dans notre base.',
                'code' => 404
            ], Response::HTTP_NOT_FOUND);
        } else {
            $this->sendMail($request->email);
            return response()->json([
                'message' => 'Vérifie ton émail, nous vous avons envoyé un lien pour réinitialiser votre mot de passe',
                'code' => 200
            ], Response::HTTP_OK);
        }
    }
    public function sendMail($email)
    {

        DB::beginTransaction();
        try {
            $token = $this->generateToken($email);
            Mail::to($email)->send(new SendMail($token));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
    public function validEmail($email)
    {
        return User::where('email', $email)->first();
    }
    public function generateToken($email)
    {
        $isOtherToken = DB::table('password_reset_tokens')->where('email', $email)->first();
        if ($isOtherToken) {
            return $isOtherToken->token;
        }
        $token = Str::random(80);
        $this->storeToken($token, $email);
        return $token;
    }
    public function storeToken($token, $email)
    {
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
        ]);
    }
}
