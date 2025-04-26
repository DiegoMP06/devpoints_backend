<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssessmentCollection;
use App\Models\Assessment;
use App\Models\Contest;
use App\Models\Team;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contest $contest, Team $team)
    {
        return new AssessmentCollection($team->assessments()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Contest $contest, Team $team)
    {
        $validated = $request->validate([
            'exercise_id' => ['required', 'exists:exercises,id'],
        ]);

        $isEvaluated = $team->assessments()->withoutTrashed()->where('exercise_id', $validated['exercise_id'])->exists();

        if ($isEvaluated) {
            return response()->json([
                "message" => "El ejercicio ya ha sido evaluado"
            ], 401);
        }

        $team->assessments()->create([
            'exercise_id' => $validated['exercise_id'],
            'created_by' => $request->user()->id
        ]);

        return response()->json([
            "message" => "El ejercicio se ha evaluado correctamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Contest $contest, Team $team, Assessment $assessment)
    {
        $assessment->deleted_by = $request->user()->id;
        $assessment->save();

        $assessment->delete();

        return response()->json([
            "message" => "La evaluación se ha eliminado correctamente",
        ]);
    }
}
