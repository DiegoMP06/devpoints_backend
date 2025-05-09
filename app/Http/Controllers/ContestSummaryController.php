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

        return new ContestCollection([$contest]);
    }
}
