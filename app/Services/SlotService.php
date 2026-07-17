<?php

namespace App\Services;

use App\Enums\SlotStatus;
use App\Models\Slot;
use Carbon\Carbon;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;

class SlotService
{
    use CanUseDatabaseTransactions;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function checkExpiredSlots(): void
    {
        $today = Carbon::today();

        $this->wrapInDatabaseTransaction(function () use ($today) {
            $expiredSlots = Slot::query()
                ->where('status', SlotStatus::Available)
                ->whereDoesntHave('appointments')
                ->whereDate('date', '<', $today)
                ->orWhereDate('date', $today)
                ->whereTime('end_time', '<', now())
                ->get();

            $expiredSlots->map(
                fn(Slot $slot) => $this->expire($slot)
            );
        });
    }

    public function expire(Slot $slot): bool
    {
        $this->beginDatabaseTransaction();

        $result = $slot->update([
            'status' => SlotStatus::Expired,
        ]);

        $this->commitDatabaseTransaction();

        return $result;
    }

    public function free(Slot $slot): bool
    {
        $this->beginDatabaseTransaction();

        $result = $slot->update([
            'status' => SlotStatus::Available,
        ]);

        $this->commitDatabaseTransaction();

        return $result;
    }

    public function book(Slot $slot): bool
    {
        $this->beginDatabaseTransaction();

        $result = $slot->update([
            'status' => SlotStatus::Booked,
        ]);

        $this->commitDatabaseTransaction();

        return $result;
    }
}
