<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Library;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    public function index()
    {
        try {
            $libraries = Library::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->with('videos')
                ->get();
            return response()->json($libraries);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch libraries',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'thumb' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $thumbPath = null;
            if ($request->hasFile('thumb')) {
                $thumbPath = $request->file('thumb')->store('libraries', 'public');
            }

            $library = Library::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'thumb' => $thumbPath,
            ]);

            return response()->json($library, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create library',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $library = Library::where('user_id', Auth::id())->findOrFail($id);
            $library->load('videos'); 
            return response()->json($library);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Library not found',
                'message' => 'The requested library does not exist or you do not have access.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve library',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $library = Library::where('user_id', Auth::id())->findOrFail($id);

            // apaga o thumb do storage, caso tenha
            if ($library->thumb && Storage::disk('public')->exists($library->thumb)) {
                Storage::disk('public')->delete($library->thumb);
            }

            // Apaga vídeos relacionados
            foreach ($library->videos as $video) {
                if ($video->path && Storage::disk('public')->exists($video->path)) {
                    Storage::disk('public')->delete($video->path);
                }
                $video->delete();
            }

            $library->delete();

            return response()->json([
                'success' => true,
                'message' => 'Biblioteca e vídeos removidos com sucesso.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Library not found',
                'message' => 'A biblioteca não existe ou não pertence a este usuário.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete library',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
