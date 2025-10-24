<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Muhammad Rizky',
            'email' => 'rizky@gmail.com',
            'password' => Hash::make("password"),
            'role' => 'requestor',
        ]);
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make("password"),
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'Approver',
            'email' => 'approver@gmail.com',
            'password' => Hash::make("password"),
            'role' => 'approver',
        ]);
    }
}
