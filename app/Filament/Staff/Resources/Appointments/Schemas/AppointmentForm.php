<?php

namespace App\Filament\Staff\Resources\Appointments\Schemas;

use App\Enums\PanelRole;
use App\Enums\SlotStatus;
use App\Models\Animal;
use App\Models\Slot;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Morilog\Jalali\Jalalian;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make()
                    ->steps([
                        Wizard\Step::make('انتخاب کلینیک و حیوان مورد نظر')
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        Section::make(__('resources/appointments.schema.form.components.sections.info'))
                                            ->schema([
                                                Select::make('clinic_id')
                                                    ->label(__('resources/appointments.schema.form.components.clinic_id.label'))
                                                    ->relationship('clinic', 'name', modifyQueryUsing: function (Builder $query) {
                                                        return $query
                                                            ->where('is_active', true);
                                                    })
                                                    ->required()
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set) {
                                                        $set('doctor_id', null);
                                                        $set('selectedDate', null);
                                                    })
                                                    ->native(false),

                                                Select::make('owner_id')
                                                    ->label(__('resources/appointments.schema.form.components.owner_id.label'))
                                                    ->noOptionsMessage(__('resources/appointments.schema.form.components.owner_id.no_options_message'))
                                                    ->options(function () {
                                                        return User::query()
                                                            ->whereHas('roles', fn (Builder $query) => $query->where('name', PanelRole::Owner))
                                                            ->pluck('name', 'id');
                                                    })
                                                    ->required()
                                                    ->live()
                                                    ->searchable()
                                                    ->preload()
                                                    ->afterStateUpdated(fn (Set $set) => $set('animal_id', null)),

                                                Select::make('animal_id')
                                                    ->label(__('resources/appointments.schema.form.components.animal_id.label'))
                                                    ->noOptionsMessage(__('resources/appointments.schema.form.components.animal_id.panel.admin.no_options_message'))
                                                    ->required()
                                                    ->options(function (Get $get) {
                                                        if (! $ownerId = $get('owner_id')) {
                                                            return [];
                                                        }

                                                        return Animal::query()
                                                            ->where('owner_id', $ownerId)
                                                            ->get()
                                                            ->pluck('name', 'id');
                                                    })
                                                    ->live()
                                                    ->searchable()
                                                    ->disabled(fn (Get $get) => blank($get('owner_id')))
                                                    ->preload()
                                                    ->native(false),
                                            ]),
                                    ]),
                            ]),
                        Wizard\Step::make('اطلاعات نوبت')
                            ->schema([
                                DatePicker::make('selectedDate')
                                    ->label(__('resources/appointments.schema.form.components.selected_date.label'))
                                    ->required()
                                    ->date()
                                    ->displayFormat('Y-M-d')
                                    ->closeOnDateSelection()
                                    ->jalali()
                                    ->live()
                                    ->dehydrated(false)
                                    ->disabled(fn (Get $get) => blank($get('clinic_id')))
                                    ->afterOrEqual(today())
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('doctor_id', null);
                                        $set('slot_id', null);
                                    })
                                    ->native(false),

                                Select::make('doctor_id')
                                    ->label(__('resources/appointments.schema.form.components.doctor_id.label'))
                                    ->noOptionsMessage(__('resources/appointments.schema.form.components.doctor_id.no_options_message'))
                                    ->required()
                                    ->options(function (Get $get) {
                                        $clinicId = $get('clinic_id');
                                        $selectedDate = $get('selectedDate');

                                        if (! $clinicId) {
                                            return [];
                                        }

                                        if (! $selectedDate) {
                                            return [];
                                        }

                                        $dayOfWeek = Carbon::parse($selectedDate)->dayOfWeek;

                                        $doctors = User::query()->whereHas('roles',
                                            fn (Builder $query) => $query->where('name', PanelRole::DOCTOR)
                                        );

                                        return $doctors->whereHas('schedules', function (Builder $query) use ($selectedDate, $dayOfWeek, $clinicId) {
                                            return $query
                                                ->where('clinic_id', $clinicId)
                                                ->whereDate('start_date', '<=', $selectedDate)
                                                ->whereDate('end_date', '>=', $selectedDate)
                                                ->whereJsonContains('days_of_week', $dayOfWeek);
                                        })->pluck('name', 'id');
                                    })
                                    ->disabled(fn (Get $get) => blank($get('selectedDate')))
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('slot_id', null))
                                    ->native(false),

                                Select::make('slot_id')
                                    ->label(__('resources/appointments.schema.form.components.slot_id.label'))
                                    ->noOptionsMessage(__('resources/appointments.schema.form.components.slot_id.no_options_message'))
                                    ->options(function (Get $get) {
                                        $clinicId = $get('clinic_id');
                                        $selectedDate = $get('selectedDate');
                                        $doctorId = $get('doctor_id');

                                        if (! $clinicId) {
                                            return [];
                                        }

                                        if (! $doctorId) {
                                            return [];
                                        }

                                        if (! $selectedDate) {
                                            return [];
                                        }

                                        $dayOfWeek = Carbon::parse($selectedDate)->dayOfWeek;

                                        return Slot::query()
                                            ->whereHas('schedule', function (Builder $query) use ($clinicId, $doctorId, $selectedDate, $dayOfWeek) {
                                                return $query
                                                    ->where('clinic_id', $clinicId)
                                                    ->where('doctor_id', $doctorId)
                                                    ->whereDate('start_date', '<=', $selectedDate)
                                                    ->whereDate('end_date', '>=', $selectedDate)
                                                    ->whereJsonContains('days_of_week', $dayOfWeek);
                                            })
                                            ->whereDate('date', $selectedDate)
                                            ->where('status', SlotStatus::Available)
                                            ->whereDoesntHave('appointment')
                                            ->get()
                                            ->mapWithKeys(fn (Slot $slot) => [
                                                $slot->id => Jalalian::fromCarbon(
                                                        Carbon::parse($slot->start_time)
                                                    )->format('H:i A').'  تا '.
                                                    Jalalian::fromCarbon(Carbon::parse($slot->end_time))->format('H:i A'),
                                            ]);
                                    })
                                    ->required()
                                    ->disabled(fn ($get): bool => blank($get('doctor_id')))
                                    ->live()
                                    ->searchable()
                                    ->preload()
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
