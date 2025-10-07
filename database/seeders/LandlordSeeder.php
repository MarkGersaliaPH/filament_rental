<?php

namespace Database\Seeders;

use App\Models\Landlord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandlordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing landlords if needed (be careful in production)
        // DB::table('landlords')->truncate();
        
        $this->command->info('Creating landlords...');
        
        // Create a variety of landlords with different characteristics
        
        // 1. Create 5 high-rated, verified landlords with companies
        $this->command->info('Creating high-rated company landlords...');
        Landlord::factory()
            ->count(5)
            ->verified()
            ->active()
            ->withCompany()
            ->highRated()
            ->create();
            
        // 2. Create 10 individual verified landlords
        $this->command->info('Creating verified individual landlords...');
        Landlord::factory()
            ->count(10)
            ->verified()
            ->active()
            ->individual()
            ->create();
            
        // 3. Create 8 unverified but active landlords (new signups)
        $this->command->info('Creating new unverified landlords...');
        Landlord::factory()
            ->count(8)
            ->unverified()
            ->active()
            ->create();
            
        // 4. Create 3 inactive landlords (for testing inactive states)
        $this->command->info('Creating inactive landlords...');
        Landlord::factory()
            ->count(3)
            ->inactive()
            ->create();
            
        // 5. Create some specific landlords for testing
        $this->command->info('Creating specific test landlords...');
        
        // A premium landlord with excellent ratings
        Landlord::factory()
            ->verified()
            ->active()
            ->withCompany()
            ->state([
                'first_name' => 'Premium',
                'last_name' => 'Properties',
                'email' => 'premium@landlords.com',
                'company_name' => 'Premium Properties LLC', 
                'average_rating' => 4.95,
                'total_properties' => 50,
                'total_reviews' => 125,
                'bio' => 'We are a premium property management company with over 10 years of experience in providing exceptional rental experiences.',
            ])
            ->create();
            
        // A new individual landlord
        Landlord::factory()
            ->unverified()
            ->active()
            ->individual()
            ->state([
                'first_name' => 'John',
                'last_name' => 'NewLandlord', 
                'email' => 'john.new@example.com',
                'total_properties' => 1,
                'total_reviews' => 0,
                'average_rating' => 0.0,
                'bio' => 'New to property rental, looking forward to providing great service.',
            ])
            ->create();
            
        // A landlord with mixed reviews
        Landlord::factory()
            ->verified()
            ->active()
            ->state([
                'first_name' => 'Average',
                'last_name' => 'Landlord',
                'email' => 'average@example.com',
                'average_rating' => 3.2,
                'total_properties' => 8,
                'total_reviews' => 24,
            ])
            ->create();
            
        $totalLandlords = Landlord::count();
        $this->command->info("Successfully created {$totalLandlords} landlords!");
        
        // Display some statistics
        $verified = Landlord::where('is_verified', true)->count(); 
        $highRated = Landlord::where('average_rating', '>=', 4.0)->count();
        
        $this->command->table(
            ['Metric', 'Count'],
            [
                ['Total Landlords', $totalLandlords],
                ['Verified', $verified],
                ['Companies/LLCs', $companies],
                ['High Rated (4.0+)', $highRated],
            ]
        );
    }
}
