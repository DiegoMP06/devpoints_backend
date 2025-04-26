<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'El email ya ha sido verificado.'], 409);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'El email de verificación ha sido enviado.']);
    }
}
