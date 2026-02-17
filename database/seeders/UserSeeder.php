<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator Prodi Telkom',
            'email' => 'proditelkom', // Using email field to store username
            'password' => Hash::make('proditelkom123'),
            'email_verified_at' => now(),
        ]);
    }
} 