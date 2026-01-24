<?php

namespace Tests\Feature\Clients;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListClientsTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotListClients(): void
    {
        $response = $this->getJson('/api/v1/clients');
        $response->assertStatus(401);
    }

    public function testUserOnlySeesOwnClients(): void
    {
        $user = User::factory()->create();

        Client::factory()->count(2)->for($user)->create();
        Client::factory()->count(3)->create();

        $response = $this->actingAs($user, 'api')->getJson('api/v1/clients');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    public function testAdminSeesAllClientss(): void
    {
        $admin = User::factory()->admin()->create();

        Client::factory()->count(5)->create();

        $response = $this->actingAs($admin, 'api')->getJson('/api/v1/clients');
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }
}