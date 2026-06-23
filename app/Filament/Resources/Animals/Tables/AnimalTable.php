<?php

namespace App\Filament\Resources\Animals\Tables;

use App\Enums\AnimalSpecies;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AnimalTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label(__('resources/animals.table.columns.avatar'))
                    ->circular(),

                TextColumn::make('name')
                    ->label(__('resources/animals.table.columns.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('owner.name')
                    ->label(__('resources/animals.table.columns.owner'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('clinics.name')
                    ->label(__('resources/animals.table.columns.clinics'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('species')
                    ->label(__('resources/animals.table.columns.species'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('date_of_birth')
                    ->label(__('resources/animals.table.columns.date_of_birth'))
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('resources/animals.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('owner_id')
                    ->label(__('resources/animals.table.filters.owner'))
                    ->relationship('owner', 'name'),
                SelectFilter::make('species')
                    ->label(__('resources/animals.table.filters.species'))
                    ->options(AnimalSpecies::class),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
