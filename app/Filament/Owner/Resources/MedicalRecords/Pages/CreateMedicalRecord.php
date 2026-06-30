<?php

namespace App\Filament\Owner\Resources\MedicalRecords\Pages;

use App\Filament\Owner\Resources\MedicalRecords\MedicalRecordResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateMedicalRecord extends CreateRecord
{
    protected static string $resource = MedicalRecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! isset($data['owner_id'])) {
            $data['owner_id'] = Filament::auth()->id();
        }

        return $data;
    }
}
