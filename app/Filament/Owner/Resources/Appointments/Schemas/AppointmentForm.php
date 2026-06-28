<?php

namespace App\Filament\Owner\Resources\Appointments\Schemas;

use App\Enums\PanelRole;
use App\Enums\SlotStatus;
use App\Models\Animal;
use App\Models\Slot;
use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('انتخاب کلینیک مورد نظر')
                        ->schema([
                            Select::make('clinic_id')
                                ->label(__('resources/appointments.schema.form.components.clinic_id.label'))
                                ->relationship('clinic', 'name')
                                ->required()
                                ->live()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->afterStateUpdated(function (Set $set) {
                                    $set('animal_id', null);
                                    $set('doctor_id', null);
                                    $set('selectedDate', null);
                                }),
                            Select::make('animal_id')
                                ->label(__('resources/appointments.schema.form.components.animal_id.label'))
                                ->noOptionsMessage(__('resources/appointments.schema.form.components.animal_id.panel.owner.no_options_message'))
                                ->options(function () {
                                    $owner = Filament::auth()->user();

                                    return Animal::query()
                                        ->where('owner_id', $owner->id)
                                        ->get();
                                })
                                ->required()
                                ->live()
                                ->searchable()
                                ->preload()
                                ->disabled(fn (Get $get) => blank($get('clinic_id')))
                                ->native(false),
                        ]),
                    Wizard\Step::make('اطلاعات نوبت')
                        ->schema([
                            DatePicker::make('selectedDate')
                                ->label(__('resources/appointments.schema.form.components.selectedDate.label'))
                                ->required()
                                ->date()
                                ->live()
                                ->maxDate(today())
                                ->afterOrEqual(today())
                                ->disabled(fn (Get $get) => blank($get('clinic_id')))
                                ->native(false)
                                ->dehydrated(false)
                                ->afterStateUpdated(function (Set $set) {
                                    $set('doctor_id', null);
                                    $set('slot_id', null);
                                }),
                            Select::make('doctor_id')
                                ->label(__('resources/appointments.schema.form.components.doctor_id.label'))
                                ->noOptionsMessage(__('resources/appointments.schema.form.components.doctor_id.no_options_message'))
                                ->options(function (Get $get) {
                                    if (! $clinicId = $get('clinic_id')) {
                                        return [];
                                    }

                                    if (! $selectedDate = $get('selectedDate')) {
                                        return [];
                                    }

                                    $doctors = User::query()->whereHas('roles',
                                        fn (Builder $query) => $query->where('name', PanelRole::Owner)
                                    );

                                    $dayOfWeek = Carbon::parse($selectedDate)->dayOfWeek;

                                    return $doctors->whereHas('schedules', function (Builder $query) use ($clinicId, $selectedDate, $dayOfWeek) {
                                        $query
                                            ->where('clinic_id', $clinicId)
                                            ->whereDate('start_date', '<=', $selectedDate)
                                            ->whereDate('end_date', '>=', $selectedDate)
                                            ->whereJsonContains('day_of_week', $dayOfWeek);
                                    })->pluck('name', 'id');
                                })
                                ->required()
                                ->live()
                                ->searchable()
                                ->preload()
                                ->disabled(fn (Get $get) => blank($get('clinic_id')) && blank($get('selectedDate')))
                                ->afterStateUpdated(fn (Set $set) => $set('slot_id', null))
                                ->native(false),
                            Select::make('slot_id')
                                ->label(__('resources/appointments.schema.form.components.slot_id.label'))
                                ->noOptionsMessage(__('resources/appointments.schema.form.components.slot_id.no_options_message'))
                                ->options(function (Get $get) {
                                    if (! $clinicId = $get('clinic_id')) {
                                        return [];
                                    }

                                    if (! $selectedDate = $get('selectedDate')) {
                                        return [];
                                    }

                                    if (! $doctorId = $get('doctor_id')) {
                                        return [];
                                    }

                                    $dayOfWeek = Carbon::parse($selectedDate)->dayOfWeek;

                                    return Slot::query()
                                        ->whereHas('schedules', function (Builder $query) use ($clinicId, $selectedDate, $doctorId, $dayOfWeek) {
                                            $query
                                                ->where('clinic_id', $clinicId)
                                                ->where('doctor_id', $doctorId)
                                                ->whereDate('start_date', '<=', $selectedDate)
                                                ->whereDate('end_date', '>=', $selectedDate)
                                                ->whereJsonContains('day_of_week', $dayOfWeek);
                                        })
                                        ->whereDate('date', $selectedDate)
                                        ->whereDoesntHave('appointment')
                                        ->where('status', SlotStatus::Available)
                                        ->get()
                                        ->mapWithKeys(fn (Slot $slot) => [
                                            $slot->id => Carbon::parse($slot->start_time)->format('H:i A').' - '.Carbon::parse($slot->end_time)->format('H:i A'),
                                        ]);
                                })
                                ->required()
                                ->live()
                                ->searchable()
                                ->preload()
                                ->disabled(fn (Get $get) => blank($get('clinic_id')) && blank($get('selectedDate')) && blank($get('doctor_id')))
                                ->native(false),
                        ]),

                    Wizard\Step::make(__('resources/appointments.schema.form.components.sections.medical_info'))
                        ->schema([
                            Textarea::make('description')
                                ->label(__('resources/appointments.schema.form.components.description.label'))
                                ->nullable()
                                ->rows(5)
                                ->columnSpanFull(),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}
