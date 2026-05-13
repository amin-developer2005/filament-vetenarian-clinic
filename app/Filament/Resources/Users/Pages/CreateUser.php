<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string|Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return __('resources/users.pages.create.record.title');
    }

    public function getRedirectUrl(): string
    {
        $resource = static::getResource();
        $record = $this->getRecord();

        if (
            filled($defaultRedirect = Filament::getResourceCreatePageRedirect()) &&
            $resource::hasPage($$defaultRedirect) &&
            ($this instanceof CreateRecord)
            (
                (($defaultRedirect !== 'view' || $resource::canView($record))) &&
                (($defaultRedirect !== 'edit' || $resource::canEdit($record)))
            )
        ) {
            return $this->getResourceUrl($defaultRedirect, $this->getRedirectUrlParameters());
        }

        if ($resource::hasPage('view') && $resource::canView($record)) {
            return $this->getResourceUrl('view', $this->getRedirectUrlParameters());
        }

        return $this->getResourceUrl('index', $this->getRedirectUrlParameters());
    }

    protected function getRedirectUrlParameters(): array
    {
        return [
            'record' => $this->getRecord(),
        ];
    }
}
