<?php

namespace App\Observers;

use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 * @author Mohammadamin Meghdadi
 * @email mohamadamin.meghdadi@gmail.com
 */
class ScheduleObserver
{
    /**
     * Handle the Schedule "created" event.
     */
    public function created(Schedule $schedule): void
    {
        $startDate = Carbon::parse($schedule->start_date);
        $endDate = Carbon::parse($schedule->end_date);
        $daysOfWeek = $schedule->days_of_week;

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            if (! in_array($date->dayOfWeek, $daysOfWeek)) {
                continue;
            }

            $timeStart = Carbon::parse($schedule->time_start);
            $timeEnd = Carbon::parse($schedule->time_end);
            $duration = $schedule->slot_duration;

            $totalSlotTimes = $this->getDifferenceOfTimes($timeStart, $timeEnd);
            $slotDurationTime = $this->calculateSlotDurationTime($totalSlotTimes, $duration);

            for ($i = 0; $i < $slotDurationTime; $i++) {

                $slotEndTime = $timeStart->copy()->addMinutes($duration);

                $schedule->slots()->create([
                    'start_time' => $timeStart,
                    'end_time' => $slotEndTime,
                    'date' => $date->toDateString(),
                ]);

                $timeStart->addMinutes($duration);
            }
        }

    }

    private function getDifferenceOfTimes(Carbon $start, Carbon $end): float
    {
        return $start->diffInMinutes($end);
    }

    private function calculateSlotDurationTime(float $totalSlotTimes, int $duration): float
    {
        return $totalSlotTimes / $duration;
    }

    /**
     * Handle the Schedule "updated" event.
     */
    public function updated(Schedule $schedule): void
    {

    }

    /**
     * Handle the Schedule "deleted" event.
     */
    public function deleted(Schedule $schedule): void
    {
        //
    }

    /**
     * Handle the Schedule "restored" event.
     */
    public function restored(Schedule $schedule): void
    {
        //
    }

    /**
     * Handle the Schedule "force deleted" event.
     */
    public function forceDeleted(Schedule $schedule): void
    {
        //
    }
}
