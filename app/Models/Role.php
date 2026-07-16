<?php

namespace App\Models;

use App\Enums\PanelRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_role',
            'role_id',
            'user_id'
        );
    }

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }

    public function clinic(): BelongsToMany
    {
        return $this->clinics();
    }

    protected function casts(): array
    {
        return [
            'name' => PanelRole::class,
        ];
    }
}
