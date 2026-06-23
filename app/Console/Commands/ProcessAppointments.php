<?php

namespace App\Console\Commands;

use App\Enums\AppointmentStatus;
use App\Jobs\ProcessNoShowAppointment;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->markAsNoShow();

        return self::SUCCESS;
    }

    private function markAsNoShow(): void
    {
        $appointments = Appointment::query()
            ->where('status', AppointmentStatus::InProgress)
            ->whereHas('slot', function ($query) {
                $query
                    ->whereDate(Carbon::today())
                    ->whereTime('end_time', '<=', Carbon::now());
            })
            ->get();

        foreach ($appointments as $appointment) {
            ProcessNoShowAppointment::dispatch($appointment);
        }
    }
}
