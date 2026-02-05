<?php

namespace Tests\Feature\Users;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotSeeOwnProfile(): void
    {
        $this->getJson('/api/v1/users/me')->assertStatus(401);
    }

    public function testUserCannotListUsers(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api')->getJson('/api/v1/users')
            ->assertStatus(403);
    }

    public function testUserCannotSeeOtherUsersById(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($user, 'api')->getJson('/api/v1/users/' . $other->id)
            ->assertStatus(403);
    }

    public function testUserCannotSeeSelfByIdEvenIfSameId(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api')->getJson('/api/v1/users/' . $user->id)
            ->assertStatus(403);
    }

    public function testAdminCanListUsers(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(5)->create();

        $res = $this->actingAs($admin, 'api')->getJson('/api/v1/users');

        $res->assertStatus(200);

        $res->assertJsonCount(6, 'data');
    }

    public function testAdminCanSeeAnyUserById(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();

        $res = $this->actingAs($admin, 'api')->getJson('/api/v1/users/' . $target->id);

        $res->assertStatus(200);
        $res->assertJsonPath('data.id', $target->id);
        $res->assertJsonPath('data.email', $target->email);
    }

    public function testAdminGets404WhenUserDoesNotExist(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin, 'api')->getJson('/api/v1/users/999999')
            ->assertStatus(404);
    }
}
