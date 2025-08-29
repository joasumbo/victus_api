<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    public function index()
    {
        try {
            return Evento::where('user_id', Auth::id())->orderBy('data', 'asc')->get();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao listar eventos', 'details' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'titulo'     => 'required|string|max:255',
                'data'       => 'required|date',
                'descricao'  => 'nullable|string',
            ]);

            $evento = Evento::create([
                'user_id'    => Auth::id(),
                'titulo'     => $request->titulo,
                'data'       => $request->data,
                'descricao'  => $request->descricao,
            ]);

            return response()->json($evento, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar evento', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $evento = Evento::where('user_id', Auth::id())->findOrFail($id);
            return response()->json($evento);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Evento não encontrado', 'details' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $evento = Evento::where('user_id', Auth::id())->findOrFail($id);

            $request->validate([
                'titulo'     => 'sometimes|string|max:255',
                'data'       => 'sometimes|date',
                'descricao'  => 'nullable|string',
            ]);

            $evento->update($request->only(['titulo', 'data', 'descricao']));
            return response()->json($evento);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar evento', 'details' => $e->getMessage()], 500);
        }
    }

    public function upcoming()
    {
        try {
            $eventos = Evento::where('user_id', Auth::id())
                ->orderBy('data', 'asc')
                ->take(2)
                ->get();

            $total = Evento::where('user_id', Auth::id())->count();

            return response()->json([
                'eventos' => $eventos,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar próximos eventos',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $evento = Evento::where('user_id', Auth::id())->findOrFail($id);
            $evento->delete();
            return response()->json(['message' => 'Evento removido com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover evento', 'details' => $e->getMessage()], 500);
        }
    }
}
