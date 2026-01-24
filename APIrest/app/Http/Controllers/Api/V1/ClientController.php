<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Client::class);
        $user = $request->user();

        $clients = $user->role === 'admin' ? Client::all() : Client::where('user_id', $user->id)->get();
        
        return response()->json([
            'data'=>$clients,
        ]);
    }

    public function store(): JsonResponse
    {
        return response()->json([], 201);
    }

}