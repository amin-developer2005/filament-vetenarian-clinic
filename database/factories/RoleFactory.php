<?php

namespace Database\Factories;

use App\Enums\PanelRole;
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
                    'name' => PanelRole::ADMIN, 'description' => 'Admin User'
                ],
                [
                    'name' => PanelRole::Owner, 'description' => 'Owner LiveStock'
                ],
                [
                    'name' => PanelRole::DOCTOR, 'description' => 'Doctor LiveStock'
                ],
                [
                    'name' => PanelRole::Staff, 'description' => 'Clinic Staff'
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
