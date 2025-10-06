<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            // VIP/Premium Customers
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'email' => 'maria.santos@email.com',
                'email_verified_at' => now()->subDays(150),
                'phone' => '+63 917 123 4567',
                'date_of_birth' => Carbon::parse('1985-03-15'),
                'address' => '123 Makati Avenue, Makati City, Metro Manila 1200, Philippines',
                'is_active' => true,
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Dela Cruz',
                'email' => 'john.delacruz@gmail.com',
                'email_verified_at' => now()->subDays(200),
                'phone' => '+63 918 234 5678',
                'date_of_birth' => Carbon::parse('1990-07-22'),
                'address' => '456 Ortigas Center, Pasig City, Metro Manila 1605, Philippines',
                'is_active' => true,
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@yahoo.com',
                'email_verified_at' => now()->subDays(120),
                'phone' => '+63 919 345 6789',
                'date_of_birth' => Carbon::parse('1992-11-08'),
                'address' => '789 BGC Central, Taguig City, Metro Manila 1634, Philippines',
                'is_active' => true,
            ],

            // Regular Active Customers
            [
                'first_name' => 'Michael',
                'last_name' => 'Garcia',
                'email' => 'michael.garcia@hotmail.com',
                'email_verified_at' => now()->subDays(90),
                'phone' => '+63 920 456 7890',
                'date_of_birth' => Carbon::parse('1988-05-12'),
                'address' => '321 Quezon Avenue, Quezon City, Metro Manila 1104, Philippines',
                'is_active' => true,
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'Reyes',
                'email' => 'ana.reyes@outlook.com',
                'email_verified_at' => now()->subDays(75),
                'phone' => '+63 921 567 8901',
                'date_of_birth' => Carbon::parse('1995-09-30'),
                'address' => '654 España Boulevard, Manila City, Metro Manila 1015, Philippines',
                'is_active' => true,
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Chen',
                'email' => 'robert.chen@gmail.com',
                'email_verified_at' => now()->subDays(60),
                'phone' => '+63 922 678 9012',
                'date_of_birth' => Carbon::parse('1987-12-03'),
                'address' => '987 Alabang Town Center, Muntinlupa City, Metro Manila 1770, Philippines',
                'is_active' => true,
            ],

            // New Customers (Recently Registered)
            [
                'first_name' => 'Jessica',
                'last_name' => 'Martinez',
                'email' => 'jessica.martinez@email.com',
                'email_verified_at' => now()->subDays(15),
                'phone' => '+63 923 789 0123',
                'date_of_birth' => Carbon::parse('1993-04-18'),
                'address' => '147 Eastwood City, Quezon City, Metro Manila 1110, Philippines',
                'is_active' => true,
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Brown',
                'email' => 'david.brown@proton.me',
                'email_verified_at' => now()->subDays(8),
                'phone' => '+63 924 890 1234',
                'date_of_birth' => Carbon::parse('1991-08-25'),
                'address' => '258 Greenhills Shopping Center, San Juan City, Metro Manila 1502, Philippines',
                'is_active' => true,
            ],

            // Customers from Different Regions
            [
                'first_name' => 'Carmen',
                'last_name' => 'Rodriguez',
                'email' => 'carmen.rodriguez@gmail.com',
                'email_verified_at' => now()->subDays(45),
                'phone' => '+63 32 234 5678',
                'date_of_birth' => Carbon::parse('1989-01-14'),
                'address' => '369 IT Park, Lahug, Cebu City, Cebu 6000, Philippines',
                'is_active' => true,
            ],
            [
                'first_name' => 'Jose',
                'last_name' => 'Villanueva',
                'email' => 'jose.villanueva@yahoo.com',
                'email_verified_at' => now()->subDays(30),
                'phone' => '+63 82 345 6789',
                'date_of_birth' => Carbon::parse('1986-10-07'),
                'address' => '741 Abreeza Mall, J.P. Laurel Avenue, Davao City, Davao del Sur 8000, Philippines',
                'is_active' => true,
            ],

            // Young Customers (Gen Z)
            [
                'first_name' => 'Ashley',
                'last_name' => 'Lim',
                'email' => 'ashley.lim@student.edu.ph',
                'email_verified_at' => now()->subDays(25),
                'phone' => '+63 925 901 2345',
                'date_of_birth' => Carbon::parse('2001-06-12'),
                'address' => '852 UP Town Center, Katipunan Avenue, Quezon City, Metro Manila 1108, Philippines',
                'is_active' => true,
            ],
            [
                'first_name' => 'Kyle',
                'last_name' => 'Torres',
                'email' => 'kyle.torres@college.edu.ph',
                'email_verified_at' => now()->subDays(12),
                'phone' => '+63 926 012 3456',
                'date_of_birth' => Carbon::parse('2000-02-28'),
                'address' => '963 Ateneo de Manila University, Loyola Heights, Quezon City, Metro Manila 1108, Philippines',
                'is_active' => true,
            ],

            // Senior Customers
            [
                'first_name' => 'Elena',
                'last_name' => 'Fernandez',
                'email' => 'elena.fernandez@senior.com',
                'email_verified_at' => now()->subDays(180),
                'phone' => '+63 927 123 4567',
                'date_of_birth' => Carbon::parse('1960-05-20'),
                'address' => '159 Heritage Park, Fort Bonifacio, Taguig City, Metro Manila 1634, Philippines',
                'is_active' => true,
            ],

            // International Customers
            [
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'email' => 'james.wilson@international.com',
                'email_verified_at' => now()->subDays(40),
                'phone' => '+1 555 234 5678',
                'date_of_birth' => Carbon::parse('1984-09-16'),
                'address' => '456 Expat Village, Makati City, Metro Manila 1229, Philippines',
                'is_active' => true,
            ],
            [
                'first_name' => 'Yuki',
                'last_name' => 'Tanaka',
                'email' => 'yuki.tanaka@japan.co.jp',
                'email_verified_at' => now()->subDays(55),
                'phone' => '+81 90 1234 5678',
                'date_of_birth' => Carbon::parse('1990-03-08'),
                'address' => '789 Little Tokyo, Makati City, Metro Manila 1200, Philippines',
                'is_active' => true,
            ],

            // Customers with Missing Information (Testing Edge Cases)
            [
                'first_name' => 'Guest',
                'last_name' => 'User',
                'email' => 'guest.user@temp.com',
                'email_verified_at' => null, // Not verified
                'phone' => null, // No phone
                'date_of_birth' => null, // No birthday
                'address' => null, // No address
                'is_active' => true,
            ],
            [
                'first_name' => 'Partial',
                'last_name' => 'Info',
                'email' => 'partial.info@example.com',
                'email_verified_at' => now()->subDays(5),
                'phone' => '+63 928 345 6789',
                'date_of_birth' => null, // No birthday
                'address' => 'Incomplete Address',
                'is_active' => true,
            ],

            // Inactive Customer (Testing Status)
            [
                'first_name' => 'Inactive',
                'last_name' => 'Customer',
                'email' => 'inactive.customer@disabled.com',
                'email_verified_at' => now()->subDays(300),
                'phone' => '+63 929 456 7890',
                'date_of_birth' => Carbon::parse('1985-11-11'),
                'address' => '000 Disabled Account Street, Test City, Test Province 0000, Philippines',
                'is_active' => false, // Inactive customer
            ],

            // Business Customers
            [
                'first_name' => 'Corporate',
                'last_name' => 'Buyer',
                'email' => 'corporate.buyer@business.com',
                'email_verified_at' => now()->subDays(100),
                'phone' => '+63 2 8123 4567',
                'date_of_birth' => Carbon::parse('1975-04-01'),
                'address' => 'Suite 1001, One Corporate Center, Ortigas Avenue, Pasig City, Metro Manila 1605, Philippines',
                'is_active' => true,
            ],
            [
                'first_name' => 'Small',
                'last_name' => 'Business',
                'email' => 'small.business@startup.ph',
                'email_verified_at' => now()->subDays(65),
                'phone' => '+63 917 987 6543',
                'date_of_birth' => Carbon::parse('1988-07-15'),
                'address' => 'Unit 205, Startup Hub, Bonifacio Global City, Taguig City, Metro Manila 1634, Philippines',
                'is_active' => true,
            ],

            // Customers for Testing Edge Cases
            [
                'first_name' => 'Very',
                'last_name' => 'LongLastNameForTestingPurposesOnly',
                'email' => 'very.long.email.address.for.testing@verylongdomainname.com',
                'email_verified_at' => now()->subDays(35),
                'phone' => '+63 930 111 2222',
                'date_of_birth' => Carbon::parse('1995-12-31'),
                'address' => 'Very Long Address Line For Testing Purposes, Building Name, Street Name, Subdivision Name, Barangay Name, City Name, Province Name, Postal Code 1234, Philippines',
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        $this->command->info('Customers seeded successfully!');
        $this->command->info('Created ' . count($customers) . ' customers with diverse profiles.');
        $this->command->info('Includes: VIP customers, new customers, regional customers, international customers, business customers, and edge cases.');
    }
}
