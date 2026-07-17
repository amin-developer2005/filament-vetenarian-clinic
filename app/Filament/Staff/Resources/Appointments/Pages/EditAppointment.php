<?php

namespace App\Filament\Staff\Resources\Appointments\Pages;

use App\Filament\Staff\Resources\Appointments\AppointmentResource;
use App\Models\Slot;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Auth\Access\AuthorizationException;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;


    protected function beforeSave(): void
    {
        $appointment = $this->getRecord();
        $slotId = $this->data['slot_id'] ?? null;

        if ($slotId && $appointment->slot_id !== $slotId) {
            $slot = Slot::query()->find($slotId);

            try {
                $appointment->updateSlot($slot);
            } catch (\Exception $exception) {
                Notification::make()
                    ->title($exception->getMessage())
                    ->danger()
                    ->send();

                return;
            }

        }
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
