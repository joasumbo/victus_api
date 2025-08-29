<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => 'Link de recuperação enviado com sucesso!'
                ], 200);
            }

            return response()->json([
                'error' => 'Não foi possível enviar o link. Verifique o email e tente novamente.'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Erro ao enviar link de recuperação: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro interno ao enviar o link de recuperação.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
