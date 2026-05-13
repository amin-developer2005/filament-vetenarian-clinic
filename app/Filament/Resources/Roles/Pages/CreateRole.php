<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public function getTitle(): string|Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return __('resources/roles.pages.create.record.title');
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();
        $record = $this->getRecord();

        if (
            filled($defaultRedirect = Filament::getResourceCreatePageRedirect()) &&
            $resource::hasPage($defaultRedirect) &&
            (
                (($defaultRedirect !== 'view') || ($resource::canView($record))) &&
                (($defaultRedirect !== 'edit') || ($resource::canEdit($record)))
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
