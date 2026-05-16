<?php

namespace App\Models;

use App\Enums\LiveStockType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LiveStock extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'type',
        'date_of_birth',
        'avatar',
    ];

    protected $casts = [
        'type' => LiveStockType::class
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

    public function isBelongsToOwner(User $owner): bool
    {
        return $this->getAttribute('owner_id') === $owner->getKey();
    }
}
