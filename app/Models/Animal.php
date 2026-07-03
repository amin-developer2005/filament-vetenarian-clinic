<?php

namespace App\Models;

use App\Enums\AnimalSpecies;
use App\Enums\AnimalGender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Animal extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'species',
        'breed',
        'gender',
        'date_of_birth',
        'microchip_number',
        'is_neutered',
        'avatar',
    ];

    protected $casts = [
        'species' => AnimalSpecies::class,
        'gender'  => AnimalGender::class,
        'date_of_birth' => 'date',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }

    public function clinic(): BelongsToMany
    {
        return $this->clinics();
    }

    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function isBelongsToOwner(User $owner): bool
    {
        return $this->getAttribute('owner_id') === $owner->getKey();
    }
}
