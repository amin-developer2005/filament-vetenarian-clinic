<?php

namespace App\Jobs;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessNoShowAppointment implements ShouldQueue
{
    use Queueable;

    protected Appointment $appointment {
        set => $this->appointment = $value;
        get => $this->appointment;
    }

    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->appointment->isConfirmed()) {
            $this->appointment->changeStatusTo(AppointmentStatus::NoShow);
        }
    }
}
