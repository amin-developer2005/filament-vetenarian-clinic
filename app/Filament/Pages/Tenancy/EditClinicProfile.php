<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Schema;

class EditClinicProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return "Edit Clinic Profile";
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->nullable()
                ->dehydrated(fn (string $state) => filled($state)),
        ]);
    }
}
