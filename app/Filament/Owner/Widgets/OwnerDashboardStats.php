<?php

namespace App\Filament\Owner\Widgets;

use App\Enums\AppointmentStatus;
use App\Models\Animal;
use App\Models\Appointment;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class OwnerDashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $owner = Filament::auth()->user();

        return [
            Stat::make(
                'حیوانات من',
                Animal::query()
                    ->where('owner_id', $owner->id)
                    ->count()
            ),
            Stat::make(
                'نوبت‌های من',
                Appointment::query()
                    ->where('owner_id', $owner->id)
                    ->count()
            ),
            Stat::make(
                'نوبت‌های در انتظار تایید',
                Appointment::query()
                    ->where('owner_id', $owner->id)
                    ->where('status', AppointmentStatus::Pending)
                    ->count()
            ),
            Stat::make(
                'نوبت‌های امروز',
                Appointment::query()
                    ->where('owner_id', $owner->id)
                    ->whereHas('slot', function (Builder $query) {
                        return $query
                            ->whereDate('date', today());
                    })
                    ->count()
            ),

        ];
    }
}
