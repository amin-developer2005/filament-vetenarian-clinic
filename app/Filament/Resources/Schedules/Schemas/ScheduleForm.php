<?php

namespace App\Filament\Resources\Schedules\Schemas;

use App\Enums\PanelRole;
use App\Models\Clinic;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('clinic_id')
                        ->label(__('resources/schedules.schema.form.components.clinic.label'))
                        ->relationship('clinic', 'name')
                        ->required()
                        ->live()
                        ->searchable()
                        ->preload()
                        ->noOptionsMessage(__('resources/schedules.schema.form.components.clinic.no_options_message'))
                        ->afterStateUpdated(fn(Set $set) => $set('doctor_id', null))
                        ->native(false),
                    Select::make('doctor_id')
                        ->label(__('resources/schedules.schema.form.components.doctor.label'))
                        ->options( function (Get $get) {
                            if (! $clinicId = $get('clinic_id')) {
                                return [];
                            }

                            $clinic = Clinic::query()
                                ->find($clinicId);

                            return $clinic->users()
                                ->whereHas('roles', fn($query) => $query->where('name', PanelRole::DOCTOR))
                                ->pluck('name', 'id');
                        })
                        ->required()
                        ->live()
                        ->disabled(fn(Get $get) => blank($get('clinic_id')))
                        ->searchable()
                        ->noOptionsMessage(__('resources/schedules.schema.form.components.doctor.no_options_message'))
                        ->native(false),
                ]),

                Section::make([
                    DatePicker::make('start_date')
                        ->label(__('resources/schedules.schema.form.components.start_date.label'))
                        ->required()
                        ->date()
                        ->closeOnDateSelection()
                        ->displayFormat('Y-m-d')
                        ->native(false),
                    DatePicker::make('end_date')
                        ->label(__('resources/schedules.schema.form.components.end_date.label'))
                        ->required()
                        ->date()
                        ->closeOnDateSelection()
                        ->displayFormat('Y-m-d')
                        ->afterOrEqual('start_date')
                        ->native(false),
                ]),
                Section::make([
                    CheckboxList::make('days_of_week')
                        ->label(__('resources/schedules.schema.form.components.days_of_week.label'))
                        ->required()
                        ->options([
                            0 => __('resources/schedules.schema.form.components.days_of_week.days.saturday'),
                            1 => __('resources/schedules.schema.form.components.days_of_week.days.sunday'),
                            2 => __('resources/schedules.schema.form.components.days_of_week.days.monday'),
                            3 => __('resources/schedules.schema.form.components.days_of_week.days.tuesday'),
                            4 => __('resources/schedules.schema.form.components.days_of_week.days.wednesday'),
                            5 => __('resources/schedules.schema.form.components.days_of_week.days.thursday'),
                            6 => __('resources/schedules.schema.form.components.days_of_week.days.friday'),
                        ])
                        ->columns(3),
                ]),

                Section::make([
                    TimePicker::make('time_start')
                        ->label(__('resources/schedules.schema.form.components.time_start.label'))
                        ->required()
                        ->closeOnDateSelection()
                        ->seconds(false),
                    TimePicker::make('time_end')
                        ->label(__('resources/schedules.schema.form.components.time_end.label'))
                        ->required()
                        ->closeOnDateSelection()
                        ->seconds(false)
                        ->after('time_start'),
                ]),
                Section::make([
                    TextInput::make('slot_duration')
                        ->label(__('resources/schedules.schema.form.components.slot_duration.label'))
                        ->required()
                        ->numeric()
                        ->integer()
                        ->minValue(5)
                        ->step(5)
                        ->suffix(__('resources/schedules.schema.form.components.slot_duration.minutes')),
                ]),
            ]);
    }
}
