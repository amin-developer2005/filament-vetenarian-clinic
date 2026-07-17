<?php

namespace App\Filament\Resources\Slots\Tables;

use App\Enums\SlotStatus;
use App\Models\Clinic;
use App\Models\Slot;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
