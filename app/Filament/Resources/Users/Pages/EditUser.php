<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Htmlable;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string|Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return __('resources/users.pages.edit.record.title');
    }

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
            filled($defaultRedirect = Filament::getResourceEditPageRedirectUrl()) &&
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

        return $this->getResourceUrl('index', $this->getRedirectUrlParameters());
    }


}
