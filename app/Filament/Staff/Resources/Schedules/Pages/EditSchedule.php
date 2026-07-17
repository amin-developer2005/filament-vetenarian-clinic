<?php

namespace App\Filament\Staff\Resources\Schedules\Pages;

use App\Filament\Staff\Resources\Schedules\ScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
