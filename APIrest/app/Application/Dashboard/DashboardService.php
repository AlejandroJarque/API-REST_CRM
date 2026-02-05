<?php

namespace App\Application\Dashboard;

use App\Models\User;
use App\Models\Client;
use App\Models\Activity;

class DashboardService
{
    public function getFor(User $user): array
    {
        if ($user->isAdmin()) {
            return $this->forAdmin();
        }

        return $this->forUser($user);
    }

    private function forAdmin(): array
    {
        return [
            'clients_count' => Client::count(),
            'activities_count' => Activity::count(),

            'pending_activities_alerts' => Activity::query()
                ->where('status', Activity::STATUS_PENDING)
                ->get()
                ->map(fn (Activity $activity) => [
                    'activity_id' => $activity->id,
                ])
                ->values(),
        ];
    }

    private function forUser(User $user): array
    {
        return [
            'clients_count' => Client::where('user_id', $user->id)->count(),

            'activities_count' => Activity::whereHas('client', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),

            'pending_activities_alerts' => Activity::query()
                ->where('status', Activity::STATUS_PENDING)
                ->whereHas('client', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->get()
                ->map(fn (Activity $activity) => [
                    'activity_id' => $activity->id,
                ])
                ->values(),
        ];
    }
}

