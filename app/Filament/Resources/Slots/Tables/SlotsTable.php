<?php

namespace App\Filament\Resources\Slots\Tables;

use App\Enums\SlotStatus;
use App\Models\Slot;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Query\Builder;

class SlotsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date('M d Y')
                    ->sortable(),
                TextColumn::make('start')
                    ->time('h:i A')
                    ->label('From')
                    ->sortable(),
                TextColumn::make('end')
                    ->time('h:i A')
                    ->label('To')
                    ->sortable(),
                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(SlotStatus::class)
                    ->native(false),
                Filter::make('date')
                    ->schema([
                        DatePicker::make('from')
                            ->label('From Date')
                            ->date()
                            ->native(false),
                        DatePicker::make('to')
                            ->label('To Date')
                            ->date()
                            ->native(false),
                    ])->query(function (Builder $query, array $data) {
                        $fromDate = $data['from'] ?? null;
                        $toDate = $data['to'] ?? null;

                        return $query
                            ->when($fromDate, fn ($q, $v) => $q->whereDate('date', '>=', $v))
                            ->when($toDate, fn ($q, $v) => $q->whereDate('date', '<=', $v));
                    }),
                Filter::make('start')
                    ->schema([
                        TimePicker::make('start')
                            ->label('From Time')
                            ->date()
                            ->displayFormat('h:i A'),
                        TimePicker::make('end')
                            ->label('To Time')
                            ->time()
                            ->displayFormat('h:i A'),
                    ])->query(function (Builder $query, array $data) {
                        $fromTime = $data['start'] ?? null;
                        $toTime = $data['end'] ?? null;

                        return $query
                            ->when($fromTime, fn ($q, $v) => $q->whereTime('start', $v))
                            ->when($toTime, fn ($q, $v) => $q->whereTime('end', $v));
                    }),

                SelectFilter::make('owner_id')
                    ->relationship('owner', 'name')
                    ->label('Owner')
                    ->searchable()
                    ->preload()
                    ->native(false),
            ], FiltersLayout::Modal)
            ->emptyStateHeading('No Slots Found')
            ->emptyStateDescription('Create a new slot to start managing reservations.')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
