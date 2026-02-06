<?php

namespace Tests\Feature\Activities;

use App\Models\Activity;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateActivityTest extends TestCase
{
    use RefreshDatabase;


    public function testItCanCreateAnActivityWithValidDomainFields(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/v1/activities', [
            'client_id' => $client->id,
            'title' => 'Call client',
            'status' => 'pending',
            'date' => '2026-02-05',
            'description' => 'Optional notes',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('activities', [
            'client_id' => $client->id,
            'title' => 'Call client',
            'status' => 'pending',
            'date' => '2026-02-05 00:00:00',
        ]);
    }

    public function testItCannotCreateActivityWithoutTitle(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->postJson('/api/v1/activities', [
            'client_id' => $client->id,
            'status' => 'pending',
            'date' => '2026-02-05',
        ])->assertStatus(422);
    }

    public function testItCannotCreateActivityWithInvalidStatus(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->postJson('/api/v1/activities', [
            'client_id' => $client->id,
            'title' => 'Call client',
            'status' => 'invalid_status',
            'date' => '2026-02-05',
        ])->assertStatus(422);
    }

    public function testItCannotCreateActivityWithoutDate(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->postJson('/api/v1/activities', [
            'client_id' => $client->id,
            'title' => 'Call client',
            'status' => 'pending',
        ])->assertStatus(422);
    }

    public function testItCannotCreateActivityWithoutDescription(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->postJson('/api/v1/activities', [
            'client_id' => $client->id,
            'title' => 'Send proposal',
            'status' => 'pending',
            'date' => '2026-02-10',
        ])->assertStatus(422);
    }

}
