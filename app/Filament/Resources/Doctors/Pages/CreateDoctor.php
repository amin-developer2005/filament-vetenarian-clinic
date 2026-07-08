<?php

namespace App\Filament\Resources\Doctors\Pages;

use App\Enums\PanelRole;
use App\Filament\Resources\Doctors\DoctorResource;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class CreateDoctor extends CreateRecord
{
    protected static string $resource = DoctorResource::class;

    public function getTitle(): string|Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return __('resources/doctors.pages.create.record.title');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = $this->getModel()::query()->create($data);

        if ($parentRecord = $this->getParentRecord()) {
            return $this->associateRecordWithParent($record, $parentRecord);
        }

        $record->assignRole(PanelRole::Doctor);

        return $record;
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

}
