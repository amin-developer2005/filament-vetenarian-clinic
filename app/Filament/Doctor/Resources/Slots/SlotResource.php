<?php

namespace App\Filament\Doctor\Resources\Slots;

use App\Filament\Doctor\Resources\Slots\Pages\CreateSlot;
use App\Filament\Doctor\Resources\Slots\Pages\EditSlot;
use App\Filament\Doctor\Resources\Slots\Pages\ListSlots;
use App\Filament\Doctor\Resources\Slots\Schemas\SlotForm;
use App\Filament\Doctor\Resources\Slots\Tables\SlotsTable;
use App\Models\Slot;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class SlotResource extends Resource
{
    protected static ?string $model = Slot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static bool $isScopedToTenant = false;

    private static bool $hasNavigationGroup = true;

    public static function form(Schema $schema): Schema
    {
        return SlotForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlotsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = static::getModel()::query();

        return $query
            ->whereHas('schedule',
                fn ($query) => $query->where('doctor_id', Filament::auth()->id())
            );
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
            'index' => ListSlots::route('/'),
            'create' => CreateSlot::route('/create'),
            'edit' => EditSlot::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return static::$modelLabel ?? __('doctor/slots.label');
    }

    public static function getPluralModelLabel(): string
    {
        if ($label = static::$modelLabel ?? static::getLabel()) {
            return $label;
        }

        return __('doctor/slots.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        if ($label = static::$navigationLabel ?? static::getModelLabel()) {
            return $label;
        }

        return __('doctor/slots.navigations.label');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        if (! static::$hasNavigationGroup) {
            return null;
        }

        return static::$navigationGroup ?? __('resources/slots.navigations.group');
    }

    public static function getBreadcrumb(): string
    {
        if (filled($breadcrumb = static::$breadcrumb)) {
            return $breadcrumb;
        }

        return __('doctor/slots.bread_crumb');
    }


    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();

        return $user->isDoctor();
    }

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return $user->isDoctor();
    }
}
