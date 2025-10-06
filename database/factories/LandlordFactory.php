<?php

namespace Database\Factories;

use App\Models\Landlord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Landlord>
 */
class LandlordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $hasCompany = $this->faker->boolean(30); // 30% chance of having a company
        
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'date_of_birth' => $this->faker->dateTimeBetween('-70 years', '-25 years'),
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state_province' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country' => $this->faker->randomElement(['US', 'CA', 'GB']),
            'company_name' => $hasCompany ? $this->faker->company : null,
            'business_type' => $hasCompany ? $this->faker->randomElement(['company', 'llc']) : 'individual',
            'tax_id' => $hasCompany ? $this->faker->numerify('##-#######') : null,
            'bank_name' => $this->faker->randomElement([
                'Bank of America', 'Chase Bank', 'Wells Fargo', 'Citibank', 
                'PNC Bank', 'TD Bank', 'US Bank', 'Capital One'
            ]),
            'account_holder_name' => $hasCompany ? null : "{$firstName} {$lastName}",
            'is_verified' => $this->faker->boolean(70), // 70% verified
            'is_active' => $this->faker->boolean(90), // 90% active
            'verified_at' => function (array $attributes) {
                return $attributes['is_verified'] ? $this->faker->dateTimeBetween('-2 years', 'now') : null;
            },
            'contact_hours_start' => $this->faker->time('H:i:s', '10:00:00'),
            'contact_hours_end' => $this->faker->time('H:i:s', '18:00:00'),
            'preferred_contact_methods' => $this->faker->randomElements(
                ['email', 'phone', 'sms', 'app'], 
                $this->faker->numberBetween(1, 3)
            ),
            'self_managed' => $this->faker->boolean(80), // 80% self-managed
            'property_manager_name' => function (array $attributes) {
                return !$attributes['self_managed'] ? $this->faker->name : null;
            },
            'property_manager_phone' => function (array $attributes) {
                return !$attributes['self_managed'] ? $this->faker->phoneNumber : null;
            },
            'average_rating' => $this->faker->randomFloat(2, 3.0, 5.0),
            'total_properties' => $this->faker->numberBetween(1, 25),
            'total_reviews' => $this->faker->numberBetween(0, 50),
            'profile_photo' => null, // Will be handled separately if needed
            'bio' => $this->faker->optional(0.6)->paragraph(3),
            'admin_notes' => $this->faker->optional(0.1)->sentence,
        ];
    }

    // State variations
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
            'verified_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => false,
            'verified_at' => null,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withCompany(): static
    {
        return $this->state(fn (array $attributes) => [
            'company_name' => $this->faker->company,
            'business_type' => $this->faker->randomElement(['company', 'llc']),
            'tax_id' => $this->faker->numerify('##-#######'),
        ]);
    }

    public function individual(): static
    {
        return $this->state(fn (array $attributes) => [
            'company_name' => null,
            'business_type' => 'individual',
            'tax_id' => null,
        ]);
    }

    public function highRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'average_rating' => $this->faker->randomFloat(2, 4.5, 5.0),
            'total_reviews' => $this->faker->numberBetween(25, 100),
        ]);
    }
}
