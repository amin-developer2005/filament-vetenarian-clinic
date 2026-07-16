<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Illuminate\Contracts\Auth\Authenticatable;

class AppointmentService
{
    use CanUseDatabaseTransactions;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function confirm(Appointment $appointment): bool
    {
        if (! $this->canBeConfirmed($appointment)) {
            return false;
        }

        $this->beginDatabaseTransaction();

        $result = $appointment->changeStatusTo(AppointmentStatus::Confirmed);

        $this->commitDatabaseTransaction();

        return $result;
    }

    public function checkIn(Appointment $appointment): bool
    {
        if (! $this->canBeCheckedIn($appointment)) {
            return false;
        }

        $this->beginDatabaseTransaction();

        $result = $appointment->changeStatusTo(AppointmentStatus::CheckedIn);

        $this->commitDatabaseTransaction();

        return $result;
    }

    public function startVisit(Appointment $appointment): bool
    {
        if (! $this->canBeStartedVisiting($appointment)) {
            return false;
        }

        $this->beginDatabaseTransaction();

        $result = $appointment->changeStatusTo(AppointmentStatus::InProgress);

        $this->commitDatabaseTransaction();

        return $result;
    }

    public function complete(Appointment $appointment): bool
    {
        if (! $this->canBeCompleted($appointment)) {
            return false;
        }

        $this->beginDatabaseTransaction();

        $result = $appointment->changeStatusTo(AppointmentStatus::Completed);

        $this->commitDatabaseTransaction();

        return $result;
    }

    public function cancel(Appointment $appointment): bool
    {
        if (! $this->canBeCanceled($appointment)) {
            return false;
        }

        $this->beginDatabaseTransaction();

        $result = $appointment->changeStatusTo(AppointmentStatus::Cancelled);

        $this->commitDatabaseTransaction();

        return $result;
    }

    public function reject(Appointment $appointment): bool
    {
        if (! $this->canBeRejected($appointment)) {
            return false;
        }

        $this->beginDatabaseTransaction();

        $result = $appointment->changeStatusTo(AppointmentStatus::Rejected);

        $this->commitDatabaseTransaction();

        return $result;
    }


    public function canBeConfirmed(Appointment $appointment): bool
    {
        if ($appointment->isPending()) {
            return true;
        }

        return false;
    }

    public function canBeRejected(Appointment $appointment): bool
    {
        return $this->canBeConfirmed($appointment);
    }

    public function canBeCheckedIn(Appointment $appointment): bool
    {
        if ($appointment->isConfirmed()) {
            return true;
        }

        return false;
    }

    public function canBeStartedVisiting(Appointment $appointment): bool
    {
        if ($appointment->isCheckedIn()) {
            return true;
        }

        return false;
    }

    public function canBeCompleted(Appointment $appointment): bool
    {
        if ($appointment->isInProgress()) {
            return true;
        }

        return false;
    }

    public function canBeCanceled(Appointment $appointment): bool
    {
        if ($appointment->isPending()) {
            return true;
        }

        return false;
    }


}
