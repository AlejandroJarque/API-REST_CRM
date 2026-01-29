<?php

namespace App\Listeners;

use App\Domain\Events\ActivityRegistered;
use Illuminate\Support\Facades\Log;

final class LogActivityRegistered
{
    public function handle(ActivityRegistered $events): void
    {
        Log::info('activity.registered', [
            'activity_id' => $events->activity->id,
            'client_id' => $events->activity->client_id,
            'user_id' => $events->activity->user_id,
        ]);
    }
}