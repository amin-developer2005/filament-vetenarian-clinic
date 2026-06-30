<?php

namespace App\Filament\Owner\Resources\MedicalRecords;

use App\Filament\Owner\Pages\Profile;
use App\Filament\Owner\Resources\MedicalRecords\Pages\CreateMedicalRecord;
use App\Filament\Owner\Resources\MedicalRecords\Pages\EditMedicalRecord;
use App\Filament\Owner\Resources\MedicalRecords\Pages\ListMedicalRecords;
use App\Filament\Owner\Resources\MedicalRecords\Schemas\MedicalRecordForm;
use App\Filament\Owner\Resources\MedicalRecords\Tables\MedicalRecordsTable;
use App\Models\Animal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MedicalRecordResource extends Resource
{
    protected static ?string $model = Animal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    private static bool $hasNavigationGroup = true;

    public static function form(Schema $schema): Schema
    {
        return MedicalRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicalRecordsTable::configure($table);
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
            'index' => ListMedicalRecords::route('/'),
            'create' => CreateMedicalRecord::route('/create'),
            'edit' => EditMedicalRecord::route('/{record}/edit'),
        ];
    }


    public static function getLabel(): ?string
    {
        return static::$modelLabel ??'پرونده های پزشکی';
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

        return 'پرونده های پزشکی';
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

        return 'پرونده های پزشکی';
    }

}
