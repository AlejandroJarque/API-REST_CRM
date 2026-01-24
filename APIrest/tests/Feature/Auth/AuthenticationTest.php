<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_access_without_token_unauthorized(): void
    {
        $response = $this->getJson('/api/v1/me');
        $response->assertStatus(401);
    }

    public function test_access_with_token_authorized(): void
    {
        $user = User::factory()->create();

        Passport::actingAS($user);

        $response = $this->getJson('/api/v1/me');
        
        $response->assertStatus(200);
    }
}