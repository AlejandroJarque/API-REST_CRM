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
            'description' => fake()->sentence(),
            'client_id' => Client::factory(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Activity $activity) {
            if($activity->client && $activity->client->user_id) {
                $activity->user_id = $activity->client->user_id;
            }
        });
    }
}

?>