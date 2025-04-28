<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'El nombre es requerido.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe tener más de 255 caracteres.',
            'email.required' => 'El email es requerido.',
            'email.string' => 'El email debe ser una cadena de texto.',
            'email.lowercase' => 'El email debe estar en minúsculas.',
            'email.email' => 'El email debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El email no debe tener más de 255 caracteres.',
            'email.unique' => 'El email ya está registrado.',
            'password.required' => 'La contraseña es requerida.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        event(new Registered($user));

        Auth::login($user, true);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken(name: 'token', expiresAt: now()->addDays(1))->plainTextToken,
        ]);
    }
}
