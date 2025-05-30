<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Speaker;
use App\Models\Talk;

class SpeakerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Speaker::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $qualificationsCount = $this->faker->numberBetween(0, count(Speaker::QUALIFICATIONS));
        $qualifications = $this->faker->randomElements(array_keys(Speaker::QUALIFICATIONS), $qualificationsCount);  
        
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'bio' => fake()->text(),
            'qualifications' => [],
            'qualifications' => $qualifications,
            'twitter_handle' => fake()->word(),
        ];
    }

    public function withTalks(int $count = 1): self
    {
        return $this->has(Talk::factory()->count($count),'talks');
    }
}
