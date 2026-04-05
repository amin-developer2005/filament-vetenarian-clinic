<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('name')
                        ->required()
                        ->string(),
                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required(),

                    Select::make('role_id')
                        ->relationship('role', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required()
                                ->string(),
                        ])
                        ->createOptionModalHeading('Add a new role'),
                    Select::make('clinics')
                        ->relationship(titleAttribute: 'name')
                        ->required()
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->native(false),
                    TextInput::make('password')
                        ->required(fn(string $context): bool => $context === 'create')
                        ->password()
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->dehydrated(fn($state) => filled($state)),
                    TextInput::make('mobile')
                        ->nullable(fn (string $context): bool => $context === 'edit')
                        ->tel()
                        ->dehydrated(fn ($state): bool => filled($state))
                        ->regex("/^[0-9]{11}$/"),
                ])->columnSpanFull()
            ]);
    }
}
