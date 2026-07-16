<?php

namespace App\Filament\Owner\Resources\Appointments\Tables;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('clinic.name')
                    ->label(__('resources/appointments.table.columns.clinic'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('animal.name')
                    ->label(__('resources/appointments.table.columns.animal'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slot.schedule.doctor.name')
                    ->label(__('resources/appointments.table.columns.doctor')),

                TextColumn::make('slot.date')
                    ->label(__('resources/appointments.table.columns.date'))
                    ->jalaliDate('d M ,Y')
                    ->sortable(),

                TextColumn::make('slot.start_time')
                    ->label(__('resources/appointments.table.columns.time'))
                    ->formatStateUsing(
                        fn (Appointment $record) => Carbon::parse($record->slot->start_time)->format('h:i A').' - '.Carbon::parse($record->slot->end_time)->format('h:i A')
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
                    ->toggleable(),
            ])
            ->emptyStateHeading(__('resources/appointments.table.emptyState.heading'))
            ->emptyStateDescription(__('resources/appointments.table.emptyState.description'))
            ->filters([
                SelectFilter::make('clinic_id')
                    ->label(__('resources/appointments.table.filters.clinic'))
                    ->relationship('clinic', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),
                SelectFilter::make('status')
                    ->label(__('resources/appointments.table.filters.status'))
                    ->options(AppointmentStatus::class)
                    ->searchable()
                    ->preload()
                    ->native(false),
                        SelectFilter::make('doctor')
                            ->label(__('resources/appointments.table.filters.doctor'))
                            ->searchable()
                            ->relationship('slot.schedule.doctor', 'name')
                            ->preload()
                            ->native(false),

                Filter::make('created_at')
                    ->label(__('resources/appointments.table.filters.created_at'))
                    ->schema([
                        DatePicker::make('from_booked_date')
                            ->label(__('resources/appointments.table.filters.from_booked_date'))
                            ->native(false)
                            ->jalali(),
                        DatePicker::make('to_booked_date')
                            ->label(__('resources/appointments.table.filters.to_booked_date'))
                            ->native(false)
                            ->jalali(),
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
            ->filtersFormWidth(Width::FiveExtraLarge)
            ->recordActions([
                Action::make('cancel')
                    ->label(__('resources/appointments.table.actions.cancel.label'))
                    ->button()
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn(Appointment $appointment) => app(AppointmentService::class)->canBeCanceled($appointment) && Filament::auth()->user()->can('cancel', $appointment))
                    ->action(function (Appointment $appointment) {
                        app(AppointmentService::class)->cancel($appointment);
                        $appointment->slot->free();
                    }),
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
