<?php

namespace App\Services;

use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ScheduleService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generateSlots(Schedule $schedule): void
    {
        $startDate = Carbon::parse($schedule->start_date);
        $endDate = Carbon::parse($schedule->end_date);

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $timeStart = Carbon::parse($schedule->time_start);
            $timeEnd = Carbon::parse($schedule->time_end);
            $duration = $schedule->slot_duration;

            $totalSlotTimes = $this->fetchTimesDifference($timeStart, $timeEnd);
            $slotDurationTime = $this->calculateSlotDurationTime($totalSlotTimes, $duration);

            for ($i = 0; $i < $slotDurationTime; $i++) {
                $slotEndTime = $timeStart->copy()->addMinutes($duration);

                $schedule->slots()->create([
                    'date'       => $date->toDateString(),
                    'start_time' => $timeStart,
                    'end_time'   => $slotEndTime,
                ]);

                $timeStart->addMinutes($duration);
            }

        }
    }

    private function fetchTimesDifference(Carbon $start, Carbon $end): float
    {
        return $start->diffInMinutes($end);
    }

    private function calculateSlotDurationTime(float $totalSlotTimes, int $duration): float
    {
        return $totalSlotTimes / $duration;
    }
}
