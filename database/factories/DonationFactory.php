<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => (string) Str::uuid(),
            'donor_id' => User::whereHas('role', function ($query) {
                $query->where('name', 'Donor');
            })->inRandomOrder()->first()?->id ?? 1,
            'campaign_id' => Campaign::inRandomOrder()->first()?->id ?? 1,
            'amount' => fake()->numberBetween(100000, 10000000),
            'payment_method_id' => PaymentMethod::inRandomOrder()->first()?->id ?? 1,
            'status' => fake()->randomElement(['pending', 'successful', 'failed']),
            'proof_image' => null,
            'message' => fake()->sentence(),
        ];
    }
}
