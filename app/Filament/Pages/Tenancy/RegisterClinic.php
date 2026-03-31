<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Clinic;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;

class RegisterClinic extends RegisterTenant
{

    public static function getLabel(): string
    {
        return "Register Clinic";
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->string()
                ->maxLength(255),
        ]);
    }

    protected function handleRegistration(array $data): Clinic
    {
        $clinic = Clinic::create($data);

        $clinic->users()->attach(auth()->user());

        return $clinic;
    }
}
