<?php

namespace App\Filament\Staff\Resources\Slots;

use App\Filament\Staff\Resources\Slots\Pages\CreateSlot;
use App\Filament\Staff\Resources\Slots\Pages\EditSlot;
use App\Filament\Staff\Resources\Slots\Pages\ListSlots;
use App\Filament\Staff\Resources\Slots\Schemas\SlotForm;
use App\Filament\Staff\Resources\Slots\Tables\SlotsTable;
use App\Models\Slot;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SlotResource extends Resource
{
    protected static ?string $model = Slot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static bool $hasNavigationGroup = true;
    protected static bool $isScopedToTenant = false;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return static::getModel()::query()
            ->whereHas('schedule', fn ($query) =>
            $query->where('clinic_id', Filament::getTenant()->id)
            );
    }

    public static function form(Schema $schema): Schema
    {
        return SlotForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlotsTable::configure($table);
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
        return static::$modelLabel ?? __('resources/slots.label');
    }

    public static function getPluralModelLabel(): string
    {
        if ($label = static::$modelLabel ?? static::getLabel()) {
            return $label;
        }

        return __('resources/slots.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        if ($label = static::$navigationLabel ?? static::getModelLabel()) {
            return $label;
        }

        return __('resources/slots.navigations.label');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        if (! static::$hasNavigationGroup) {
            return null;
        }

        return static::$navigationGroup ?? __('resources/slots.navigations.group');;
    }

    public static function getBreadcrumb(): string
    {
        if (filled($breadcrumb = static::$breadcrumb)) {
            return $breadcrumb;
        }

        return __('resources/slots.bread_crumb');
    }
}
