<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContestCollection;
use App\Models\FavoriteContest;
use Illuminate\Http\Request;

class FavoriteContestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $contests = $request->user()->favorites()->with('user')->get();

        return new ContestCollection($contests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'contest_id' => ['required', 'exists:contests,id'],
        ]);

        $isFavorite = $request->user()->favorites()->where('contest_id', $validate['contest_id'])->exists();

        if ($isFavorite) {
            return response()->json([
                "message" => "La competencia ya está en tus favoritos"
            ], 401);
        }

        $request->user()->favorites()->attach($validate['contest_id']);

        return response()->json([
            "message" => "La competencia se ha añadido correctamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FavoriteContest $favoriteContest)
    {
        $favoriteContest->delete();

        return response()->json([
            "message" => "La competencia se ha eliminado correctamente"
        ]);
    }
}
