<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $clinic = Clinic::factory()->create([
                    'name' => 'Clinic 1',
                ]);

        $role = Role::query()->where('name', 'admin')->first();
        $user = User::query()->where('role_id', $role->id)->first();

        $clinic->users()->attach($user->id);
    }
}
