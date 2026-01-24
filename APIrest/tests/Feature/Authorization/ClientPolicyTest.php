<?php

namespace Tests\Feature\Authorization;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanViewOwnClient(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();
        $this->assertTrue($user->can('view', $client));
    }

    public function testUserCannotViewForeignClient(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $this->assertFalse($user->can('view', $client));
    }

    public function testAdminCanViewAnyClient(): void
    {
        $admin = user::factory()->admin()->create();
        $client = Client::factory()->create();
        $this->assertTrue($admin->can('view', $client));
    }
}