<?php

namespace App\Filament\Owner\Resources\Appointments\Pages;

use App\Enums\AppointmentStatus;
use App\Filament\Owner\Resources\Appointments\AppointmentResource;
use App\Models\Clinic;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

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
        if (! isset($data['owner_id'])) {
            $data['owner_id'] = Filament::auth()->id();
        }

        if (! isset($data['status'])) {
            $data['status'] = AppointmentStatus::Pending;
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = $this->getModel()::query()->create($data);

        $clinic = Clinic::query()->find($record->clinic_id);
        $clinic->users()->attach($record->owner_id);

        return $record;
    }


    public function canCreateAnother(): bool
    {
        return false;
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
