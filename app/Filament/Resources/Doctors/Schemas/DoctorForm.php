<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('name')
                                ->label(__('resources/doctors.schema.form.components.name.label'))
                                ->required()
                                ->string(),
                            TextInput::make('email')
                                ->label(__('resources/doctors.schema.form.components.email.label'))
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => __('resources/doctors.schema.form.components.email.validationMessages.unique'),
                                ]),
                            Select::make('clinics')
                                ->relationship(titleAttribute: 'name')
                                ->label(__('resources/doctors.schema.form.components.clinics.label'))
                                ->required()
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->noOptionsMessage(__('resources/doctors.pages.create.form.select.clinics.no_options_message')),
                            TextInput::make('password')
                                ->required(fn (string $context): bool => $context === 'create')
                                ->label(__('resources/doctors.schema.form.components.password.label'))
                                ->password()
                                ->revealable()
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                ->dehydrated(fn ($state) => filled($state)),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}
