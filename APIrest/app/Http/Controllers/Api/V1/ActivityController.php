<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreActivityRequest;

class ActivityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Activity::class);

        $user = $request->user();

        $activities = $user->isAdmin()
            ? Activity::all()
            : Activity::whereHas('client', function($q) use ($user) {
            $q->where('user_id', $user->id);
            })->get();
        
        return response()->json([
            'data' => $activities,
        ]);
    }

    public function store(StoreActivityRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $client = Client::findOrFail($validated['client_id']);

        $this->authorize('create', [\App\Models\Activity::class, $client]);

        $activity = \App\Models\Activity::create([
            'description' => $validated['description'],
            'client_id' => $client->id,
            'user_id' => $client->user_id,
        ]);

        return response()->json(['data' => $activity], 201);
    }

    public function show(Activity $activity): JsonResponse
    {
        $this->authorize('view', $activity);

        return  response()->json([
            'data' => $activity,
        ]);
    }

    public function update(Request $request, Activity $activity): JsonResponse
    {
        return response()->json(['data' => $activity], 200);
    }
}