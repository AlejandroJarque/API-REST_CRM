<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Client;
use App\Models\User;

class DevelopmentActivitiesSeeder extends Seeder
{
    
    public function run(): void
    {
        Activity::query()->delete();

        $devUser = User::where('email', 'dev@example.com')->firstOrFail();
        $secondUser = User::where('email', 'user2@example.com')->firstOrFail();

        $devClients = Client::where('user_id', $devUser->id)->get();
        $secondClients = Client::where('user_id', $secondUser->id)->get();

        foreach($devClients as $client) {
            Activity::factory()->count(2)->create([
                'user_id' => $devUser->id,
                'client_id' => $client->id,
            ]);
        }

        foreach($secondClients as $client) {
            Activity::factory()->count(1)->create([
                'user_id' => $secondUser->id,
                'client_id' => $client->id,
            ]);
        }
    }
}
