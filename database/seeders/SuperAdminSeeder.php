<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Check if company exists first
        $company = Company::where('email', 'admin@tbafinance.com')->first();

        if (!$company) {
            $company = Company::create([
                'name' => 'TBA Finance Cloud',
                'email' => 'admin@tbafinance.com',
                'phone' => '0700000000',
                'address' => 'Kampala, Uganda',
                'subscription_expiry' => now()->addYears(10),
                'is_active' => true,
            ]);
        }

        // ✅ Check if user exists first
        if (!User::where('email', 'admin@tbafinance.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@tbafinance.com',
                'password' => bcrypt('password123'),
                'company_id' => $company->id,
                'is_super_admin' => true,
            ]);
        }
    }
}