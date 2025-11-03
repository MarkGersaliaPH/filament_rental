<?
namespace App\Services;

class UserAccountService
{
    public static function createUserAccount(string $role): void{
 // Check if customer already has a user account
        if ($this->user_id) {
            return;
        }

        // Check if a user with this email already exists
        $existingUser = User::where('email', $this->email)->first();
        if ($existingUser) {
            // Link existing user to this customer
            $this->update(['user_id' => $existingUser->id]);
            
            // Assign Renter role if not already assigned
            if (!$existingUser->hasRole('Renter')) {
                $existingUser->assignRole('Renter');
            }
            return;
        }

        // Create new user account
        $user = User::create([
            'name' => $this->full_name,
            'email' => $this->email,
            'password' => Hash::make('password'), // Default password
            'email_verified_at' => now(), // Auto-verify email for customers
        ]);

        // Link user to customer
        $this->update(['user_id' => $user->id]);

        // Ensure Renter role exists
        $renterRole = Role::firstOrCreate(
            ['name' => 'Renter'],
            ['description' => 'Customer with rental access to the system']
        );

        // Assign Renter role to user
        $user->assignRole($role);
    }
}