<?php

namespace Tests\Feature\Activities;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Events\ActivityRegistered;
use Illuminate\Support\Facades\Event;

class CreateActivityTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotCreateActivities(): void
    {
        $this->postJson('api/v1/activities', [])->assertStatus(401);
    }

    public function testCannotCreateActivityWithInvalidPayload(): void
    {
        Event::fake([ActivityRegistered::class]);

        $user = User::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson('/api/v1/activities', [
                'description'=>'',
            ])
            ->assertStatus(422);

        Event::assertNotDispatched(ActivityRegistered::class);
    }

    public function testClientIdIsRequired(): void
    {
        $user = User::factory()->create();

        $response=$this->actingAs($user, 'api')
            ->postJson('/api/v1/activities', [
                'description' => 'Call notes',
            ]);
        
        $this->assertApiError($response, 422, true);

        $response->assertJsonPath('details.fields.client_id', fn($value) => is_array($value));
    }

    public function testUserCannotCreateActivityForForeignClient(): void
    {
        $user = User::factory()->create();
        $foreignClient = Client::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson('/api/v1/activities', [
                'description' => 'Atempt',
                'client_id' => $foreignClient->id,
            ])
            ->assertStatus(403);
    }

    public function testCanCreateActivityForOwnClientAndUserIdIsForcedFromClient(): void
    {
        Event::fake([ActivityRegistered::class]);

        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        $this->actingAs($user, 'api')
            ->postJson('/api/v1/activities', [
                'description'=>'Meeting notes',
                'client_id'=>$client->id,
                'user_id' => 999, 
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('activities', [
            'client_id' => $client->id,
            'user_id' => $client->user_id,
            'description'=>'Meeting notes',
        ]);

        Event::assertDispatched(ActivityRegistered::class, function (ActivityRegistered $event) use ($client) {
            return $event->activity->client_id === $client->id
                && $event->activity->user_id === $client->user_id;
        });
    }

    public function testAdminCanCreateActivityForAnyClient(): void
    {
        $admin = User::factory()->Admin()->create();
        $client = Client::factory()->create();

        $this->actingAs($admin, 'api')
            ->postJson('/api/v1/activities', [
                'description' => 'Admin note',
                'client_id' => $client->id,
            ])
            ->assertStatus(201);
    }

}