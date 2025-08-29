<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Frase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FraseController extends Controller
{
    public function index()
    {
        try {
            return Frase::where('user_id', Auth::id())->get();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao listar frases', 'details' => $e->getMessage()], 500);
        }
    }

    public function last()
    {
        try {
            $frase = Frase::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->first();

            return response()->json($frase);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar última frase',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate(['frase' => 'required|string']);

            $frase = Frase::create([
                'user_id' => Auth::id(),
                'frase' => $request->frase,
            ]);

            return response()->json($frase, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar frase', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $frase = Frase::where('user_id', Auth::id())->findOrFail($id);
            return response()->json($frase);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Frase não encontrada', 'details' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $frase = Frase::where('user_id', Auth::id())->findOrFail($id);
            $frase->update($request->only(['frase']));
            return response()->json($frase);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar frase', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $frase = Frase::where('user_id', Auth::id())->findOrFail($id);
            $frase->delete();
            return response()->json(['message' => 'Frase removida']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover frase', 'details' => $e->getMessage()], 500);
        }
    }
}
