<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => $name = 'Admin',
            'is_admin' => 1,
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
        ]);

        $user->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', 'test-token-for-test-user'),
            'abilities' => ['*'],
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'user@gmail.com',
            'password' => bcrypt('user'),
        ]);
    }
}
