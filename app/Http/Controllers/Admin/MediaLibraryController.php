<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaLibraryController extends Controller
{
    use HandlesImageUpload;

    public function index()
    {
        $files = collect(Storage::disk('public')->allFiles('uploads'))
            ->filter(fn($f) => preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f))
            ->map(fn($f) => [
                'path' => $f,
                'url'  => asset('storage/' . $f),
                'name' => basename($f),
                'size' => Storage::disk('public')->size($f),
                'time' => Storage::disk('public')->lastModified($f),
            ])
            ->sortByDesc('time')
            ->values();

        return response()->json($files);
    }

    public function upload(Request $request)
    {
     

        $dir = 'uploads/' . ($request->input('module', 'general'));

        if ($request->filled('cropped')) {
            $path = $this->storeAsWebp($request->input('cropped'), $dir);
        } elseif ($request->hasFile('file')) {
            $path = $this->storeAsWebp($request->file('file'), $dir);
        } else {
            return response()->json(['error' => 'No file provided'], 422);
        }

        return response()->json([
            'path' => $path,
            'url'  => asset('storage/' . $path),
            'name' => basename($path),
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate(['path' => 'required|string']);
        $path = $request->input('path');

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}