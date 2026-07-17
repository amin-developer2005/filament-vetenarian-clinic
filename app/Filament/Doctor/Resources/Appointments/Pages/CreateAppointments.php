<?php

namespace App\Filament\Doctor\Resources\Appointments\Pages;

use App\Filament\Doctor\Resources\Appointments\AppointmentsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointments extends CreateRecord
{
    protected static string $resource = AppointmentsResource::class;
}
