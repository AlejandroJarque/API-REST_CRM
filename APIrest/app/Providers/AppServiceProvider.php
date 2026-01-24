<?php

namespace App\Providers;

use App\Models\Activity;
use App\Policies\ActivityPolicy;
use App\Policies\ClientPolicy;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Passport::loadKeysFrom(storage_path());
    }
}
