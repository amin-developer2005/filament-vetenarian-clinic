<?php

namespace App\Observers;

use App\Models\Appointment;


class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        if ($appointment->slot && $appointment->slot?->isAvailable()) {
            $appointment->slot->book();
        }
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        if (
            $appointment->wasChanged('status') &&
            $appointment->isCancelled()
        ) {
            $appointment->slot?->free();
        }

        if ($appointment->wasChanged('status') &&
            $appointment->isRejected()
        ) {
            $appointment->slot?->free();
        }

    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        $appointment->slot?->free();
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {

    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        $appointment->slot?->free();
    }

}
