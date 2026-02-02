<?php

namespace App\Listeners;

use App\Domain\Events\ClientCreated;
use Illuminate\Support\Facades\Log;

final class LogClientCreation
{
    public function handle(ClientCreated $event): void
    {
        Log::info('clients.created', [
            'client_id' => $event->client->id,
            'user_id' => $event->client->user_id,
            'email' => $event->client->email,
        ]);
    }
}