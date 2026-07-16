<?php

namespace App\Filament\Resources\Clinics\Schemas;

use App\Filament\Resources\Clinics\Schemas\Steps\AddressStep;
use App\Filament\Resources\Clinics\Schemas\Steps\GeneralInformationStep;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;

class ClinicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    GeneralInformationStep::build(),
                    AddressStep::build(),
                ])
                    ->columnSpanFull()
                    ->persistStepInQueryString(),
            ]);
    }
}
