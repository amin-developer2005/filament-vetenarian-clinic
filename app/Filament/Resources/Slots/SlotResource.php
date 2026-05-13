<?php

namespace App\Filament\Resources\Slots;

use App\Filament\Resources\Slots\Pages\CreateSlot;
use App\Filament\Resources\Slots\Pages\EditSlot;
use App\Filament\Resources\Slots\Pages\ListSlots;
use App\Filament\Resources\Slots\Schemas\SlotForm;
use App\Filament\Resources\Slots\Tables\SlotsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\Slot;
use Illuminate\Database\Eloquent\Model;

class SlotResource extends Resource
{
    protected static ?string $model = Slot::class;

    protected static bool $isDiscovered = false;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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

    public static function getUrl(?string $name = null, array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null, bool $shouldGuessMissingParameters = false): string
    {
        return parent::getUrl($name, $parameters, $isAbsolute, $panel, $tenant);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSlots::route('/'),
            'create' => CreateSlot::route('/create'),
            'edit' => EditSlot::route('/{record}/edit'),
        ];
    }
}
