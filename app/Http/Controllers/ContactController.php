<?php

namespace App\Http\Controllers;

use App\Jobs\SendContactFormEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        SendContactFormEmail::dispatch(
            senderName: $validated['name'],
            senderEmail: $validated['email'],
            messageBody: $validated['message'],
        );

        return response()->json([
            'success' => true,
            'message' => 'شكراً لتواصلك، سأرد عليك قريباً',
        ]);
    }
}
