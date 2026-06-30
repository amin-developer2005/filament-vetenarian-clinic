<?php

namespace App\Filament\Owner\Resources\Animals;

use App\Filament\Owner\Resources\Animals\Pages\CreateAnimal;
use App\Filament\Owner\Resources\Animals\Pages\EditAnimal;
use App\Filament\Owner\Resources\Animals\Pages\ListAnimals;
use App\Filament\Owner\Resources\Animals\Schemas\AnimalForm;
use App\Filament\Owner\Resources\Animals\Tables\AnimalsTable;
use App\Models\Animal;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AnimalResource extends Resource
{
    protected static ?string $model = Animal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    private static bool $hasNavigationGroup = true;

    public static function form(Schema $schema): Schema
    {
        return AnimalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnimalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = static::getModel()::query();

        return $query
            ->where('owner_id', Filament::auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAnimals::route('/'),
            'create' => CreateAnimal::route('/create'),
            'edit' => EditAnimal::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return static::$modelLabel ?? __('owner/animals.label');
    }

    public static function getPluralModelLabel(): string
    {
        if ($label = static::$modelLabel ?? static::getLabel()) {
            return $label;
        }

        return __('owner/animals.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        if ($label = static::$navigationLabel ?? static::getModelLabel()) {
            return $label;
        }

        return __('owner/animals.navigations.label');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        if (! static::$hasNavigationGroup) {
            return null;
        }

        return static::$navigationGroup ?? __('owner/animals.navigations.group');
    }

    public static function getBreadcrumb(): string
    {
        if (filled($breadcrumb = static::$breadcrumb)) {
            return $breadcrumb;
        }

        return __('owner/animals.bread_crumb');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Filament::auth()->user();

        return $user->isOwner() || $user->isAdmin();
    }

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return $user->isOwner() || $user->isAdmin();
    }
}
