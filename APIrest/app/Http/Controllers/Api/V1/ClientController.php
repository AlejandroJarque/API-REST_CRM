<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;



class ClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $clients = $user->role === 'admin' ? Client::all() : Client::where('user_id', $user->id)->get();
        
        return response()->json([
            'data'=>$clients,
        ]);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['data' => $client], 201);
    }

    public function show(Client $client): JsonResponse
    {
        $this->authorize('view', $client);
        
        return response()->json([
            'data' => $client,
        ]);
    }

    public function update(Client $client): JsonResponse
    {
        return response()->json([]);
    }
}