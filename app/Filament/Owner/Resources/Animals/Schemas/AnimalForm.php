<?php

namespace App\Filament\Owner\Resources\Animals\Schemas;

use App\Enums\AnimalSpecies;
use App\Enums\AnimalGender;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;

class AnimalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([

                    /*
                    |--------------------------------------------------------------------------
                    | Step 1
                    |--------------------------------------------------------------------------
                    */

                    Wizard\Step::make(__('owner/animals.steps.basic.title'))
                        ->description(__('owner/animals.steps.basic.description'))
                        ->icon('heroicon-o-identification')

                        ->schema([

                            Section::make()
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('name')
                                                ->required()
                                                ->label(__('owner/animals.schema.form.components.name.label'))
                                                ->placeholder(__('owner/animals.schema.form.components.name.placeholder'))
                                                ->required()
                                                ->maxLength(255)
                                                ->autofocus(),

                                            Select::make('species')
                                                ->required()
                                                ->label(__('owner/animals.schema.form.components.species.label'))
                                                ->options(AnimalSpecies::class)
                                                ->native(false)
                                                ->searchable()
                                                ->required(),

                                        ]),

                                    TextInput::make('breed')
                                        ->required()
                                        ->label(__('owner/animals.schema.form.components.breed.label'))
                                        ->placeholder(__('owner/animals.schema.form.components.breed.placeholder'))
                                        ->maxLength(255),

                                ]),

                        ]),

                    /*
                    |--------------------------------------------------------------------------
                    | Step 2
                    |--------------------------------------------------------------------------
                    */

                    Wizard\Step::make(__('owner/animals.steps.identity.title'))
                        ->description(__('owner/animals.steps.identity.description'))
                        ->icon('heroicon-o-heart')

                        ->schema([

                            Section::make()

                                ->schema([

                                    Grid::make(2)
                                        ->schema([

                                            Select::make('gender')
                                                ->required()
                                                ->label(__('owner/animals.schema.form.components.gender.label'))
                                                ->options(AnimalGender::class)
                                                ->native(false)
                                                ->searchable()
                                                ->required(),

                                            DatePicker::make('date_of_birth')
                                                ->required()
                                                ->label(__('owner/animals.schema.form.components.date_of_birth.label'))
                                                ->native(false)
                                                ->date()
                                                ->closeOnDateSelection()
                                                ->displayFormat('Y-m-d')
                                                ->maxDate(now()),

                                        ]),

                                ]),

                        ]),

                    /*
                    |--------------------------------------------------------------------------
                    | Step 3
                    |--------------------------------------------------------------------------
                    */

                    Wizard\Step::make(__('owner/animals.steps.medical.title'))
                        ->description(__('owner/animals.steps.medical.description'))
                        ->icon('heroicon-o-shield-check')
                        ->schema([
                            Section::make()
                                ->schema([

                                    TextInput::make('microchip_number')
                                        ->label(__('owner/animals.schema.form.components.microchip_number.label'))
                                        ->nullable()
                                        ->placeholder(__('owner/animals.schema.form.components.microchip_number.placeholder'))
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),

                                    Toggle::make('is_neutered')
                                        ->label(__('owner/animals.schema.form.components.is_neutered.label'))
                                        ->nullable()
                                        ->inline(false)
                                        ->default(false),
                                ]),

                        ]),

                    /*
                    |--------------------------------------------------------------------------
                    | Step 4
                    |--------------------------------------------------------------------------
                    */

                    Wizard\Step::make(__('owner/animals.steps.avatar.title'))
                        ->description(__('owner/animals.steps.avatar.description'))
                        ->icon('heroicon-o-photo')
                        ->schema([
                            FileUpload::make('avatar')
                                ->label(__('owner/animals.schema.form.components.avatar.label'))
                                ->nullable()
                                ->image()
                                ->avatar()
                                ->imageEditor()
                                ->directory('avatars/animals')
                                ->visibility('public')
                                ->downloadable()
                                ->openable()
                                ->previewable()
                                ->moveFiles()
                                ->maxSize(4096)
                                ->columnSpanFull(),

                        ]),

                ])
                    ->columnSpanFull()
                    ->persistStepInQueryString(),
            ]);
    }
}
