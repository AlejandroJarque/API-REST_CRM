<?php

namespace Tests\Feature\Activities;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteActivityTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotDeleteActivity(): void
    {
        $client = Client::factory()->create();

        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'user_id' => $client->user_id,
        ]);

        $this->deleteJson("/api/v1/activities/{$activity->id}")
            ->assertStatus(401);
    }

    public function testUserCannotDeleteActivityOfForeignClient(): void
    {
        $user = User::factory()->create();

        $foreignClient = Client::factory()->create();

        $activity = Activity::factory()->create([
            'client_id' => $foreignClient->id,
            'user_id' => $foreignClient->user_id,
        ]);

        $this->actingAs($user, 'api')
            ->deleteJson("api/v1/activities/{$activity->id}")
            ->assertStatus(403);
    }
}