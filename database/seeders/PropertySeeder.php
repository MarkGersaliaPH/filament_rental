<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some users first if they don't exist
        if (User::count() == 0) {
            User::factory(10)->create();
        }

        // Create a variety of properties
        $this->createFeaturedProperties();
        $this->createRegularProperties();
        $this->createSpecialProperties();
    }

    private function createFeaturedProperties(): void
    {
        // Create 5 featured properties of different types
        Property::factory()->featured()->available()->create([
            'title' => 'Luxury Downtown Penthouse',
            'type' => 'penthouse',
            'city' => 'New York',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'rent_amount' => 8500.00,
        ]);

        Property::factory()->featured()->available()->create([
            'title' => 'Modern Waterfront Villa',
            'type' => 'villa',
            'city' => 'Miami',
            'bedrooms' => 4,
            'bathrooms' => 3,
            'rent_amount' => 6200.00,
        ]);

        Property::factory()->featured()->available()->create([
            'title' => 'Executive Studio Loft',
            'type' => 'studio',
            'city' => 'San Francisco',
            'bedrooms' => 0,
            'bathrooms' => 1,
            'rent_amount' => 3800.00,
        ]);

        Property::factory()->featured()->available()->furnished()->create([
            'title' => 'Designer Family House',
            'type' => 'house',
            'city' => 'Los Angeles',
            'bedrooms' => 4,
            'bathrooms' => 3,
            'rent_amount' => 5500.00,
        ]);

        Property::factory()->featured()->available()->petFriendly()->create([
            'title' => 'Pet-Friendly Garden Townhouse',
            'type' => 'townhouse',
            'city' => 'Austin',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'rent_amount' => 2800.00,
        ]);
    }

    private function createRegularProperties(): void
    {
        // Create 20 regular available properties
        Property::factory(15)->available()->create();
        
        // Create 5 furnished properties
        Property::factory(5)->available()->furnished()->create();
        
        // Create some pet-friendly properties
        Property::factory(8)->available()->petFriendly()->create();
    }

    private function createSpecialProperties(): void
    {
        // Create some rented properties
        Property::factory(5)->rented()->create();
        
        // Create some properties under maintenance
        Property::factory(2)->create([
            'status' => 'maintenance',
        ]);
        
        // Create some inactive properties
        Property::factory(3)->create([
            'status' => 'inactive',
        ]);
        
        // Create properties with different rent periods
        Property::factory(3)->available()->create([
            'rent_period' => 'weekly',
            'rent_amount' => 800.00,
            'minimum_stay_days' => 7,
        ]);
        
        Property::factory(2)->available()->create([
            'rent_period' => 'daily',
            'rent_amount' => 150.00,
            'minimum_stay_days' => 1,
            'maximum_stay_days' => 30,
        ]);
    }
}
