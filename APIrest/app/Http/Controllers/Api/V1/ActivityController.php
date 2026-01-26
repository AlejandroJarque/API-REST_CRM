<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}