<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
     public function __invoke(): JsonResponse
    {
        return response()->json([
            'clients_count' => 0,
            'activities_count' => 0,
            'pending_activities_alerts' => [],
        ]);
    }
}
