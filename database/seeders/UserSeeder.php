<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'    => 'admin',
            'email'   => 'admin@outlook.com',
            'password' => Hash::make('Admin@4567'),
            'role_id' => Role::query()->where('name', 'admin')->first()->id,
        ]);
    }
}
