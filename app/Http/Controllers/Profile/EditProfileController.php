<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validate = $request->validate([

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
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
        ]);

        $user = $request->user();

        $user->name = $validate['name'];
        $user->email = $validate['email'];
        $user->save();

        return response()->json([
            'message' => 'Perfil Actualizado Correctamente'
        ]);
    }
}
