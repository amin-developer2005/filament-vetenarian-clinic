<?php

namespace App\Filament\Staff\Resources\Appointments;

use App\Filament\Staff\Resources\Appointments\Pages\CreateAppointment;
use App\Filament\Staff\Resources\Appointments\Pages\EditAppointment;
use App\Filament\Staff\Resources\Appointments\Pages\ListAppointments;
use App\Filament\Staff\Resources\Appointments\Schemas\AppointmentForm;
use App\Filament\Staff\Resources\Appointments\Tables\AppointmentsTable;
use App\Models\Appointment;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    private static bool $hasNavigationGroup = true;

    protected static bool $isScopedToTenant = false;

    public static function form(Schema $schema): Schema
    {
        return AppointmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AppointmentsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('clinic_id', Filament::getTenant()->id);
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
            'index' => ListAppointments::route('/'),
            'create' => CreateAppointment::route('/create'),
            'edit' => EditAppointment::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return static::$modelLabel ?? __('resources/appointments.label');
    }

    public static function getPluralModelLabel(): string
    {
        if ($label = static::$modelLabel ?? static::getLabel()) {
            return $label;
        }

        return __('resources/appointments.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        if ($label = static::$navigationLabel ?? static::getModelLabel()) {
            return $label;
        }

        return __('resources/appointments.navigations.label');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        if (! static::$hasNavigationGroup) {
            return null;
        }

        return static::$navigationGroup ?? __('resources/appointments.navigations.group');
    }

    public static function getBreadcrumb(): string
    {
        if (filled($breadcrumb = static::$breadcrumb)) {
            return $breadcrumb;
        }

        return __('resources/appointments.bread_crumb');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();

        return $user->isStaff();
    }

    public static function canAccess(): bool
    {
        return true;
    }
}
