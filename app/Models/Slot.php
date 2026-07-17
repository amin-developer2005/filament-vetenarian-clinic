<?php

namespace App\Models;

use App\Enums\SlotStatus;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Slot extends Model
{
    /** @use HasFactory<\Database\Factories\SlotFactory> */
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'status',
        'date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'status'   => SlotStatus::class,
        'date'    => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(Appointment::class);
    }

    public function free(): static
    {
        $this->update([
            'status' => SlotStatus::Available,
        ]);

        return $this;
    }

    public function book(): static
    {
        $this->update([
            'status' => SlotStatus::Booked,
        ]);

        return $this;
    }

    public function expire(): static
    {
        $this->update([
            'status' => SlotStatus::Expired,
        ]);

        return $this;
    }

    public function isBooked(): bool
    {
        return $this->status == SlotStatus::Booked;
    }

    public function isAvailable(): bool
    {
        return $this->status == SlotStatus::Available;
    }

    public function isExpired(): bool
    {
        return $this->status == SlotStatus::Expired;
    }

    public function scopeForUser($query, $user)
    {
        $clinicIds = $user->clinics()->pluck('clinics.id');

        return $query->whereHas('clinics', function ($query) use ($clinicIds) {
            return $query->whereIn('clinics.id', $clinicIds);
        });
    }


    public function getDurationAttribute(): string
    {
        return Carbon::parse($this->start)
            ->diff(Carbon::parse($this->end))
            ->format('%h hour %i min');
    }

}
