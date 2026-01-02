<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@kostan.local'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );
    }
}
