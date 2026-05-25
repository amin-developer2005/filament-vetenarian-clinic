<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\ScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Auth\Access\AuthorizationException;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
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
