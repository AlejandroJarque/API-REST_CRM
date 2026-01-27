<?php

namespace Tests\Feature\Activities;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowActivityTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCannotViewActivityOfForeignClient(): void
    {
        $user = User::factory()->create();
        $foreignClient = Client::factory()->create();

        $activity = Activity::factory()->create([
            'client_id' => $foreignClient->id,
            'user_id' => $foreignClient->user_id,
        ]);

        $this->actingAs($user, 'api')
            ->getJson("api/v1/activities/{$activity->id}")
            ->assertStatus(403);
    }

    public function testGuestCannotViewActivity(): void
    {
        $client = Client::factory()->create();

        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'user_id' => $client->user_id,
        ]);

        $this->getJson("/api/v1/activities/{$activity->id}")
            ->assertStatus(401);
    }
}