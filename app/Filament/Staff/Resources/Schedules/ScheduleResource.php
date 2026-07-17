<?php

namespace App\Filament\Staff\Resources\Schedules;

use App\Filament\Staff\Resources\Schedules\Pages\CreateSchedule;
use App\Filament\Staff\Resources\Schedules\Pages\EditSchedule;
use App\Filament\Staff\Resources\Schedules\Pages\ListSchedules;
use App\Filament\Staff\Resources\Schedules\Schemas\ScheduleForm;
use App\Filament\Staff\Resources\Schedules\Tables\SchedulesTable;
use App\Models\Schedule;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    private static bool $hasNavigationGroup = true;


    public static function form(Schema $schema): Schema
    {
        return ScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SchedulesTable::configure($table);
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
            'index' => ListSchedules::route('/'),
            'create' => CreateSchedule::route('/create'),
            'edit' => EditSchedule::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return static::$modelLabel ?? __('resources/schedules.label');
    }

    public static function getPluralModelLabel(): string
    {
        if ($label = static::$modelLabel ?? static::getLabel()) {
            return $label;
        }

        return __('resources/schedules.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        if ($label = static::$navigationLabel ?? static::getModelLabel()) {
            return $label;
        }

        return __('resources/schedules.navigations.label');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        if (! static::$hasNavigationGroup) {
            return null;
        }

        return static::$navigationGroup ?? __('resources/schedules.navigations.group');
    }

    public static function getBreadcrumb(): string
    {
        if (filled($breadcrumb = static::$breadcrumb)) {
            return $breadcrumb;
        }

        return __('resources/schedules.bread_crumb');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();

        return $user->isStaff() || $user->isAdmin();
    }

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return $user->isStaff() || $user->isAdmin();
    }
}
