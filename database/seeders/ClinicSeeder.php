<?php

namespace Database\Seeders;

use App\Enums\PanelRole;
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

        $user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', PanelRole::ADMIN)->orWhere('name', PanelRole::DOCTOR);
        })->first();

        $clinic->users()->attach($user->id);

        foreach (Role::query()->get() as $role) {
            $clinic->roles()->attach($role->id);
        }
    }
}
