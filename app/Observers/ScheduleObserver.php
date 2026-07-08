<?php

namespace App\Observers;

use App\Models\Schedule;
use App\Models\Slot;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 * @author Mohammadamin Meghdadi
 * @email mohamadamin.meghdadi@gmail.com
 */
class ScheduleObserver
{
    public ScheduleService $scheduleService {
        set => $this->scheduleService = $value;
        get => $this->scheduleService;
    }

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Handle the Schedule "created" event.
     */
    public function created(Schedule $schedule): void
    {
        $this->scheduleService->generateSlots($schedule);
    }

    /**
     * Handle the Schedule "updated" event.
     */
    public function updated(Schedule $schedule): void
    {
        $schedule->slots()->delete();

        $this->created($schedule);
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
