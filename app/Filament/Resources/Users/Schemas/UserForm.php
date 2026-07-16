<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\PanelRole;
use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('name')
                                ->label(__('resources/users.schema.form.components.name.label'))
                                ->required()
                                ->string(),
                            TextInput::make('email')
                                ->label(__('resources/users.schema.form.components.email.label'))
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => __('resources/users.schema.form.components.email.validationMessages.unique'),
                                ]),
                            Select::make('roles')
                                ->relationship(titleAttribute: 'name')
                                ->label(__('resources/users.schema.form.components.roles.label'))
                                ->required()
                                ->multiple()
                                ->getOptionLabelFromRecordUsing(fn(Role $role) => $role->name->value)
                                ->searchable()
                                ->live()
                                ->preload()
                                ->native(false)
                                ->noOptionsMessage(__('resources/users.schema.form.components.roles.no_options_message'))
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->required()
                                        ->label(__('resources/users.schema.form.components.roles.createOptionForm.name'))
                                        ->string(),
                                ])
                                ->createOptionModalHeading(__('resources/users.schema.form.components.roles.createOptionModalHeading')),
                            Select::make('clinics')
                                ->relationship(titleAttribute: 'name')
                                ->label(__('resources/users.schema.form.components.clinics.label'))
                                ->required()
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->noOptionsMessage(__('resources/user.pages.create.form.select.clinics.no_options_message')),
                            TextInput::make('password')
                                ->required(fn (string $context): bool => $context === 'create')
                                ->label(__('resources/users.schema.form.components.password.label'))
                                ->password()
                                ->revealable()
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                ->dehydrated(fn ($state) => filled($state)),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}
