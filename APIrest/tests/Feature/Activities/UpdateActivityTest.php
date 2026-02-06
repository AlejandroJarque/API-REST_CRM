<?php

namespace Tests\Feature\Activities;

use App\Models\User;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateActivityTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotUpdateActivity(): void
    {
        $activity = Activity::factory()->create();

        $this->patchJson("/api/v1/activities/{$activity->id}", [
            'description' => 'New description',
        ])->assertStatus(401);
    }

    public function testUserCannotUpdateActivityOfForeignClient(): void
    {
        $user = User::factory()->create();
        $foreignClient = Client::factory()->create();

        $activity = Activity::factory()->create([
            'client_id' => $foreignClient->id,
            'user_id' => $foreignClient->user_id,
        ]);

        $this->actingAs($user)
            ->patchJson("/api/v1/activities/{$activity->id}", [
                'description' => 'New description',
            ])
            ->assertStatus(403);
    }

    public function testUpdateActivityWithInvalidPayloadReturns422(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'user_id' => $client->user_id,
        ]);

        $this->actingAs($user)
            ->patchJson("/api/v1/activities/{$activity->id}", [
                'description' => '',
            ])
            ->assertStatus(422);
    }

    public function testOwnerCanUpdateActivityDescription(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'user_id' => $client->user_id,
            'description' => 'Old description',
        ]);

        $this->actingAs($user)
            ->patchJson("/api/v1/activities/{$activity->id}", [
                'description' => 'Updated description',
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'description' => 'Updated description',
        ]);
    }

    public function testAdminCanUpdateAnyActivity(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create();

        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'user_id'=> $client->user_id,
            'description' => 'Old description',
        ]);

        $this->actingAs($admin)
            ->patchJson("/api/v1/activities/{$activity->id}", [
                'description' => 'Admin updated description',
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'description' => 'Admin updated description',
        ]);
    }

    public function testUpdatingStatusToDoneSetsCompletedAt(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'user_id' => $client->user_id,
            'status' => Activity::STATUS_PENDING,
            'completed_at' => null,
        ]);

        $this->actingAs($user)
            ->patchJson("/api/v1/activities/{$activity->id}", [
                'status' => Activity::STATUS_DONE,
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => Activity::STATUS_DONE,
        ]);

        $this->assertNotNull(
            Activity::find($activity->id)->completed_at
        );
    }

    public function testUpdatingStatusToPendingClearsCompletedAt(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'user_id' => $client->user_id,
            'status' => Activity::STATUS_DONE,
            'completed_at' => now(),
        ]);

        $this->actingAs($user)
            ->patchJson("/api/v1/activities/{$activity->id}", [
                'status' => Activity::STATUS_PENDING,
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'status' => Activity::STATUS_PENDING,
        ]);

        $this->assertNull(
            Activity::find($activity->id)->completed_at
        );
    }

}
