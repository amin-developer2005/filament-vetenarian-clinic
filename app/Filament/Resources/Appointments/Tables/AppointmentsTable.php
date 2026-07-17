<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Enums\AppointmentStatus;
use App\Filament\Schemas\AppointmentInformation;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Morilog\Jalali\Jalalian;

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
                    ->jalaliDate('d M ,Y')
                    ->sortable(),

                TextColumn::make('day_of_week')
                    ->label(__('resources/slots.table.columns.day_of_week'))
                    ->badge()
                    ->color(Color::Emerald)
                    ->state(
                        fn($state) => Carbon::parse($state->slot->date)->locale(app()->getLocale())->dayName
                    ),

                TextColumn::make('slot.start_time')
                    ->label(__('resources/appointments.table.columns.time'))
                    ->formatStateUsing(
                        fn (Appointment $record) => Jalalian::fromCarbon(
                            Carbon::parse($record->slot->start_time)
                            )->format('H:i A').'  تا '.
                            Jalalian::fromCarbon(Carbon::parse($record->slot->end_time))->format('H:i A')
                    ),

                TextColumn::make('status')
                    ->label(__('resources/appointments.table.columns.status'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('resources/appointments.table.columns.created_at'))
                    ->since()
                    ->jalaliDateTime('d M ,Y H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

                Filter::make('created_at')
                    ->label(__('resources/appointments.table.filters.created_at'))
                    ->schema([
                        DatePicker::make('from_booked_date')
                            ->jalali()
                            ->label(__('resources/appointments.table.filters.from_booked_date'))
                            ->date(),
                        DatePicker::make('to_booked_date')
                            ->jalali()
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
                    ->visible(
                        fn (Appointment $appointment) => app(AppointmentService::class)->canBeConfirmed($appointment) && Filament::auth()->user()->can('confirm', $appointment),
                    )
                    ->action(fn (Appointment $appointment) => app(AppointmentService::class)->confirm($appointment))
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
                        fn (Appointment $appointment) => app(AppointmentService::class)->canBeRejected($appointment) && Filament::auth()->user()->can('reject', $appointment)
                    )
                    ->action(function (Appointment $appointment) {
                        app(AppointmentService::class)->reject($appointment);
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
                    ->visible(fn (Appointment $appointment) => app(AppointmentService::class)->canBeCheckedIn($appointment) && Filament::auth()->user()->can('checkIn', $appointment))
                    ->action(fn (Appointment $appointment) => app(AppointmentService::class)->checkIn($appointment))
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
                    ->visible(fn (Appointment $appointment) => app(AppointmentService::class)->canBeStartedVisiting($appointment) && Filament::auth()->user()->can('startVisit', $appointment))
                    ->action(fn (Appointment $appointment) => app(AppointmentService::class)->startVisit($appointment))
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
                        fn (Appointment $appointment) => app(AppointmentService::class)->canBeCanceled($appointment) && Filament::auth()->user()->can('cancel', $appointment),
                    )
                    ->action(function (Appointment $appointment) {
                        app(AppointmentService::class)->cancel($appointment);
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
                        fn (Appointment $appointment) => app(AppointmentService::class)->canBeCompleted($appointment) && Filament::auth()->user()->can('complete', $appointment),
                    )
                    ->action(fn (Appointment $appointment) => app(AppointmentService::class)->complete($appointment))
                    ->after(fn () => Notification::make()
                        ->title(__('resources/appointments.table.actions.complete.notification'))
                        ->success()
                        ->send()
                    ),
                ViewAction::make()
                    ->modalHeading('جزئیات کامل نوبت')
                    ->color('info')
                    ->slideOver()
                    ->modalWidth(Width::FiveExtraLarge)
                    ->schema(AppointmentInformation::make()),
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
