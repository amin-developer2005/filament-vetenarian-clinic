<?php

namespace App\Filament\Resources\Slots\Pages;

use App\Filament\Resources\Slots\SlotResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateSlot extends CreateRecord
{
    protected static string $resource = SlotResource::class;

    public function getRedirectUrl(): string
    {
        $resource = static::getResource();
        $record = $this->getRecord();

        if (
            ! is_null($defaultRedirect = Filament::getResourceCreatePageRedirect()) &&
            $resource::hasPage($defaultRedirect) &&
            (
                (($defaultRedirect !== 'view') || $resource::canView($record)) &&
                (($defaultRedirect !== 'edit') || $resource::canEdit($record))
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
