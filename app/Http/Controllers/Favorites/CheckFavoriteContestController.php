<?php

namespace App\Http\Controllers\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContestCollection;
use App\Models\Contest;
use Illuminate\Http\Request;

class CheckFavoriteContestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Contest $contest)
    {
        $isFavorite = $request->user()->favorites()->where('contest_id', $contest->id)->get();
        return new ContestCollection($isFavorite);
    }
}
