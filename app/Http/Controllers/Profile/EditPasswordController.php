<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EditPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validate = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'current_password.required' => 'La contraseña es requerida.',
            'current_password.string' => 'La contraseña es invalida.',
            'password.required' => 'La contraseña es requerida.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = $request->user();

        $isCorrectPassword = Hash::check($validate['current_password'], $user->password);

        if (!$isCorrectPassword) {
            throw ValidationException::withMessages([
                'current_password' => ["Contraseña Incorrecta"],
            ]);
        }

        $user->password = Hash::make($validate['password']);
        $user->save();

        return response()->json([
            'message' => 'La contraseña ha sido cambiada.'
        ]);
    }
}
