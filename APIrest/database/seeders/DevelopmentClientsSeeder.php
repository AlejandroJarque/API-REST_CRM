<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\User;

class DevelopmentClientsSeeder extends Seeder
{
    
    public function run(): void
    {
        Client::query()->delete();

        $devUser = User::where('email', 'dev@example.com')->firstOrFail();
        $secondUser = User::where('email', 'user2@example.com')->firstOrFail();

        Client::factory()->count(3)->create([
            'user_id' => $devUser->id,
        ]);

        Client::factory()->count(1)->create([
            'user_id' => $secondUser->id,
        ]);
    }
}
