<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamCollection;
use App\Models\Contest;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contest $contest)
    {
        return new TeamCollection($contest->teams()->without(['members', 'assessments'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Contest $contest, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'members' => ['required', 'array', 'min:1', 'max:10'],
            'members.*.name' => ['required', 'string', 'max:100'],
            'members.*.father_last_name' => ['required', 'string', 'max:100'],
            'members.*.mother_last_name' => ['required', 'string', 'max:100'],
        ], Team::$validateMessages);

        $team = $contest->teams()->create([
            'name' => $request->name,
        ]);

        foreach ($request->members as $member) {
            $team->members()->create([
                'name' => $member['name'],
                'father_last_name' => $member['father_last_name'],
                'mother_last_name' => $member['mother_last_name'],
            ]);
        }

        return response()->json([
            'message' => 'Equipo creado exitosamente',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contest $contest, Team $team)
    {
        return new TeamCollection([$team->load(['members', 'assessments'])]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contest $contest, Team $team)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ], Team::$validateMessages);

        $team->name = $request->name;
        $team->save();

        return response()->json([
            'message' => 'Equipo actualizado exitosamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest, Team $team)
    {
        $team->delete();
        return response()->json([
            'message' => 'Equipo eliminado exitosamente',
        ]);
    }
}
