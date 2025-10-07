<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Enums\LogType;
use Leobsst\LaravelCmsCore\Models\User;

/**
 * @extends Factory
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(LogType::cases())->value,
            'message' => fake()->sentence(),
            'creator_id' => fake()->boolean() ? fake()->randomElement(User::all())->id : null,
            'ip_address' => fake()->boolean() ? fake()->ipv4 : null,
            'data' => fake()->boolean() ? json_encode(fake()->words) : null,
            'status' => fake()->randomElement(LogStatus::cases())->value,
        ];
    }
}
