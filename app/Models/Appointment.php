<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'slot_id',
        'vet_id',
        'description',
    ];

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }

    public function clinic(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }
}
