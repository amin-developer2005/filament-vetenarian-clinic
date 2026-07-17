<?php

namespace App\Filament\Staff\Resources\Appointments\Pages;

use App\Enums\AppointmentStatus;
use App\Filament\Staff\Resources\Appointments\AppointmentResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    public function getTitle(): string|Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return __('resources/appointments.pages.create.record.title');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! isset($data['status'])) {
            $data['status'] = AppointmentStatus::Pending;
        }

        return $data;
    }

    public function getRedirectUrl(): string
    {
        $resource = static::getResource();
        $record = $this->getRecord();

        if (
            filled($defaultRedirect = Filament::getResourceCreatePageRedirect()) &&
            $resource::hasPage($defaultRedirect) &&
            (
                ($defaultRedirect !== 'view' || $resource::hasView($record)) &&
                ($defaultRedirect !== 'edit' || $resource::hasEdit($record))
            )
        ) {
            return $this->getResourceUrl($defaultRedirect, $this->getRedirectUrlParameters());
        }

        if ($resource::hasPage('view') && $resource::canView($record)) {
            return $this->getResourceUrl('view', $this->getRedirectUrlParameters());
        }

        return $this->getResourceUrl('index', $this->getRedirectUrlParameters());
    }
}
