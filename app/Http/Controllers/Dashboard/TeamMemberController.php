<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamMemberCollection;
use App\Models\Contest;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contest $contest, Team $team)
    {
        return new TeamMemberCollection($team->members()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Contest $contest, Team $team)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'father_last_name' => ['required', 'string', 'max:100'],
            'mother_last_name' => ['required', 'string', 'max:100'],
        ], TeamMember::$validateMessages);

        if($team->members()->count() >= 10) {
            return response()->json([
                'message' => 'El equipo ya tiene 10 miembros',
            ], 422);
        }

        $team->members()->create([
            'name' => $data['name'],
            'father_last_name' => $data['father_last_name'],
            'mother_last_name' => $data['mother_last_name'],
        ]);

        return response()->json([
            'message' => 'Miembro del equipo creado exitosamente',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contest $contest, Team $team, TeamMember $teamMember)
    {
        return new TeamMemberCollection([$teamMember]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contest $contest, Team $team, TeamMember $teamMember)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'father_last_name' => ['required', 'string', 'max:100'],
            'mother_last_name' => ['required', 'string', 'max:100'],
        ], TeamMember::$validateMessages);

        $teamMember->name = $data['name'];
        $teamMember->father_last_name = $data['father_last_name'];
        $teamMember->mother_last_name = $data['mother_last_name'];
        $teamMember->save();

        return response()->json([
            'message' => 'Miembro del equipo actualizado exitosamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest, Team $team, TeamMember $teamMember)
    {
        if($team->members()->count() <= 1) {
            return response()->json([
                'message' => 'El equipo debe tener al menos un miembro',
            ], 422);
        }

        $teamMember->delete();
        return response()->json([
            'message' => 'Miembro del equipo eliminado exitosamente',
        ]);
    }
}
