<?php

use App\Models\Role;

return [
    'relations' => [
        'related' => Role::class,
        'name' => 'model',
        'table' => 'user_role',
        'foreign_pivot_key' => 'user_id',
        'related_pivot_key' => 'role_id',
    ],
];
