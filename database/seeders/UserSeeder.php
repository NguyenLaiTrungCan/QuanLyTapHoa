<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@taphoa.test'],
            [
                'name' => 'Admin',
                'phone' => '0900000000',
                'address' => 'TP. Hồ Chí Minh',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@taphoa.test'],
            [
                'name' => 'Customer Demo',
                'phone' => '0911111111',
                'address' => 'Hà Nội',
                'password' => Hash::make('password'),
            ]
        );
    }
}