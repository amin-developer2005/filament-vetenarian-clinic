<?php

namespace App\Filament\Resources\LiveStocks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LiveStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('عکس دام')
                    ->circular()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('نام دام')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('نوع دام')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('owner.name')
                    ->label('مالک دام')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clinics.name')
                    ->label('مالک دام')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_of_birth')
                    ->label('تاریخ تولد')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('تاریخ ساخت دام')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
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
