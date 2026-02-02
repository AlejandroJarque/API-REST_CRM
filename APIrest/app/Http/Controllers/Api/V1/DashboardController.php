<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Application\Dashboard\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) 
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->dashboardService->getFor($request->user());

        return response()->json($data);
    }
}
