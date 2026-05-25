<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'start_date',
        'end_date',
        'days_of_week',
        'time_start',
        'time_end',
        'slot_duration',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }

    public function clinic(): BelongsToMany
    {
        return $this->clinics();
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class, 'schedule_id');
    }
}
