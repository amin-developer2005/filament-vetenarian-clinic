<?php

namespace App\Filament\Resources\Clinics\Pages;

use App\Filament\Resources\Clinics\ClinicResource;
use App\Models\Role;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CreateClinic extends CreateRecord
{
    protected static string $resource = ClinicResource::class;

    public function getTitle(): string|Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return __('resources/clinics.pages.create.record.title');
    }

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $addressData = Arr::get($data, 'address');
        $data = Arr::except($data, 'address');

        $record = $this->getModel()::query()->create($data);

        if (filled($addressData)) {
            $record->address()->create($addressData);
        }

        $record->users()->attach(auth()->user());

        collect(Role::query()->get())
            ->each(fn(Role $role) => $record->roles()->attach($role));

        return $record;
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
