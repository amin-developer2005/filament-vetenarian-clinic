<?php

namespace App\Filament\Doctor\Resources\Slots\Tables;

use App\Enums\SlotStatus;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
                TextColumn::make('schedule.clinic.name')
                    ->label(__('resources/slots.table.columns.clinic'))
                    ->badge()
                    ->color(Color::Blue)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('resources/slots.table.columns.status'))
                    ->badge(),

                TextColumn::make('date')
                    ->label(__('resources/slots.table.columns.date'))
                    ->jalaliDate('d,M Y')
                    ->sortable(),

                TextColumn::make('day_of_week')
                    ->label(__('resources/slots.table.columns.dayOfWeek'))
                    ->state(function ($record) {
                        return Carbon::parse($record->date)
                            ->locale(app()->getLocale())
                            ->dayName;
                    })
                    ->badge()
                    ->color(Color::Emerald)
                    ->sortable(
                        query: fn ($query, $direction) => $query->orderBy('date', $direction)
                    ),

                TextColumn::make('start_time')
                    ->label(__('resources/slots.table.columns.start_time'))
                    ->jalaliDateTime('h:i A'),

                TextColumn::make('end_time')
                    ->label(__('resources/slots.table.columns.end_time'))
                    ->jalaliDateTime('h:i A'),

                TextColumn::make('created_at')
                    ->label(__('resources/slots.table.columns.created_at'))
                    ->jalaliDateTime('d,M Y h:i A')
                    ->sortable()
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
                            ->time()
                            ->native(false)
                            ->displayFormat('h:i A'),
                        TimePicker::make('end')
                            ->label(__('resources/slots.table.filters.end.label'))
                            ->time()
                            ->native(false)
                            ->displayFormat('h:i A'),
                    ])->query(function (Builder $query, array $data) {
                        $fromTime = $data['start'] ?? null;
                        $toTime = $data['end'] ?? null;

                        return $query
                            ->when($fromTime, fn ($q, $v) => $q->whereTime('start', $v))
                            ->when($toTime, fn ($q, $v) => $q->whereTime('end', $v));
                    }),
            ], FiltersLayout::Modal)
            ->emptyStateHeading(__('doctors/slots.table.emptyStateHeading'))
            ->emptyStateDescription(__('doctors/slots.table.emptyStateDescription'))
            ->emptyStateIcon('heroicon-o-clock')
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);

    }
}
