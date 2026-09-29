<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggleFavorite(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first to bookmark this property.'
            ], 401);
        }

        $userId = auth()->id();
        $projectId = $request->input('project_id');

        // Check if already favorited
        $existing = Favorite::where('user_id', $userId)
            ->where('content_id', $projectId)
            ->first();

        if ($existing) {
            // If already exists, remove it
            $existing->delete();
            return response()->json([
                'success' => true,
                'status'  => 'removed',
                'message' => 'Removed from favorites.'
            ]);
        } else {
            // Otherwise, add to favorites
            Favorite::create([
                'user_id'    => $userId,
                'content_id' => $projectId
            ]);
            return response()->json([
                'success' => true,
                'status'  => 'added',
                'message' => 'Added to favorites.'
            ]);
        }
    }
}
