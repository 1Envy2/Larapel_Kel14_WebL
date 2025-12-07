<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $targetAmount = fake()->numberBetween(10000000, 500000000);

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(3),
            'target_amount' => $targetAmount,
            'collected_amount' => fake()->numberBetween(0, $targetAmount),
            'organizer_id' => User::whereHas('role', function ($query) {
                $query->where('name', 'Donor');
            })->inRandomOrder()->first()?->id ?? 1,
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'status' => fake()->randomElement(['active', 'completed', 'cancelled']),
            'story' => fake()->paragraph(5),
            'end_date' => fake()->dateTimeBetween('+1 month', '+6 months'),
        ];
    }
}
