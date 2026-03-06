<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Kasir User',
            'email' => 'kasir@example.com',
            'role' => 'kasir',
        ]);

        User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@example.com',
            'role' => 'owner',
        ]);
    }
}
