<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        try {
            $banners = Banner::where('user_id', Auth::id())->get();
            return response()->json($banners);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao listar banners', 'details' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'imagem' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'link' => 'nullable'
            ]);

            $path = $request->file('imagem')->store('banners', 'public');

            $banner = Banner::create([
                'user_id' => Auth::id(),
                'titulo' => $request->titulo,
                'imagem' => $path,
                'link' => $request->link
            ]);

            return response()->json($banner, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar banner', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $banner = Banner::where('user_id', Auth::id())->findOrFail($id);
            return response()->json($banner);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Banner não encontrado', 'details' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $banner = Banner::where('user_id', Auth::id())->findOrFail($id);

            $banner->titulo = $request->titulo ?? $banner->titulo;

            if ($request->hasFile('imagem')) {
                // remove antiga
                if ($banner->imagem && Storage::disk('public')->exists($banner->imagem)) {
                    Storage::disk('public')->delete($banner->imagem);
                }
                $banner->imagem = $request->file('imagem')->store('banners', 'public');
            }

            $banner->save();

            return response()->json($banner);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar banner', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $banner = Banner::where('user_id', Auth::id())->findOrFail($id);

            if ($banner->imagem && Storage::disk('public')->exists($banner->imagem)) {
                Storage::disk('public')->delete($banner->imagem);
            }

            $banner->delete();
            return response()->json(['message' => 'Banner removido']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover banner', 'details' => $e->getMessage()], 500);
        }
    }
}