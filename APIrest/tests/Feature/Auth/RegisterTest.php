<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterCreatesUserWithForcedUserRole(): void
    {
        $payload = [
            'email' => 'new.user@example.com',
            'password' => 'Password123',
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $response->assertStatus(201);

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

        $this->assertDatabaseHas('users', [
            'email' => 'new.user@example.com',
            'role' => User::ROLE_USER,
        ]);

        $user = User::where('email', 'new.user@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('Password123', $user->password));

        $response->assertJsonMissingPath('data.password');
        $response->assertJsonMissingPath('data.remember_token');
    }

    public function testRegisterFailsWhenEmailAlreadyExists(): void
    {
        User::factory()->create([
            'email' => 'dup@example.com',
        ]);

        $payload = [
            'email' => 'dup@example.com',
            'password' => 'Password123',
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $this->assertApiError($response, 422, true);

        $response->assertJsonPath('details.fields.email', fn ($v) => is_array($v) && count($v) > 0);
    }

    public function testRegisterFailsWithInvalidPassword(): void
    {
        $payload = [
            'email' => 'weak@example.com',
            'password' => 'short',
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $this->assertApiError($response, 422, true);

        $response->assertJsonPath('details.fields.password', fn ($v) => is_array($v) && count($v) > 0);
    }

    public function testRegisterRejectsRoleInBody(): void
    {
        $payload = [
            'email' => 'attacker@example.com',
            'password' => 'Password123',
            'role' => 'admin',
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $this->assertApiError($response, 422, true);

        $response->assertJsonPath('details.fields.role', fn ($v) => is_array($v) && count($v) > 0);

        $this->assertDatabaseMissing('users', [
            'email' => 'attacker@example.com',
        ]);
    }
}
