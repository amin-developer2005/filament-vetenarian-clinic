<?php

namespace App\Filament\Staff\Resources\Schedules\Pages;

use App\Filament\Staff\Resources\Schedules\ScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
