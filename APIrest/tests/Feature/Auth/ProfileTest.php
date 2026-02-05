<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function testGetProfileReturnsAuthenticatedUserData(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/profile');

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

        $response->assertJsonPath('data.email', 'john@example.com');
        $response->assertJsonMissingPath('data.password');
    }

    public function testUserCanUpdateOwnProfile(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        Passport::actingAs($user);

        $payload = [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ];

        $response = $this->patchJson('/api/v1/profile', $payload);

        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'New Name');
        $response->assertJsonPath('data.email', 'new@example.com');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'new@example.com',
        ]);
    }

    public function testUserCanChangePassword(): void
    {
        $user = User::factory()->create([
            'password' => 'OldPassword123',
        ]);

        Passport::actingAs($user);

        $response = $this->patchJson('/api/v1/profile/password', [
            'current_password' => 'OldPassword123',
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $response->assertStatus(200);
    }


}
