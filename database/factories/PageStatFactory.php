<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;

/**
 * @extends Factory<\Leobsst\LaravelCmsCore\Models\Features\Pages\PageStat>
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
