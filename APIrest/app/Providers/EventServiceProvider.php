<?php

namespace App\Providers;

use App\Domain\Events\ClientCreated;
use App\Domain\Events\ActivityRegistered;
use App\Listeners\LogClientCreation;
use App\Listeners\LogActivityRegistered;
use App\Models\Client;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


final class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ClientCreated::class => [
            LogClientCreation::class,
        ],
        ActivityRegistered::class => [
            LogActivityRegistered::class,
        ],
    ];

}
