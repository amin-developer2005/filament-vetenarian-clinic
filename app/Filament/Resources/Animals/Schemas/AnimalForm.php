<?php

namespace App\Filament\Resources\Animals\Schemas;

use App\Enums\AnimalSpecies;
use App\Enums\GenderType;
use App\Enums\PanelRole;
use App\Models\Role;
use Filament\Facades\Filament;
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
                        ->previewable()
                        ->image()
                        ->imageEditor()
                        ->maxSize(2048)
                        ->directory('avatars/liveStocks'),
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
                        ->options(GenderType::class)
                        ->searchable()
                        ->preload()
                        ->native(false)
                    ,
                    DatePicker::make('date_of_birth')
                        ->label(__('resources/animals.schema.form.components.date_of_birth.label'))
                        ->required()
                        ->date()
                        ->displayFormat('Y-m-d')
                        ->closeOnDateSelection()
                        ->maxDate(now())
                        ->native(false),
                ]),
                Section::make([
                    Select::make('owner_id')
                        ->label(__('resources/animals.schema.form.components.owner_id.label'))
                        ->relationship('owner', 'name', modifyQueryUsing: function (Builder $query) {
                            $tenant = Filament::getTenant();

                            return $query->whereHas('clinics', function (Builder $query) use ($tenant) {
                                return $query->where('clinics.id', $tenant->id);
                            })
                                ->whereHas('roles', function (Builder $query) {
                                return $query->where('name', PanelRole::Owner);
                            });
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->noOptionsMessage(__('resources/animals.schema.form.components.owner_id.no_options_message'))
                        ->native(false),

                ])

            ]);
    }
}
