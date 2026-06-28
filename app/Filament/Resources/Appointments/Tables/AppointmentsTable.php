<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentsTable
{
    public static function configure(Table $table): Table
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

                TextColumn::make('slot.schedule.doctor.name')
                    ->label(__('resources/appointments.table.columns.doctor')),

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

                TextColumn::make('created_at')
                    ->label(__('resources/appointments.table.columns.created_at'))
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('owner_id')
                    ->label(__('resources/appointments.table.filters.owner'))
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),
                SelectFilter::make('status')
                    ->label(__('resources/appointments.table.filters.status'))
                    ->options(AppointmentStatus::class)
                    ->searchable()
                    ->native(false),
                /*
                SelectFilter::make('doctor_id')
                    ->label(__('resources/appointments.table.filters.doctor'))
                    ->relationship('slot.schedule', 'doctor.name')
                    ->searchable()
                    ->preload(),*/
                Filter::make('doctor_id')
                    ->label(__('resources/appointments.table.filters.doctor'))
                    ->schema([
                        Select::make('doctor')
                            ->label(__('resources/appointments.table.filters.doctor.label'))
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ])->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['doctor'] ?? null,
                            fn ($q, $v) => $q->where('slot.schedule.doctor_id', $v)
                        );
                    }),

                Filter::make('created_at')
                    ->label(__('resources/appointments.table.filters.created_at'))
                    ->schema([
                        DatePicker::make('from_booked_date')
                            ->label(__('resources/appointments.table.filters.from_booked_date'))
                            ->date(),
                        DatePicker::make('to_booked_date')
                            ->label(__('resources/appointments.table.filters.to_booked_date'))
                            ->date(),
                    ])->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['from_booked_date'] ?? null,
                                fn ($q, $v) => $q->whereDate('created_at', '>=', $v)
                            )
                            ->when(
                                $data['to_booked_date'] ?? null,
                                fn ($q, $v) => $q->whereDate('created_at', '<=', $v)
                            );
                    }),
            ], FiltersLayout::Modal)
            ->recordActions([
                Action::make('confirm')
                    ->label(__('resources/appointments.table.actions.confirm.label'))
                    ->button()
                    ->color(Color::Emerald)
                    ->icon('heroicon-o-check')
                    ->visible(fn (Appointment $appointment) => $appointment->canBeConfirmedBy(Filament::auth()->user()))
                    ->action(fn (Appointment $appointment) => $appointment->confirm())
                    ->after(fn () => Notification::make()
                        ->title(__('resources/appointments.table.actions.confirm.notification'))
                        ->success()
                        ->send()
                    ),
                Action::make('reject')
                    ->label(__('resources/appointments.table.actions.reject.label'))
                    ->icon('heroicon-o-x-circle')
                    ->button()
                    ->color(Color::Red)
                    ->visible(
                        fn (Appointment $appointment) => $appointment->canBeRejectedBy(Filament::auth()->user())
                    )
                    ->action(function (Appointment $appointment) {
                        $appointment->reject();
                        $appointment->slot->free();
                    })
                    ->after(fn () => Notification::make()
                        ->title(__('resources/appointments.table.actions.reject.notification'))
                        ->success()
                        ->send()
                    ),
                Action::make('check_in')
                    ->label(__('resources/appointments.table.actions.check_in.label'))
                    ->button()
                    ->color(Color::Emerald)
                    ->icon('heroicon-o-check')
                    ->visible(fn (Appointment $appointment) => $appointment->canBeCheckedInBy(Filament::auth()->user()))
                    ->action(fn (Appointment $appointment) => $appointment->checkIn())
                    ->after(fn () => Notification::make()
                        ->title(__('resources/appointments.table.actions.check_in.notification'))
                        ->success()
                        ->send()
                    ),
                Action::make('start_visit')
                    ->label(__('resources/appointments.table.actions.start_visit.label'))
                    ->button()
                    ->color(Color::Cyan)
                    ->icon('heroicon-o-play')
                    ->visible(fn (Appointment $appointment) => $appointment->canBeStartedVisitingBy(Filament::auth()->user()))
                    ->action(fn (Appointment $appointment) => $appointment->startVisit())
                    ->after(fn () => Notification::make()
                        ->title(__('resources/appointments.table.actions.start_visit.notification'))
                        ->success()
                        ->send()
                    ),
                Action::make('cancel')
                    ->label(__('resources/appointments.table.actions.cancel.label'))
                    ->button()
                    ->color(Color::Red)
                    ->icon('heroicon-o-x-mark')
                    ->visible(
                        fn (Appointment $appointment) => $appointment->canBeCanceledBy(Filament::auth()->user()),
                    )
                    ->action(function (Appointment $appointment) {
                        $appointment->cancel();
                        $appointment->slot->free();
                    })->after(fn () => Notification::make()
                    ->title(__('resources/appointments.table.actions.cancel.notification'))
                    ->success()
                    ->send()
                    ),
                Action::make('complete')
                    ->label(__('resources/appointments.table.actions.complete.label'))
                    ->icon('heroicon-o-check-badge')
                    ->button()
                    ->color(Color::Cyan)
                    ->visible(
                        fn (Appointment $appointment) => $appointment->canBeCompletedBy(Filament::auth()->user())
                    )
                    ->action(fn (Appointment $appointment) => $appointment->complete())
                    ->after(fn () => Notification::make()
                        ->title(__('resources/appointments.table.actions.complete.notification'))
                        ->success()
                        ->send()
                    ),
                EditAction::make(),
                DeleteAction::make()
                    ->after(fn (Appointment $appointment) => $appointment->slot->free()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(fn (Appointment $appointment) => $appointment->slot->free()),
                ]),
            ]);
    }
}
