<?php

namespace App\Filament\Resources\Animals\Schemas;

use App\Enums\AnimalGender;
use App\Enums\AnimalSpecies;
use App\Enums\PanelRole;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class AnimalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    FileUpload::make('avatar')
                        ->label(__('resources/animals.schema.form.components.avatar.label'))
                        ->nullable()
                        ->openable()
                        ->downloadable()
                        ->previewable()
                        ->avatar()
                        ->image()
                        ->imageEditor()
                        ->maxSize(2048)
                        ->directory('avatars/animals'),
                ])->columnSpanFull(),
                Section::make([
                    \Filament\Schemas\Components\Grid::make(3)
                        ->schema([

                            TextInput::make('name')
                                ->label(__('resources/animals.schema.form.components.name.label'))
                                ->required()
                                ->string(),
                            Select::make('species')
                                ->label(__('resources/animals.schema.form.components.species.label'))
                                ->required()
                                ->options(AnimalSpecies::class)
                                ->searchable()
                                ->preload()
                                ->native(false),
                            TextInput::make('breed')
                                ->label(__('resources/animals.schema.form.components.breed.label'))
                                ->required()
                                ->string(),
                            Select::make('gender')
                                ->label(__('resources/animals.schema.form.components.gender.label'))
                                ->required()
                                ->options(AnimalGender::class)
                                ->searchable()
                                ->preload()
                                ->native(false),
                            DatePicker::make('date_of_birth')
                                ->label(__('resources/animals.schema.form.components.date_of_birth.label'))
                                ->required()
                                ->date()
                                ->displayFormat('Y-m-d')
                                ->closeOnDateSelection()
                                ->maxDate(now())
                                ->native(false),

                            Select::make('owner_id')
                                ->label(__('resources/animals.schema.form.components.owner_id.label'))
                                ->relationship(
                                    name: 'owner',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn (Builder $query) => $query->whereHas(
                                        'roles',
                                        fn(Builder $query) => $query->where('name', 'owner')
                                    )
                                )
                                ->required()
                                ->searchable()
                                ->preload()
                                ->noOptionsMessage(__('resources/animals.schema.form.components.owner_id.no_options_message'))
                                ->native(false),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}
