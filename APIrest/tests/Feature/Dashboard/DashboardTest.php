<?php

namespace Tests\Feature\Dashboard;

use Tests\TestCase;
use App\Models\User;
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

}
