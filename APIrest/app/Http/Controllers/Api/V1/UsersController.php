<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        return response()->json([
            'data' => $user,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->user();

        if (! $auth) {
            abort(401, 'Unauthenticated.');
        }

        if (! $auth->isAdmin()) {
            abort(403, 'Forbidden.');
        }

        $users = User::query()
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => $users,
        ]);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $auth = $request->user();

        if (! $auth) {
            abort(401, 'Unauthenticated.');
        }

        if (! $auth->isAdmin()) {
            abort(403, 'Forbidden.');
        }

        $user = User::findOrFail($id);

        return response()->json([
            'data' => $user,
        ]);
    }
}
