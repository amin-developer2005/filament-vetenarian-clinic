<?php

namespace App\Filament\Owner\Resources\MedicalRecords\Schemas;

use App\Enums\AnimalSpecies;
use App\Enums\AnimalGender;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MedicalRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar')
                    ->visibility('public')
                    ->disk('public')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/doc',
                        'application/docx',
                        'application/xls',
                        'application/txt'
                    ])
                    ->maxSize(9097)
                    ->directory('/docs'),
                TextInput::make('name')
                    ->default('aaa'),
                TextInput::make('breed')
                    ->default('sdg'),
                TextInput::make('species')
                    ->default(AnimalSpecies::Cat),
                TextInput::make('gender')
                    ->default(AnimalGender::Male),
                DateTimePicker::make('date_of_birth')
                    ->default(today()),

            ]);
    }
}
