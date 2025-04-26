<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\Contest;
use App\Models\User;
use Illuminate\Http\Request;

class SearchForUsersForEvaluatorsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Contest $contest)
    {
        $validate = $request->validate([
            'query' => ['required', 'string'],
        ], [
            'query.required' => 'El campo de búsqueda es requerido',
            'query.string' => 'El campo de búsqueda debe ser una cadena de texto',
        ]);

        $users = User::where('name', 'LIKE', '%' . $validate['query'] . '%')
            ->orWhere('email', 'LIKE', '%' . $validate['query'] . '%')
            ->get(['id', 'name', 'email']);

        return new UserCollection($users);
    }
}
