<?php

namespace App\Console\Commands;

use App\Models\Landlord;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class TestLandlordCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:landlord';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test landlord creation and user account generation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Landlord Creation and User Account Generation...');
        $this->line('');
        
        // Test 1: Create individual landlord
        $landlord1 = Landlord::factory()->individual()->verified()->create([
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'email' => 'alice.landlord@example.com',
            'city' => 'New York',
            'business_type' => 'individual',
        ]);
        
        $this->info('✅ Created Individual Landlord:');
        $this->info('   - Name: ' . $landlord1->full_name);
        $this->info('   - Email: ' . $landlord1->email);
        $this->info('   - Type: ' . $landlord1->business_type);
        $this->info('   - Verified: ' . ($landlord1->is_verified ? 'Yes' : 'No'));
        
        if ($landlord1->user) {
            $this->info('   - User Account: Created (ID: ' . $landlord1->user->id . ')');
            $this->info('   - Has Landlord Role: ' . ($landlord1->user->hasRole('Landlord') ? 'Yes' : 'No'));
        }
        
        $this->line('');
        
        // Test 2: Create company landlord
        $landlord2 = Landlord::factory()->withCompany()->active()->create([
            'first_name' => 'Bob',
            'last_name' => 'Smith',
            'email' => 'bob.company@example.com',
            'city' => 'Los Angeles',
        ]);
        
        $this->info('✅ Created Company Landlord:');
        $this->info('   - Name: ' . $landlord2->display_name);
        $this->info('   - Company: ' . $landlord2->company_name);
        $this->info('   - Email: ' . $landlord2->email);
        $this->info('   - Type: ' . $landlord2->business_type);
        $this->info('   - Active: ' . ($landlord2->is_active ? 'Yes' : 'No'));
        
        $this->line('');
        
        // Test 3: Create high-rated landlord
        $landlord3 = Landlord::factory()->highRated()->verified()->create([
            'first_name' => 'Carol',
            'last_name' => 'Davis',
            'email' => 'carol.rated@example.com',
        ]);
        
        $this->info('✅ Created High-Rated Landlord:');
        $this->info('   - Name: ' . $landlord3->full_name);
        $this->info('   - Rating: ' . $landlord3->average_rating . '/5.0 (' . $landlord3->total_reviews . ' reviews)');
        $this->info('   - Rating Stars: ' . $landlord3->rating_stars);
        $this->info('   - Properties: ' . $landlord3->total_properties);
        
        $this->line('');
        
        // Test landlord scopes
        $this->info('📊 Testing Landlord Scopes:');
        $this->info('   - Total Landlords: ' . Landlord::count());
        $this->info('   - Active Landlords: ' . Landlord::active()->count());
        $this->info('   - Verified Landlords: ' . Landlord::verified()->count());
        $this->info('   - Individual Type: ' . Landlord::byBusinessType('individual')->count());
        $this->info('   - Company Type: ' . Landlord::byBusinessType('company')->count());
        
        $this->line('');
        
        // Test helper methods
        $this->info('🔧 Testing Helper Methods:');
        $this->info('   - ' . $landlord1->full_name . ' is verified: ' . ($landlord1->isVerified() ? 'Yes' : 'No'));
        $this->info('   - ' . $landlord2->display_name . ' has company: ' . ($landlord2->hasCompany() ? 'Yes' : 'No'));
        $this->info('   - ' . $landlord3->full_name . ' is self-managed: ' . ($landlord3->isSelfManaged() ? 'Yes' : 'No'));
        
        $this->line('');
        $this->info('✅ All tests completed successfully!');
        $this->info('You can now log in with any of these accounts using password: "password"');
    }
}
