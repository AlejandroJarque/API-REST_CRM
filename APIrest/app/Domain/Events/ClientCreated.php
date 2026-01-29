<?php

namespace App\Domain\Events;

use App\Models\Client;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ClientCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Client $client)
    {
        
    }
}