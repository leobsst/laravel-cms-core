<?php

namespace Database\Factories;

use Leobsst\LaravelCmsCore\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Leobsst\LaravelCmsCore\Models\PageStat>
 */
class PageStatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::inRandomOrder()->first()->id,
            'ip' => $this->faker->ipv4,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'created_at' => $this->faker->dateTimeBetween('-2 month', 'now'),
        ];
    }
}
