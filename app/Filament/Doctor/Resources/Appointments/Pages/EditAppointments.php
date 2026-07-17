<?php

namespace App\Filament\Doctor\Resources\Appointments\Pages;

use App\Filament\Doctor\Resources\Appointments\AppointmentsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAppointments extends EditRecord
{
    protected static string $resource = AppointmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
