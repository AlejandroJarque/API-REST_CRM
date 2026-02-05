<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => ['prohibited'],
            'password' => ['prohibited'],
        ]);

        $user->update($validated);

        return response()->json([
            'data' => $user->fresh(),
        ]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Validation failed',
                'details' => [
                    'fields' => [
                        'current_password' => ['Invalid current password'],
                    ],
                ],
            ], 422);
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        return response()->json([
            'data' => $user->fresh(),
        ]);
    }
}
