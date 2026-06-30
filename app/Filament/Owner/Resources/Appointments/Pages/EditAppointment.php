<?php

namespace App\Filament\Owner\Resources\Appointments\Pages;

use App\Enums\AppointmentStatus;
use App\Filament\Owner\Resources\Appointments\AppointmentResource;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Auth\Access\AuthorizationException;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (! isset($data['owner_id'])) {
            $data['owner_id'] = Filament::auth()->id();
        }

        if (! isset($data['status'])) {
            $data['status'] = AppointmentStatus::Pending;
        }

        return $data;
    }

    public function getRedirectUrl(): ?string
    {
        $resource = static::getResource();
        $record = $this->getRecord();

        if (
            filled($defaultRedirect = Filament::getResourceEditPageRedirect()) &&
            $resource::hasPage($defaultRedirect) &&
            ($defaultRedirect !== 'view' || $resource::canView($record))
        ) {
            return $this->getResourceUrl($defaultRedirect, $this->getRedirectUrlParameters());
        }

        try {
            $this->authorizeAccess();
        } catch (AuthorizationException $exception) {
            return null;
        }

        if ($resource::hasPage('view') && $resource::canView($record)) {
            return $this->getResourceUrl('view', $this->getRedirectUrlParameters());
        }

        return $resource::getUrl('index');
    }
}
