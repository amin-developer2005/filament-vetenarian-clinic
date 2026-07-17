<?php

namespace App\Filament\Staff\Resources\Appointments\Pages;

use App\Filament\Staff\Resources\Appointments\AppointmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-s-plus')
                ->iconSize(IconSize::TwoExtraLarge)
                ->label(__('resources/appointments.pages.index.actions.create')),
        ];
    }
}
