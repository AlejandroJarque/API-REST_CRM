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
            'user_id' => User::factory(),
            'client_id' => Client::factory(),
        ];
    }
}

?>