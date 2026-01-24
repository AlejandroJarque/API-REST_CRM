<?php

namespace Tests\Feature\Clients;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowClientTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotViewClient(): void
    {
        $client = Client::factory()->create();
        $response = $this->getJson("/api/v1/clients/{$client->id}");
        $response->assertStatus(401);
    }

    public function testUserCannotViewForeignClient(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $response = $this->actingAs($user, 'api')->getJson("/api/v1/clients/{$client->id}");
        $response->assertStatus(403);
    }

    public function testUserCanViewOwnClient(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();
        $response = $this->actingAs($user, 'api')->getJson("/api/v1/clients/{$client->id}");
        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $client->id);
    }

    public function testAdminCanViewAnyClient(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create();

        $response = $this->actingAs($admin, 'api')->getJson("/api/v1/clients/{$client->id}");

        $response->assertStatus(200);
    }


}
