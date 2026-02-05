<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MeTest extends TestCase
{
    use RefreshDatabase;

    public function testMeReturnsAuthenticatedUser(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/me');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'role',
                'created_at',
                'updated_at',
            ],
        ]);

        $response->assertJsonPath('data.id', $user->id);
        $response->assertJsonPath('data.email', $user->email);

        
        $response->assertJsonMissingPath('data.password');
        $response->assertJsonMissingPath('data.remember_token');
    }

    public function testMeFailsWhenUnauthenticated(): void
    {
        $response = $this->getJson('/api/v1/me');

        $response->assertStatus(401);
    }
}
