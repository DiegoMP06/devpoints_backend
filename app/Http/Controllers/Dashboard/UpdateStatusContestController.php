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
        $data = $request->validate([
            'is_published' => ['required', 'boolean'],
        ], Contest::$validateMessages);

        $contest->is_published = $data['is_published'];
        $contest->save();

        return response()->json([
            'message' => 'Estado de la competencia actualizado exitosamente',
        ]);
    }
}
