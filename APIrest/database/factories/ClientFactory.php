<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition() : array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->safeColorName(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'user_id' => User::factory(),
        ];
        
    }
}
?>