<?php

namespace Tests\Feature\Dashboard;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Activity;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;


class DashboardTest extends TestCase
{
    use RefreshDatabase;
    
    public function testRequiresAuthentication()
    {
        $response = $this->getJson('/api/v1/dashboard');

        $response->assertStatus(401);
    }

   
    public function testReturnsDashboardStructureForAuthenticatedUser()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/dashboard');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'clients_count',
                'activities_count',
                'pending_activities_alerts',
        ]);
    }

    
    public function testUserSeesOnlyHisClientsAndActivities()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $otherUser = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $userClients = \App\Models\Client::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);

        Client::factory()->count(3)->create([
            'user_id' => $otherUser->id,
        ]);

        Activity::factory()->count(4)->create([
            'user_id' => $user->id,
            'client_id' => $userClients->first()->id,
        ]);

        Activity::factory()->count(5)->create([
            'user_id' => $otherUser->id,
            'client_id' => \App\Models\Client::where('user_id', $otherUser->id)->first()->id,
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/dashboard');

        $response
            ->assertStatus(200)
            ->assertJson([
                'clients_count' => 2,
                'activities_count' => 4,
                'pending_activities_alerts' => [],
        ]);
    }

    public function testAdminSeesGlobalClientsAndActivities()
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $user1 = User::factory()->create(['role' => User::ROLE_USER]);
        $user2 = User::factory()->create(['role' => User::ROLE_USER]);

        $clientsUser1 = \App\Models\Client::factory()->count(2)->create([
            'user_id' => $user1->id,
        ]);

        $clientsUser2 = \App\Models\Client::factory()->count(3)->create([
            'user_id' => $user2->id,
        ]);

        Activity::factory()->count(4)->create([
            'user_id' => $user1->id,
            'client_id' => $clientsUser1->first()->id,
        ]);

        Activity::factory()->count(6)->create([
            'user_id' => $user2->id,
            'client_id' => $clientsUser2->first()->id,
        ]);

        Passport::actingAs($admin);

        $response = $this->getJson('/api/v1/dashboard');

        $response
            ->assertStatus(200)
            ->assertJson([
                'clients_count' => 5,
                'activities_count' => 10,
                'pending_activities_alerts' => [],
        ]);
    }

    public function testUserSeesPendingActivitiesAlerts()
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $client = Client::factory()->create([
            'user_id' => $user->id,
        ]);

        Activity::factory()->create([
            'client_id' => $client->id,
            'completed_at' => null,
        ]);

        Activity::factory()->create([
            'client_id' => $client->id,
            'completed_at' => now(),
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/dashboard');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'pending_activities_alerts');
    }

    public function testAdminSeesGlobalPendingActivities()
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $user1 = User::factory()->create(['role' => User::ROLE_USER]);
        $user2 = User::factory()->create(['role' => User::ROLE_USER]);

        $client1 = Client::factory()->create(['user_id' => $user1->id]);
        $client2 = Client::factory()->create(['user_id' => $user2->id]);

        Activity::factory()->create([
            'client_id' => $client1->id,
            'completed_at' => null,
        ]);

        Activity::factory()->create([
            'client_id' => $client2->id,
            'completed_at' => null,
        ]);

        Activity::factory()->create([
            'client_id' => $client2->id,
            'completed_at' => now(),
        ]);

        Passport::actingAs($admin);

        $response = $this->getJson('/api/v1/dashboard');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2, 'pending_activities_alerts');
    }


}
