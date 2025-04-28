<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use Illuminate\Http\Request;

class UpdateStatusContestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Contest $contest)
    {
        $contest->is_published = !$contest->is_published;
        $contest->save();

        return response()->json([
            'message' => 'Estado de la competencia actualizado exitosamente',
        ]);
    }
}
