<?php

namespace Tests\Feature\Clients;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateClientTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotCreateClient(): void
    {
        $payload = [
            'name' => 'Acme Corp',
            'email' => 'info@acme.test',
            'phone' => '123456789',
            'address' => 'Main Street 1',
        ];

        $response = $this->postJson('/api/v1/clients', $payload);
        $response->assertStatus(401);
        
    }

    public function testUserCannotCreateClientWithInvalidData(): void
    {
        $user = User::factory()->create();
        $payload = [
            'email' => 'not-an-email'
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/v1/clients', $payload);

        $response->assertStatus(422);
    }

    public function testUserCanCreateClient(): void
    {
        $user = User::factory()->create();
        $payload = [
            'name' => 'Acme Corp',
            'email' => 'info@acme.test',
            'phone' => '123456789',
            'address' => 'Main Street 1',
        ];

        $response = $this->actingAs($user, 'api')->postJson('api/v1/clients', $payload);
        $response->assertStatus(201);

        $this->assertDatabaseHas('clients', [
            'name' => 'Acme Corp',
            'user_id' => $user->id,
        ]);
    }
}
