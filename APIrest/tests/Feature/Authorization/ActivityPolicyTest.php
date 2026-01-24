<?php

namespace Tests\Feature\Authorization;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Action;

class ActivityPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanViewActivityOfOwnClient(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();
        $activity = Activity::factory()->for($client)->create();
        $this->assertTrue($user->can('view', $activity));
    }

    public function testUserCannotViewActivityOfForeignClient(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $activity = Activity::factory()->for($client)->create();
        $this->assertFalse($user->can('view', $activity));
    }

    public function testAdminCanViewAnyActivity(): void
    {
        $admin = User::factory()->admin()->create();
        $client = Client::factory()->create();
        $activity = Activity::factory()->for($client)->create();
        $this->assertTrue($admin->can('view', $activity));
    }
}