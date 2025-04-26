<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseCollection;
use App\Models\Contest;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contest $contest)
    {
        return new ExerciseCollection($contest->exercises()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Contest $contest)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'points' => ['required', 'numeric', 'integer', 'min:1'],
        ], Exercise::$validateMessages);

        $contest->exercises()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'points' => $data['points'],
        ]);

        return response()->json([
            'message' => 'Ejercicio creado exitosamente',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contest $contest, Exercise $exercise)
    {
        return new ExerciseCollection([$exercise]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contest $contest, Exercise $exercise)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'points' => ['required', 'numeric', 'integer', 'min:1'],
        ], Exercise::$validateMessages);

        $exercise->name = $data['name'];
        $exercise->description = $data['description'];
        $exercise->points = $data['points'];
        $exercise->save();

        return response()->json([
            'message' => 'Ejercicio actualizado exitosamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest, Exercise $exercise)
    {
        $exercise->delete();
        return response()->json([
            'message' => 'Ejercicio eliminado exitosamente',
        ]);
    }
}
