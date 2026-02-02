<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testAccessWithoutTokenUnauthorized(): void
    {
        $response = $this->getJson('/api/v1/me');
        $this->assertApiError($response, 401);
    }

    public function testAccessWithTokenAuthorized(): void
    {
        $user = User::factory()->create();

        Passport::actingAS($user);

        $response = $this->getJson('/api/v1/me');
        
        $response->assertStatus(200);
    }
}