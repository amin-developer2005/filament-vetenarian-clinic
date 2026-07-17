<?php

namespace App\Filament\Doctor\Resources\Schedules\Pages;

use App\Filament\Doctor\Resources\Schedules\ScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-s-plus')
                ->iconSize(IconSize::TwoExtraLarge)
                ->label(__('doctor/schedules.pages.index.actions.create')),
        ];
    }
}
