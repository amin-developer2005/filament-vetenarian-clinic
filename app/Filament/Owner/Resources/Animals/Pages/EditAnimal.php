<?php

namespace App\Filament\Owner\Resources\Animals\Pages;

use App\Filament\Owner\Resources\Animals\AnimalResource;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Auth\Access\AuthorizationException;

class EditAnimal extends EditRecord
{
    protected static string $resource = AnimalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! isset($data['owner_id'])) {
            $data['owner_id'] = Filament::auth()->id();
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
