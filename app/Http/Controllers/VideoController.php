<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Library;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        try {
            $videos = Video::with('user')->get(); 
            return response()->json($videos);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch videos',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'video' => 'required|file|mimes:mp4,avi,mov,mkv|max:512000',
                'library_id' => 'required|exists:libraries,id', 
            ]);

            // Verificar se o usuário tem acesso à biblioteca
            $library = Library::where('user_id', Auth::id())->findOrFail($validated['library_id']);

            $path = $request->file('video')->store('videos', 'public');

            $video = Video::create([
                'user_id' => Auth::id(),
                'library_id' => $validated['library_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'video_path' => $path,
            ]);

            return response()->json($video, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Library not found',
                'message' => 'The specified library does not exist or you do not have access.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to upload video',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $video = Video::findOrFail($id);
            $video->increment('views'); 
            return response()->json($video);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Video not found',
                'message' => 'The requested video does not exist.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve video',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $video = Video::findOrFail($id);
            if ($video->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            Storage::delete('public/' . $video->video_path);
            $video->delete();
            return response()->json(['message' => 'Video deleted']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Video not found',
                'message' => 'The requested video does not exist.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete video',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
