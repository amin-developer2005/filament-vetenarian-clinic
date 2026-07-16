<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin() || ($user->isOwner() && $appointment->isBelongsToOwner($user));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin() || ($user->isOwner() && $appointment->isBelongsToOwner($user));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin() || ($user->isOwner() && $appointment->isBelongsToOwner($user));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin() || ($user->isOwner() && $appointment->isBelongsToOwner($user));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin() || ($user->isOwner() && $appointment->isBelongsToOwner($user));
    }

    public function confirm(User $user, Appointment $appointment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isDoctor() && $appointment->isBelongsToDoctor($user)) {
            return true;
        }

        return false;
    }

    public function reject(User $user, Appointment $appointment): bool
    {
        return $user->can('confirm', $appointment);
    }

    public function checkIn(User $user, Appointment $appointment): bool
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return true;
        }

        return false;
    }

    public function startVisit(User $user, Appointment $appointment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDoctor() && $appointment->isBelongsToDoctor($user)) {
            return true;
        }

        return false;
    }

    public function complete(User $user, Appointment $appointment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isDoctor() && $appointment->isBelongsToDoctor($user)) {
            return true;
        }

        return false;
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        if ($user->isOwner() && $appointment->isBelongsToOwner($user)) {
            return true;
        }

        return false;
    }
}
