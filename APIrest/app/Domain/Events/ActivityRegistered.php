<?php

namespace App\Domain\Events;

use App\Models\Activity;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ActivityRegistered
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Activity $activity)
    {
        
    }
}
