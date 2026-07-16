<?php

namespace App\Filament\Resources\Schedules\Tables;

use App\Models\Schedule;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Morilog\Jalali\Jalalian;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('start_date')
                    ->label(__('resources/schedules.table.columns.start_date.label'))
                    ->getTitleFromRecordUsing(
                        fn($record) => Jalalian::fromDateTime($record->start_date)->format('d M ,Y')
                    )
                    ->collapsible(),
                Group::make('end_date')
                    ->label(__('resources/schedules.table.columns.end_date.label'))
                    ->getTitleFromRecordUsing(
                        fn($record) => Jalalian::fromDateTime($record->end_date)->format('d M ,Y')
                    )
                    ->collapsible(),
            ])
            ->defaultGroup('start_date')
            ->columns([
                TextColumn::make('doctor.name')
                    ->label(__('resources/schedules.table.columns.doctor.label'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clinic.name')
                    ->label(__('resources/schedules.table.columns.clinics.label'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(Color::Emerald),

                TextColumn::make('days_of_week')
                    ->label(__('resources/schedules.table.columns.days_of_week.label'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        $days = [
                            0 => __('resources/schedules.schema.form.components.days_of_week.days.saturday'),
                            1 => __('resources/schedules.schema.form.components.days_of_week.days.sunday'),
                            2 => __('resources/schedules.schema.form.components.days_of_week.days.monday'),
                            3 => __('resources/schedules.schema.form.components.days_of_week.days.tuesday'),
                            4 => __('resources/schedules.schema.form.components.days_of_week.days.wednesday'),
                            5 => __('resources/schedules.schema.form.components.days_of_week.days.thursday'),
                            6 => __('resources/schedules.schema.form.components.days_of_week.days.friday'),
                        ];

                        return collect($state)->map(fn ($day) => $days[$day] ?? '')->join(',');
                    }),

                TextColumn::make('time_start')
                    ->label(__('resources/schedules.table.columns.time_start.label'))
                    ->time('h:i A')
                    ->sortable(),
                TextColumn::make('time_end')
                    ->label(__('resources/schedules.table.columns.time_end.label'))
                    ->time('h:i A')
                    ->sortable(),
                TextColumn::make('slot_duration')
                    ->label(__('resources/schedules.table.columns.slot_duration.label'))
                    ->numeric()
                    ->searchable()
                    ->suffix(__('resources/schedules.schema.form.components.slot_duration.minutes')),
                TextColumn::make('created_at')
                    ->label('ساخته شده در')
                    ->jalaliDateTime('d M ,Y H:i A')
                    ->since()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('به روز رسانی شده در')
                    ->jalaliDateTime('d M ,Y H:i A')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(fn (Schedule $schedule) => $schedule->slots()->delete()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
