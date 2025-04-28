<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContestCollection;
use App\Models\Contest;
use Illuminate\Http\Request;

class ContestSummaryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Contest $contest)
    {
        $contest->load(['user', 'evaluators', 'teams', 'exercises']);

        $isSaved = null;

        if ($request->user()) {
            $isSaved = $contest->saves()->where('user_id', $request->user()->id)->first();
        }

        $contest->setAttribute('is_saved', $isSaved);

        return new ContestCollection([$contest]);
    }
}
