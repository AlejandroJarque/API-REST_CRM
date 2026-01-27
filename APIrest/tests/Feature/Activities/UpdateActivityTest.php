<?php

namespace Tests\Feature\Activities;

use Test\Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Illumintate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase as TestsTestCase;

class UpdateActivityTest extends TestsTestCase
{
    use TestingRefreshDatabase;

    public function testGuestCannotUpsateActivity(): void
    {
        $client = Client::factory()->create();

        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'user_id' => $client->user_id,
            'description' => 'Old description',
        ]);

        $this->patchJson("/api/v1/activities/{$activity->id}", [
            'description' => 'New description',
        ])->assertStatus(401);
    }
}