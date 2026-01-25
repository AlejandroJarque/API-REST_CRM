<?php

namespace Tests\Feature\Clients;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateClientTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotUpdateClient(): void
    {
        $client = Client::factory()->create();

        $response = $this->patchJson( "/api/v1/clients/{$client->id}", ['name' => 'Updated',]);

        $response->assertStatus(401);
    }

    public function testUserCannotUpdateForeignClient(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user, 'api')->patchJson("/api/v1/clients/{$client->id}", ['name' => 'Updated',]);
        $response->assertStatus(403);
    }

    public function testUserCannotUpdateClientWithInvalidData(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        $response = $this->actingAs($user, 'api')->patchJson("/api/v1/clients/{$client->id}",['email' => 'not-an-email',]);
        $response->assertStauts(422);
    }

    public function testUserCanUpdateOwnClient(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create(['name' => 'Old name']);

        $response = $this->actingAs($user, 'api')->patchJson("/api/v1/clients/{$client->id}",['name' => 'New Name',]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'New Name',
        ]);
    }

    public function testAdminCanUpdateAnyClient(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create(['name' => 'Old Name',]);

        $response = $this->actingAs($admin, 'api')->patchJson("/api/v1/clients/{$client->id}", ['name' => 'Admin Update',]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Admin Update'
        ]);
    }
}
