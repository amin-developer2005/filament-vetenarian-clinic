<?php

namespace App\Filament\Owner\Resources\Animals\Pages;

use App\Filament\Owner\Resources\Animals\AnimalResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateAnimal extends CreateRecord
{
    protected static string $resource = AnimalResource::class;

    public function getTitle(): string|Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return __('resources/animals.pages.create.record.title');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! isset($data['owner_id'])) {
            $data['owner_id'] = Filament::auth()->id();
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
