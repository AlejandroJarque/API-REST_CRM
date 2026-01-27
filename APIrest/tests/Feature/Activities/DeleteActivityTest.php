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

    public function testOwnerCanDeleteOwnActivity(): void
    {
        $user = User::factory()->create();

        $client = Client::factory()->for($user)->create();

        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'user_id' => $client->user_id,
        ]);

        $this->actingAs($user,'api')
            ->deleteJson("/api/v1/activities/{$activity->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('activities', [
            'id' => $activity->id,
        ]);
    }

    public function testAdminCanDeleteAnyActivity(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create();

        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'user_id' => $client->user_id,
        ]);

        $this->actingAs($admin, 'api')
            ->deleteJson("/api/v1/activities/{$activity->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('activities', [
            'id' => $activity->id,
        ]);
    }
}