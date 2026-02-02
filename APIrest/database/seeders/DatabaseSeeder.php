<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Activity;
use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            DevelopmentUsersSeeder::class,
            DevelopmentClientsSeeder::class,
            DevelopmentActivitiesSeeder::class,
        ]);
    }
}
