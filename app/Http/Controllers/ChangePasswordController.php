<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ChangePasswordController extends Controller
{

    public function passwordReset(UpdatePasswordRequest $request)
    {
        return $this->updatePasswordRow($request) ? $this->resetPassword($request) :
            $this->tokenNotFound();
    }
    private function tokenNotFound()
    {
        return response()->json([
            'message' => 'Email ou token pas valid',
            'code' => 422
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function updatePasswordRow($request)
    {
        return DB::table('password_reset_tokens')->where([
            'token' => $request->token
        ])->first();
    }
    private function resetPassword($request)
    {
        $email = $this->updatePasswordRow($request)->email;
        $user = User::whereEmail($email)->first();
        $user->update([
            'password' => $request->password
        ]);
        DB::table('password_reset_tokens')->where('token', $request->token)->delete();
        return response()->json([
            'message' => 'Password modifié avec succes',
            'code' => 200
        ], Response::HTTP_CREATED);
    }
}
