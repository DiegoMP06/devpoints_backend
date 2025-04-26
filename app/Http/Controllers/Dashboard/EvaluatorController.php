<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\Contest;
use App\Models\ContestUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EvaluatorController extends Controller
{
    public function index(Contest $contest)
    {
        return new UserCollection($contest->evaluators()->get(['users.id', 'users.name', 'users.email']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Contest $contest)
    {
        $valdated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $isEvaluator = $contest->evaluators()->where('user_id', $valdated['user_id'])->exists();

        if ($isEvaluator) {
            return response()->json([
                "message" => "El usuario ya es evaluador"
            ], 401);
        }

        if (((int) $request->user()->id) === ((int) $valdated['user_id'])) {
            return response()->json([
                "message" => "No puedes añadirte a ti mismo"
            ], 401);
        }

        $contest->evaluators()->attach($valdated['user_id']);

        return response()->json([
            "message" => "El usuario se ha añadido correctamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest, ContestUser $contestUser)
    {
        $contestUser->delete();

        return response()->json([
            "message" => "El usuario se ha eliminado correctamente"
        ]);
    }
}
