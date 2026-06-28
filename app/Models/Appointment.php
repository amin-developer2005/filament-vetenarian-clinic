<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'slot_id',
        'animal_id',
        'owner_id',
        'status',
        'description',
    ];

    protected $casts = [
        'status' => AppointmentStatus::class,
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function schedule()
    {
        return $this->hasOneThrough(Schedule::class, Slot::class);
    }

    public function updateSlot(Slot $slot)
    {
        if ($this->slot_id === $slot->id) {
            return;
        }

        $slot = $slot->refresh();

        if ($slot->isBooked() || $slot->appointment()->exists()) {
            throw new \Exception(__('resources/appointments.notifications.slot_already_booked'));
        }

        $this->slot()->associate($slot)->save();

        $slot->book();
    }

    public function isBelongsToDoctor(Authenticatable|User $doctor): bool
    {
        return $this->slot->schedule->doctor->id === $doctor->id;
    }

    public function isBelongsToOwner(Authenticatable|User $owner)
    {
        return $this->owner_id === $owner->id;
    }

    public function confirm(): void
    {
        $this->update([
            'status' => AppointmentStatus::Confirmed,
        ]);
    }

    public function checkIn(): void
    {
        $this->changeStatusTo(AppointmentStatus::CheckedIn);
    }

    public function startVisit(): void
    {
        $this->changeStatusTo(AppointmentStatus::InProgress);
    }

    public function complete(): void
    {
        $this->update([
            'status' => AppointmentStatus::Completed,
        ]);
    }

    public function cancel(): void
    {
        $this->update([
            'status' => AppointmentStatus::Cancelled,
        ]);
    }

    public function reject(): void
    {
        $this->update([
            'status' => AppointmentStatus::Rejected
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === AppointmentStatus::Pending;
    }

    public function isConfirmed(): bool
    {
        return $this->status === AppointmentStatus::Confirmed;
    }

    public function isCheckedIn(): bool
    {
        return $this->status === AppointmentStatus::CheckedIn;
    }

    public function isInProgress(): bool
    {
        return $this->status === AppointmentStatus::InProgress;
    }

    public function isCompleted(): bool
    {
        return $this->status === AppointmentStatus::Completed;
    }

    public function isRejected(): bool
    {
        return $this->status === AppointmentStatus::Rejected;
    }

    public function isCancelled(): bool
    {
        return $this->status === AppointmentStatus::Cancelled;
    }

    public function isNoShow(): bool
    {
        return $this->status === AppointmentStatus::NoShow;
    }

    public function canBeConfirmedBy(Authenticatable|User $user): bool
    {
        if ($this->isPending()) {
            if ($user->isAdmin()) return true;
            if ($user->isDoctor() && $user->id === $this->slot?->schedule?->doctor_id) return true;
        }

        return false;
    }

    public function canBeRejectedBy(Authenticatable|User $user): bool
    {
        return $this->canBeConfirmedBy($user);
    }

    public function canBeCheckedInBy(Authenticatable|User $user): bool
    {
        if ($this->isConfirmed()) {
            if ($user->isAdmin() || $user->isReceptionist()) {
                return true;
            }
        }

        return false;
    }

    public function canBeStartedVisitingBy(Authenticatable|User $user): bool
    {
        if ($this->isCheckedIn()) {

            if ($user->isAdmin()) {
                return true;
            }

            if ($user->isDoctor() && $user->id === $this->slot?->schedule?->doctor_id) {
                return true;
            }
        }

        return false;
    }

    public function canBeCompletedBy(Authenticatable|User $user): bool
    {
        if ($this->isInProgress()) {
            if ($user->isAdmin()) return true;
            if ($user->isDoctor() && $user->id === $this->slot?->schedule?->doctor_id) {
                return true;
            }
        }

        return false;
    }

    public function canBeCanceledBy(Authenticatable|User $user): bool
    {
        if ($this->isPending() || $this->isConfirmed()) {
            if ($user->isOwner() && $this->isBelongsToOwner($user)) {
                return true;
            }
        }

        return false;
    }

    public function changeStatusTo(AppointmentStatus $status): void
    {
        $this->update([
            'status' => $status,
        ]);
    }

}
