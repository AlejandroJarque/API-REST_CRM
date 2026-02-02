<?php

namespace Tests\Feature\Clients;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Events\ClientCreated;
use Illuminate\Support\Facades\Event;

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
        Event::fake([ClientCreated::class]);

        $user = User::factory()->create();
        $payload = [
            'email' => 'not-an-email'
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/v1/clients', $payload);

        $this->assertApiError($response, 422, true);

        Event::assertNotDispatched(ClientCreated::class);
    }

    public function testUserCanCreateClient(): void
    {
        Event::fake([ClientCreated::class]);

        $user = User::factory()->create();
        $payload = [
            'name' => 'Acme Corp',
            'email' => 'info@acme.test',
            'phone' => '123456789',
            'address' => 'Main Street 1',
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/v1/clients', $payload);
        $response->assertStatus(201);

        $this->assertDatabaseHas('clients', [
            'name' => 'Acme Corp',
            'user_id' => $user->id,
        ]);

        Event::assertDispatched(ClientCreated::class, function (ClientCreated $event) use ($user) {
            return $event->client->user_id === $user->id;
        });
    }
}
