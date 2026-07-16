<?php

namespace App\Models;

use App\Enums\PanelRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinic extends Model
{
    /** @use HasFactory<\Database\Factories\ClinicFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'website',
        'description',
        'is_active',
    ];


    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ClinicAddress::class);
    }

    public function clinicSchedule(): HasMany
    {
        return $this->hasMany(ClinicSchedule::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function doctors(): BelongsToMany
    {
        return $this
            ->users()
            ->whereHas('roles', fn($query) => $query->where('name', PanelRole::DOCTOR));
    }

    public function activate(): bool
    {
        return $this->update([
            'is_active' => true,
        ]);
    }

    public function inactivate(): bool
    {
        return $this->update([
            'is_active' => false
        ]);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isNotActive(): bool
    {
        return ! $this->isActive();
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
