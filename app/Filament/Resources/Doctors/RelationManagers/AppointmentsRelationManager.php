<?php

namespace App\Filament\Resources\Doctors\RelationManagers;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppointmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'appointments';

    protected static ?string $relatedResource = AppointmentResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('animal.name')
                    ->label(__('resources/appointments.table.columns.animal'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('owner.name')
                    ->label(__('resources/appointments.table.columns.owner'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slot.date')
                    ->label(__('resources/appointments.table.columns.date'))
                    ->date()
                    ->sortable(),

                TextColumn::make('slot.start_time')
                    ->label(__('resources/appointments.table.columns.time'))
                    ->formatStateUsing(
                        fn (Appointment $record) => Carbon::parse($record->slot->start_time)->format('H:i').' - '.Carbon::parse($record->slot->end_time)->format('H:i')
                    ),

                TextColumn::make('status')
                    ->label(__('resources/appointments.table.columns.status'))
                    ->badge()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-s-plus')
                    ->iconSize(IconSize::TwoExtraLarge)
                    ->visible(
                        fn() => $this->getRelationship()->getParent()->hasSchedules()
                    )
                    ->label(__('resources/appointments.pages.index.actions.create')),
            ])
            ->emptyStateHeading(
                __('resources/doctors.relationManagers.appointments.empty.heading')
            )
            ->emptyStateDescription(
                __('resources/doctors.relationManagers.appointments.empty.description')
            )
            ->emptyStateIcon('heroicon-o-calendar-days');
    }
}
