<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Frase;
use App\Models\Peso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesoController extends Controller
{
    public function index()
    {
        try {
            $pesos = Peso::where('user_id', Auth::id())
                ->orderBy('data', 'desc')
                ->take(2)
                ->get();

            return response()->json($pesos);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao listar pesos',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function show()
    {
        try {
            $pesos = Peso::where('user_id', Auth::id())
                ->orderBy('data', 'desc')
                ->get();

            return response()->json($pesos);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao listar pesos',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'data' => 'required|date',
                'kg'   => 'required|numeric|min:0',
            ]);

            $peso = Peso::create([
                'user_id' => Auth::id(),
                'data'    => $request->data,
                'kg'      => $request->kg,
            ]);

            return response()->json($peso, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao registrar peso', 'details' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $peso = Peso::where('user_id', Auth::id())->findOrFail($id);

            $request->validate([
                'data' => 'sometimes|date',
                'kg'   => 'sometimes|numeric|min:0',
            ]);

            $peso->update($request->only(['data', 'kg']));
            return response()->json($peso);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar peso', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $peso = Peso::where('user_id', Auth::id())->findOrFail($id);
            $peso->delete();
            return response()->json(['message' => 'Peso removido com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover peso', 'details' => $e->getMessage()], 500);
        }
    }
}
