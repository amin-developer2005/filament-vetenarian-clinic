<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    public array $roles {
        get {
            return [
                [
                    'name' => 'admin', 'description' => 'Admin User'
                ],
                [
                    'name' => 'owner', 'description' => 'Owner Vet'
                ],
                [
                    'name' => 'doctor', 'description' => 'Doctor Vet'
                ],
                [
                    'name' => 'staff', 'description' => 'Clinic Staff'
                ]
            ];
        }
    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
