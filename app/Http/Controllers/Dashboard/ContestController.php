<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Contest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ContestCollection;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Laravel\Facades\Image;

class ContestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $contests = Contest::where('user_id', $request->user()->id)
            ->orWhereRelation('evaluators', 'user_id', $request->user()->id)
            ->get();

        return new ContestCollection($contests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'image' => ['required', 'file', 'image', 'max:2056'],
        ], Contest::$validateMessages);

        $file = $request->file('image');
        $nameImage = Str::uuid() . '.' . $file->extension();

        $image = Image::read($file);
        $image->cover(800, 800);

        if (!File::exists(Storage::path('contests'))) {
            File::makeDirectory(Storage::path('contests'));
        }

        $image->save(Storage::path("contests/{$nameImage}"));

        $request->user()->contests()->create([
            'name' => $data['name'],
            'image' => $nameImage,
            'is_published' => 0,
        ]);

        return response()->json([
            'message' => 'Competencia creada exitosamente',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contest $contest)
    {
        return new ContestCollection([
            $contest->load(['teams', 'exercises', 'evaluators'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contest $contest)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'file', 'image', 'max:2056'],
        ], Contest::$validateMessages);

        $file = $request->file('image');

        if ($file) {
            $nameImage = Str::uuid() . '.' . $file->extension();

            $image = Image::read($file);
            $image->cover(800, 800);

            if (!File::exists(Storage::path('contests'))) {
                File::makeDirectory(Storage::path('contests'));
            }

            $image->save(Storage::path("contests/{$nameImage}"));
            Storage::delete("contests/{$contest->image}");
            $contest->image = $nameImage;
        }

        $contest->name = $data['name'];
        $contest->save();

        return response()->json([
            'message' => 'Competencia actualizada exitosamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest)
    {
        Storage::delete("contests/{$contest->image}");

        $contest->delete();

        return response()->json([
            'message' => 'Competencia eliminada exitosamente',
        ]);
    }
}
