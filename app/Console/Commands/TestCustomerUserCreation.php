<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class TestCustomerUserCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:customer-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test automatic user creation when customer is saved';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Customer -> User creation with Renter role...');
        $this->line('');
        
        // Check if Renter role exists
        $renterRole = Role::where('name', 'Renter')->first();
        if (!$renterRole) {
            $this->info('Renter role does not exist yet - it will be created automatically.');
        } else {
            $this->info('Renter role already exists: ' . $renterRole->description);
        }
        
        $this->line('');
        
        // Create a test customer
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe.test@example.com',
            'phone' => '+1-555-123-4567',
            'date_of_birth' => '1990-05-15',
            'address' => '123 Test Street, Test City, TC 12345',
            'is_active' => true,
        ]);
        
        $this->info('Created customer: ' . $customer->full_name . ' (' . $customer->email . ')');
        
        // Check if user was created
        if ($customer->user_id) {
            $user = $customer->user;
            $this->info('✅ User account created successfully!');
            $this->info('   - User ID: ' . $user->id);
            $this->info('   - User Name: ' . $user->name);
            $this->info('   - User Email: ' . $user->email);
            $this->info('   - Password: password (default)');
            
            // Check roles
            $roles = $user->roles->pluck('name')->toArray();
            $this->info('   - Roles: ' . implode(', ', $roles));
            
            if ($user->hasRole('Renter')) {
                $this->info('✅ Renter role assigned successfully!');
            } else {
                $this->error('❌ Renter role was not assigned!');
            }
            
        } else {
            $this->error('❌ User account was not created!');
        }
        
        $this->line('');
        $this->info('Test completed. Customer ID: ' . $customer->id);
        $this->info('You can now try logging in with:');
        $this->info('Email: ' . $customer->email);
        $this->info('Password: password');
    }
}
