<?php

namespace App\Filament\Resources\Doctors\RelationManagers;

use App\Filament\Resources\Schedules\ScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    protected static ?string $relatedResource = ScheduleResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('clinic.name')
                    ->label(__('resources/schedules.table.columns.clinics.label'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(Color::Emerald),
                TextColumn::make('start_date')
                    ->label(__('resources/schedules.table.columns.start_date.label'))
                    ->date(),
                TextColumn::make('end_date')
                    ->label(__('resources/schedules.table.columns.end_date.label'))
                    ->date(),
                TextColumn::make('time_start')
                    ->label(__('resources/schedules.table.columns.time_start.label'))
                    ->time('h:i A')
                    ->sortable(),
                TextColumn::make('time_end')
                    ->label(__('resources/schedules.table.columns.time_end.label'))
                    ->time('h:i A')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-s-plus')
                    ->iconSize(IconSize::TwoExtraLarge)
                    ->label(__('resources/schedules.pages.index.actions.create')),
            ])
            ->emptyStateHeading(
                __('resources/doctors.relationManagers.schedules.empty.heading')
            )
            ->emptyStateDescription(
                __('resources/doctors.relationManagers.schedules.empty.description')
            )
            ->emptyStateIcon('heroicon-o-calendar-days');
    }
}
