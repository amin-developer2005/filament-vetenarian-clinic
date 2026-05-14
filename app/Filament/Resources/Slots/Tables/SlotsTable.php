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
                TextColumn::make('schedule.date')
                    ->label(__('resources/slots.table.columns.schedule.label'))
                    ->date('M d Y')
                    ->sortable(),
                TextColumn::make('start')
                    ->label(__('resources/slots.table.columns.start.label'))
                    ->time('h:i A')
                    ->label('From')
                    ->sortable(),
                TextColumn::make('end')
                    ->label(__('resources/slots.table.columns.end.label'))
                    ->time('h:i A')
                    ->label('To')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('resources/slots.table.columns.status.label'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('resources/slots.table.columns.start.label'))
                    ->since()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label(__('resources/slots.table.columns.updated_at.label'))
                    ->sortable()
                    ->since()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('resources/slots.table.filters.status.label'))
                    ->options(SlotStatus::class)
                    ->native(false),
                Filter::make('start')
                    ->schema([
                        TimePicker::make('start')
                            ->label(__('resources/slots.table.filters.start.label'))
                            ->date()
                            ->displayFormat('h:i A'),
                        TimePicker::make('end')
                            ->label(__('resources/slots.table.filters.end.label'))
                            ->time()
                            ->displayFormat('h:i A'),
                    ])->query(function (Builder $query, array $data) {
                        $fromTime = $data['start'] ?? null;
                        $toTime = $data['end'] ?? null;

                        return $query
                            ->when($fromTime, fn ($q, $v) => $q->whereTime('start', $v))
                            ->when($toTime, fn ($q, $v) => $q->whereTime('end', $v));
                    }),
            ], FiltersLayout::Modal)
            ->emptyStateHeading(__('resources/slots.table.emptyStateHeading'))
            ->emptyStateDescription(__('resources/slots.table.emptyStateDescription'))
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
