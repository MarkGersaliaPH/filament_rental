<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->randomElement([
            'Modern Downtown Apartment',
            'Luxury Villa with Pool',
            'Cozy Studio in City Center',
            'Spacious Family House',
            'Executive Penthouse Suite',
            'Charming Townhouse',
            'Contemporary Condo',
            'Designer Loft Space',
        ]);

        $type = $this->faker->randomElement(array_keys(Property::TYPES));
        $city = $this->faker->city;
        $availableFrom = $this->faker->dateTimeBetween('now', '+3 months');
        $rentAmount = $this->generateRentByType($type);

        return [
            'title' => $title,
            'description' => $this->generateDescription($type),
            'type' => $type,
            'status' => $this->faker->randomElement(array_keys(Property::STATUSES)),
            'slug' => Str::slug($title . '-' . $this->faker->unique()->randomNumber(5)),
            'bedrooms' => $this->generateBedroomsByType($type),
            'bathrooms' => $this->faker->numberBetween(1, 4),
            'area_sqft' => $this->faker->numberBetween(400, 3000),
            'area_sqm' => function (array $attributes) {
                return round($attributes['area_sqft'] * 0.092903, 2);
            },
            'floor_number' => $this->faker->optional(0.7)->numberBetween(1, 20),
            'total_floors' => function (array $attributes) {
                return $attributes['floor_number'] ? $this->faker->numberBetween($attributes['floor_number'], 25) : null;
            },
            'built_year' => $this->faker->numberBetween(1980, 2024),
            'furnished' => $this->faker->boolean(40),
            'pet_friendly' => $this->faker->boolean(30),
            'smoking_allowed' => $this->faker->boolean(15),
            'address' => $this->faker->streetAddress,
            'city' => $city,
            'state_province' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country' => 'US',
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'neighborhood' => $this->faker->optional(0.8)->randomElement([
                'Downtown', 'Midtown', 'Uptown', 'Westside', 'Eastside',
                'Historic District', 'Arts Quarter', 'Business District'
            ]),
            'rent_amount' => $rentAmount,
            'rent_period' => 'monthly',
            'security_deposit' => $rentAmount * $this->faker->randomFloat(1, 0.5, 2.0),
            'cleaning_fee' => $this->faker->optional(0.6)->randomFloat(2, 50, 300),
            'additional_fees' => $this->generateAdditionalFees(),
            'available_from' => $availableFrom,
            'available_until' => $this->faker->optional(0.3)->dateTimeBetween($availableFrom, '+2 years'),
            'minimum_stay_days' => $this->faker->randomElement([30, 60, 90, 180, 365]),
            'maximum_stay_days' => $this->faker->optional(0.4)->randomElement([365, 730, 1095]),
            'amenities' => $this->generateAmenities(),
            'appliances' => $this->generateAppliances(),
            'utilities_included' => $this->generateUtilities(),
            'images' => $this->generateImages(),
            'video_url' => $this->faker->optional(0.3)->url,
            'virtual_tour_url' => $this->faker->optional(0.2)->url,
            'owner_id' => User::factory(),
            'contact_name' => $this->faker->name,
            'contact_phone' => $this->faker->phoneNumber,
            'contact_email' => $this->faker->email,
            'meta_title' => $title . ' - ' . $city,
            'meta_description' => Str::limit($this->generateDescription($type), 160),
            'tags' => $this->generateTags($type),
            'is_featured' => $this->faker->boolean(20),
            'view_count' => $this->faker->numberBetween(0, 500),
        ];
    }

    private function generateRentByType(string $type): float
    {
        $ranges = [
            'studio' => [800, 1500],
            'apartment' => [1200, 2500],
            'condo' => [1500, 3500],
            'house' => [2000, 5000],
            'villa' => [3000, 8000],
            'townhouse' => [1800, 4000],
            'duplex' => [1600, 3500],
            'penthouse' => [4000, 12000],
        ];

        $range = $ranges[$type] ?? [1000, 3000];
        return $this->faker->randomFloat(2, $range[0], $range[1]);
    }

    private function generateBedroomsByType(string $type): int
    {
        $bedroomMap = [
            'studio' => 0,
            'apartment' => $this->faker->numberBetween(1, 3),
            'condo' => $this->faker->numberBetween(1, 3),
            'house' => $this->faker->numberBetween(2, 5),
            'villa' => $this->faker->numberBetween(3, 6),
            'townhouse' => $this->faker->numberBetween(2, 4),
            'duplex' => $this->faker->numberBetween(2, 4),
            'penthouse' => $this->faker->numberBetween(2, 5),
        ];

        return $bedroomMap[$type] ?? $this->faker->numberBetween(1, 3);
    }

    private function generateDescription(string $type): string
    {
        $descriptions = [
            'studio' => 'A beautifully designed studio featuring an open floor plan with modern finishes and excellent natural light.',
            'apartment' => 'Stylish apartment with contemporary amenities, perfect for urban living with easy access to city attractions.',
            'condo' => 'Elegant condominium offering luxury living with premium finishes and building amenities.',
            'house' => 'Spacious family home with a private yard, perfect for comfortable living in a quiet neighborhood.',
            'villa' => 'Luxurious villa featuring high-end finishes, private outdoor space, and premium amenities.',
            'townhouse' => 'Modern townhouse with multiple levels, private entrance, and attached garage.',
            'duplex' => 'Well-maintained duplex offering privacy and space with separate entrances.',
            'penthouse' => 'Exclusive penthouse with panoramic views, luxury finishes, and premium building amenities.',
        ];

        return $descriptions[$type] ?? 'Beautiful property with modern amenities and excellent location.';
    }

    private function generateAmenities(): array
    {
        $availableAmenities = array_keys(Property::AMENITIES);
        $count = $this->faker->numberBetween(3, 8);
        return $this->faker->randomElements($availableAmenities, $count);
    }

    private function generateAppliances(): array
    {
        $availableAppliances = array_keys(Property::APPLIANCES);
        $count = $this->faker->numberBetween(4, 7);
        return $this->faker->randomElements($availableAppliances, $count);
    }

    private function generateUtilities(): array
    {
        $utilities = ['electricity', 'water', 'gas', 'internet', 'cable', 'trash', 'sewer'];
        $count = $this->faker->numberBetween(2, 6);
        return $this->faker->randomElements($utilities, $count);
    }

    private function generateImages(): array
    {
        $imageCount = $this->faker->numberBetween(3, 8);
        $images = [];
        for ($i = 0; $i < $imageCount; $i++) {
            $images[] = 'https://picsum.photos/800/600?random=' . $this->faker->randomNumber(5);
        }
        return $images;
    }

    private function generateTags(string $type): array
    {
        $commonTags = ['modern', 'clean', 'quiet', 'safe', 'convenient'];
        $typeSpecificTags = [
            'studio' => ['efficient', 'minimalist', 'urban'],
            'apartment' => ['city-living', 'walkable', 'transit-friendly'],
            'condo' => ['luxury', 'amenities', 'security'],
            'house' => ['family-friendly', 'yard', 'parking'],
            'villa' => ['luxury', 'private', 'spacious'],
            'townhouse' => ['multi-level', 'garage', 'modern'],
            'duplex' => ['privacy', 'space', 'value'],
            'penthouse' => ['exclusive', 'views', 'luxury'],
        ];

        $tags = array_merge(
            $this->faker->randomElements($commonTags, $this->faker->numberBetween(2, 4)),
            $typeSpecificTags[$type] ?? []
        );

        return array_unique($tags);
    }

    private function generateAdditionalFees(): ?array
    {
        if ($this->faker->boolean(40)) {
            return null;
        }

        $fees = [];
        if ($this->faker->boolean(60)) {
            $fees['parking'] = $this->faker->randomFloat(2, 25, 150);
        }
        if ($this->faker->boolean(40)) {
            $fees['storage'] = $this->faker->randomFloat(2, 15, 75);
        }
        if ($this->faker->boolean(30)) {
            $fees['pet_fee'] = $this->faker->randomFloat(2, 25, 100);
        }

        return empty($fees) ? null : $fees;
    }

    // State variations
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
        ]);
    }

    public function rented(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rented',
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function furnished(): static
    {
        return $this->state(fn (array $attributes) => [
            'furnished' => true,
        ]);
    }

    public function petFriendly(): static
    {
        return $this->state(fn (array $attributes) => [
            'pet_friendly' => true,
        ]);
    }
}
