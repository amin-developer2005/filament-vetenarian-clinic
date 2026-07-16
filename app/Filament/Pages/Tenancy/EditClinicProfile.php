<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class EditClinicProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return "ویرایش پروفایل کلینیک";
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('نام کلینیک')
                ->nullable()
                ->dehydrated(fn (string $state) => filled($state)),
        ]);
    }
}
