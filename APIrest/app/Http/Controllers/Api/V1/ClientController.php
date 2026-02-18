<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Application\Clients\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



class ClientController extends Controller
{
    public function __construct(private readonly ClientService $clients)
    {
        
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Client::class);

        $user = $request->user();

        if(! $user) {
            abort(401, 'Unauthenticated.');
        }

        $clients = $this->clients->listFor($user);
        
        return response()->json([
            'data'=>$clients,
        ]);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = $this->clients->createFor(
            $request->user(),
            $request->validated()
        );

        return response()->json(['data' => $client], 201);
    }

    public function show(Client $client): JsonResponse
    {
        $this->authorize('view', $client);
        
        return response()->json([
            'data' => $client,
        ]);
    }

    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $this->authorize('update', $client);

        $client = $this->clients->update($client, $request->validated());

        return response()->json(['data' => $client]);
    }

    public function destroy(Client $client): Response
    {
        $this->authorize('delete', $client);

        $this->clients->delete($client);

        return response()->noContent();
    }
}