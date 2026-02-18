<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Activity;
use App\Models\User;
use App\Policies\ClientPolicy;
use App\Policies\ActivityPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Client::class => ClientPolicy::class,
        Activity::class => ActivityPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
