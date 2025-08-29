<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'message' => 'Palavra-passe redefinida com sucesso!'
                ], 200);
            }

            return response()->json([
                'error' => 'Token inválido ou email incorreto.'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Erro ao redefinir palavra-passe: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro interno ao redefinir palavra-passe.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
