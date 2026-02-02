<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Activity;
use App\Application\Activities\ActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use Illuminate\Http\Response;

class ActivityController extends Controller
{
    public function __construct(private readonly ActivityService $activites)
    {
        
    }
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Activity::class);

        $user = $request->user();

        $activities = $this->activites->listFor($user);
        
        return response()->json([
            'data' => $activities,
        ]);
    }

    public function store(StoreActivityRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $client = Client::findOrFail($validated['client_id']);

        $this->authorize('create', [\App\Models\Activity::class, $client]);

        $activity = $this->activites->createForClient(
            $validated['client_id'],
            $validated
        );

        return response()->json(['data' => $activity], 201);
    }

    public function show(Activity $activity): JsonResponse
    {
        $this->authorize('view', $activity);

        return  response()->json([
            'data' => $activity,
        ]);
    }

    public function update(UpdateActivityRequest $request, Activity $activity): JsonResponse
    {
        $this->authorize('update', $activity);

        $activity = $this->activites->update($activity, $request->validated());

        return response()->json(['data' => $activity], 200);
    }

    public function destroy(Activity $activity): Response
    {
        $this->authorize('delete', $activity);

        $this->activites->delete($activity);
        
        return response()->noContent();
    }

    
}