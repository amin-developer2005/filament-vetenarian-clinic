<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use App\Enums\SlotStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slot extends Model
{
    /** @use HasFactory<\Database\Factories\SlotFactory> */
    use HasFactory;

    protected $fillable = [
        'status',
        'date',
        'start',
        'end',
        'owner_id',
    ];

    protected $casts = [
        'status'   => SlotStatus::class,
        'date'     => 'datetime',
        'start'    => 'datetime:H:i',
        'end'    => 'datetime:H:i',
    ];


    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }

    public function clinic(): BelongsToMany
    {
        return $this->clinics();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function confirm(): void
    {
        $this->forceFill([
            'status' => SlotStatus::Confirmed,
        ])->save();
    }

    public function cancel(): void
    {
        $this->forceFill([
            'status' => SlotStatus::Cancelled,
        ])->save();
    }

    public function isCreated(): bool
    {
        return $this->status == SlotStatus::Created;
    }

    public function isConfirmed(): bool
    {
        return $this->status === SlotStatus::Confirmed;
    }

    public function isCancelled(): bool
    {
        return $this->status === SlotStatus::Cancelled;
    }

    public function isNotConfirmed(): bool
    {
        return $this->status !== SlotStatus::Confirmed;
    }

    public function isNotCancelled(): bool
    {
        return $this->status !== SlotStatus::Cancelled;
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
