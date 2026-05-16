<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('name')
                        ->label(__('resources/roles.form.name.label'))
                        ->required()
                        ->string(),
                    Textarea::make('description')
                        ->label(__('resources/roles.form.description.label'))
                        ->nullable(),
                ]),
                Section::make([
                    Select::make('clinics')
                        ->label('کلینیک ها')
                        ->relationship(titleAttribute: 'name')
                        ->required()
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->native(false),
                ])
            ]);
    }
}
