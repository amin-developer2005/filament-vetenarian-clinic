<?php

namespace App\Filament\Resources\Clinics;

use App\Filament\Resources\Clinics\Pages\CreateClinic;
use App\Filament\Resources\Clinics\Pages\EditClinic;
use App\Filament\Resources\Clinics\Pages\ListClinics;
use App\Filament\Resources\Clinics\Schemas\ClinicForm;
use App\Filament\Resources\Clinics\Tables\ClinicsTable;
use App\Models\Clinic;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ClinicResource extends Resource
{
    protected static ?string $model = Clinic::class;
    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ClinicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClinicsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['address']);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClinics::route('/'),
            'create' => CreateClinic::route('/create'),
            'edit' => EditClinic::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return static::$modelLabel ?? __('resources/clinics.label');
    }

    public static function getPluralModelLabel(): string
    {
        if ($label = static::$pluralModelLabel ?? static::getModelLabel()) {
            return $label;
        }

        return __('resources/clinics.plural_label');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return static::$navigationGroup;
    }

    /**
     * @return string|null
     */
    public static function getNavigationLabel(): string
    {
        if ($label = static::$navigationLabel ?? static::getModelLabel()) {
            return $label;
        }

        return __('resources/clinics.navigations.label');
    }

    public static function getBreadcrumb(): string
    {
        if (filled($breadcrumb = static::$breadcrumb)) {
            return $breadcrumb;
        }

        return __('resources/clinics.bread_crumb');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();

        return $user->isAdmin();
    }

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return $user->isAdmin();
    }
}
