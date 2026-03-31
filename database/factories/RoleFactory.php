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
                    'name' => 'owner', 'description' => 'Owner Pet'
                ],
                [
                    'name' => 'doctor', 'description' => 'Doctor Pet'
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
