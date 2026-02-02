<?php

namespace Tests\Feature\Activities;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListActivitiesTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotListActivities(): void 
    {

        $this->getJson('/api/v1/activities')->assertStatus(401);
    }

    public function testUserOnlySeesActivitiesFromOwnClients(): void
    {
        $user = User::factory()->create();

        $ownClient = Client::factory()->for($user)->create();
        $foreignClient = Client::factory()->create();

        Activity::factory()->count(2)->for($ownClient)->create();
        Activity::factory()->count(3)->for($foreignClient)->create();

        $this->actingAs($user, 'api')
            ->getJson('api/v1/activities')
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function testAdminSeesAllActivities(): void
    {
        $admin = User::factory()->admin()->create();

        Activity::factory()->count(5)->create();

        $this->actingAs($admin, 'api')
            ->getJson('api/v1/activities')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }
         
}