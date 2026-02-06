<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'status' => Activity::STATUS_PENDING ?? 'pending',
            'date' => now()->toDateString(),
            'description' => fake()->sentence(),
            'client_id' => Client::factory(),
        ];
    }

    public function configure():static
    {
        return $this->afterMaking(function (Activity $activity) {
            if($activity->client && $activity->client->user_id) {
                $activity->user_id = $activity->client->user_id;
            }
        });
    }

    public function done(): static
    {
        return $this->state(fn () => [
            'status' => Activity::STATUS_DONE ?? 'done',
            'completed_at' => now(),
        ]);
    }
}

?>