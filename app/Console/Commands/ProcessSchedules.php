<?php

namespace App\Console\Commands;

use App\Enums\SlotStatus;
use App\Models\Schedule;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class ProcessSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredSchedules = Schedule::query()
            ->whereDate('end_date', '<=', now())
            ->whereHas('slots', function (Builder $query) {
                $query
                    ->where('status', SlotStatus::Available)
                    ->whereDoesntHave('appointments');
            })
            ->get();

        foreach ($expiredSchedules as $schedule) {
            $schedule->slots->expire();
        }
    }
}
