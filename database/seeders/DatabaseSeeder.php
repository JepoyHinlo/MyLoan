<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LoanProduct;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Account
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@myloan.test',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'monthly_income' => 0,
        ]);

        // Staff Account
        User::create([
            'name' => 'Loan Officer Staff',
            'email' => 'staff@myloan.test',
            'username' => 'staff',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'monthly_income' => 0,
        ]);

        // Client Account
        User::create([
            'name' => 'John Client',
            'email' => 'client@myloan.test',
            'username' => 'client',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'work_status' => 'Employed',
            'monthly_income' => 25000,
        ]);

        // Default Loan Product Tier
        LoanProduct::create([
            'name' => 'Standard Personal Loan',
            'base_interest_rate' => 5.00,
            'returning_interest_rate' => 3.50,
            'max_term_months' => 12,
        ]);
    }
}