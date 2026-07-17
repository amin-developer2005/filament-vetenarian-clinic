<?php

namespace App\Filament\Staff\Resources\Slots\Tables;

use App\Enums\SlotStatus;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                TextColumn::make('schedule.doctor.name')
                    ->label(__('resources/slots.table.columns.doctor'))
                    ->searchable()
                    ->sortable(),

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
                    ->jalaliDate('d, M Y')
                    ->sortable(),


                TextColumn::make('day_of_week')
                    ->label(__('resources/slots.table.columns.dayOfWeek'))
                    ->state(function ($record) {
                        return Carbon::parse($record->date)
                            ->locale('fa')
                            ->dayName;
                    })
                    ->badge()
                    ->color(Color::Lime)
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label(__('resources/slots.table.columns.start_time'))
                    ->time('h:i A'),

                TextColumn::make('end_time')
                    ->label(__('resources/slots.table.columns.end_time'))
                    ->time('h:i A'),

                TextColumn::make('created_at')
                    ->label(__('resources/slots.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('resources/slots.table.filters.status.label'))
                    ->options(SlotStatus::class)
                    ->native(false),
            ], FiltersLayout::Modal)
            ->emptyStateHeading(__('resources/slots.table.emptyStateHeading'))
            ->emptyStateDescription(__('resources/slots.table.emptyStateDescription'))
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
