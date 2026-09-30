<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('##########'),
            'alternate_phone' => fake()->optional()->numerify('##########'),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'source' => fake()->randomElement(['website', 'referral', 'walk-in', 'social_media', 'advertisement']),
            'course_interested' => fake()->randomElement(['Lift & Escalator Safety Engineering', 'Advanced Elevator Technology', 'Diploma in Vertical Transportation']),
            'assigned_to' => null,
            'status' => fake()->randomElement([1, 2, 3, 4, 5]),
            'notes' => fake()->sentence(),
            'next_follow_up_at' => fake()->dateTimeBetween('now', '+2 weeks'),
            'created_by' => null,
        ];
    }
}
