<?php

namespace App\Filament\Resources\LiveStocks;

use App\Filament\Resources\LiveStocks\Pages\CreateLiveStock;
use App\Filament\Resources\LiveStocks\Pages\EditLiveStock;
use App\Filament\Resources\LiveStocks\Pages\ListLiveStocks;
use App\Filament\Resources\LiveStocks\Schemas\LiveStockForm;
use App\Filament\Resources\LiveStocks\Tables\LiveStocksTable;
use App\Models\LiveStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use UnitEnum;

class LiveStockResource extends Resource
{
    protected static ?string $model = LiveStock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LiveStockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LiveStocksTable::configure($table);
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
            'index' => ListLiveStocks::route('/'),
            'create' => CreateLiveStock::route('/create'),
            'edit' => EditLiveStock::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return static::$modelLabel ?? __('resources/liveStocks.label');
    }

    public static function getPluralModelLabel(): string
    {
        if ($label = static::$pluralModelLabel ?? static::getModelLabel()) {
            return $label;
        }

        return __('resources/liveStocks.plural_label');
    }

    /**
     * @return string|null
     */
    public static function getNavigationLabel(): string
    {
        if ($label = static::$navigationLabel ?? static::getModelLabel()) {
            return $label;
        }

        return __('resources/liveStocks.navigations.label');
    }

    public static function getBreadcrumb(): string
    {
        if (filled($breadcrumb = static::$breadcrumb)) {
            return $breadcrumb;
        }

        return __('resources/liveStocks.bread_crumb');
    }

}
