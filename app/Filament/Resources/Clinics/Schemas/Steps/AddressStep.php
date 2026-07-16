<?php

namespace App\Filament\Resources\Clinics\Schemas\Steps;

use App\Filament\Resources\Clinics\Schemas\Sections\AddressSection;
use App\Filament\Resources\Clinics\Schemas\Sections\GeneralInformationSection;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard\Step;

class AddressStep
{
    public static function build(): Step
    {
        return Step::make(__('resources/clinics.schema.wizard.steps.address.label'))
            ->description(__('resources/clinics.schema.wizard.steps.address.description'))
            ->schema([
                AddressSection::make(),
            ]);
    }
}
