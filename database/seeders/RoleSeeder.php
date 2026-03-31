<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    protected RoleFactory $roleFactory {
        set => $this->roleFactory = $value;
        get => $this->roleFactory;
    }

    public function __construct(RoleFactory $roleFactory)
    {
        $this->roleFactory = $roleFactory;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect($this->roleFactory->roles)->each(
            fn (array $data) => Role::query()->create($data)
        );
    }
}
