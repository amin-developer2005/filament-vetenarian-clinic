<?php

namespace App\Filament\Doctor\Resources\Schedules\Pages;

use App\Filament\Doctor\Resources\Schedules\ScheduleResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    public function getTitle(): \Illuminate\Contracts\Support\Htmlable|string
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return __('doctor/schedules.pages.create.record.title');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! isset($data['doctor_id'])) {
            $data['doctor_id'] = Filament::auth()->id();
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
