<?php

namespace Tests\Feature\Clients;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteClientTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotDeleteClient(): void
    {
        $client = Client::factory()->create();
        $response = $this->deleteJson("/api/v1/clients/{$client->id}");
        $response->assertStatus(401);
    }

    public function testUserCannotDeleteForeignClient(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($user, 'api')->deleteJson("/api/v1/clients/{$client->id}");
        $response->assertStatus(403);
    }

    public function testUserCanDeleteOwnClient(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        $response = $this->actingAs($user, 'api')->deleteJson("/api/v1/clients/{$client->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('clients', [ 
            'id' => $client->id
        ]);
    }

    public function testAdminCanDeleteAnyClient(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($admin, 'api')->deleteJson("/api/v1/clients/{$client->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('clients', [
            'id' => $client->id,
        ]);
    }
}
