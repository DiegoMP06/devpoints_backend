<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContestCollection;
use App\Models\Contest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $contests = Contest::when($request->get('query'), function ($query, $querySearch) {
            $query->where('name', 'like', "%{$querySearch}%");
        })
            ->where('is_published', true)
            ->with('user')
            ->orderBy('created_at', 'ASC')
            ->paginate(20);

        return new ContestCollection($contests);
    }
}
