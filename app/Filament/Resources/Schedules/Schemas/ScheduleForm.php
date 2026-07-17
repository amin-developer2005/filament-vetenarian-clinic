<?php

namespace App\Filament\Resources\Schedules\Schemas;

use App\Enums\PanelRole;
use App\Models\Clinic;
use Carbon\Carbon;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('clinic_id')
                        ->label(__('resources/schedules.schema.form.components.clinic.label'))
                        ->relationship(titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                            return $query
                                ->where('is_active', true);
                        })
                        ->required()
                        ->live()
                        ->searchable()
                        ->preload()
                        ->noOptionsMessage(__('resources/schedules.schema.form.components.clinic.no_options_message'))
                        ->afterStateUpdated(fn (Set $set) => $set('doctor_id', null))
                        ->native(false),
                    Select::make('doctor_id')
                        ->label(__('resources/schedules.schema.form.components.doctor.label'))
                        ->options(function (Get $get) {
                            if (! $clinicId = $get('clinic_id')) {
                                return [];
                            }

                            $clinic = Clinic::query()
                                ->find($clinicId);

                            return $clinic->users()
                                ->whereHas('roles', fn ($query) => $query->where('name', PanelRole::DOCTOR))
                                ->pluck('name', 'id');
                        })
                        ->required()
                        ->live()
                        ->disabled(fn (Get $get) => blank($get('clinic_id')))
                        ->searchable()
                        ->noOptionsMessage(__('resources/schedules.schema.form.components.doctor.no_options_message'))
                        ->native(false),
                ]),

                Section::make([
                    DatePicker::make('start_date')
                        ->label(__('resources/schedules.schema.form.components.start_date.label'))
                        ->required()
                        ->jalali()
                        ->afterOrEqual(today())
                        ->validationMessages([
                            'after_or_equal' => 'تاریخ شروع برنامه کاری نمی‌تواند قبل از امروز باشد.',
                        ])
                        ->closeOnDateSelection()
                        ->displayFormat('Y-m-d')
                        ->native(false),
                    DatePicker::make('end_date')
                        ->label(__('resources/schedules.schema.form.components.end_date.label'))
                        ->required()
                        ->jalali()
                        ->afterOrEqual('start_date')
                        ->validationMessages([
                            'after_or_equal' => 'تاریخ پایان باید بعد از تاریخ شروع یا برابر با آن باشد.',
                        ])
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
                            Carbon::SATURDAY => __('resources/schedules.schema.form.components.days_of_week.days.saturday'),
                            Carbon::SUNDAY => __('resources/schedules.schema.form.components.days_of_week.days.sunday'),
                            Carbon::MONDAY => __('resources/schedules.schema.form.components.days_of_week.days.monday'),
                            Carbon::TUESDAY => __('resources/schedules.schema.form.components.days_of_week.days.tuesday'),
                            Carbon::WEDNESDAY => __('resources/schedules.schema.form.components.days_of_week.days.wednesday'),
                            Carbon::THURSDAY => __('resources/schedules.schema.form.components.days_of_week.days.thursday'),
                            Carbon::FRIDAY => __('resources/schedules.schema.form.components.days_of_week.days.friday'),
                        ])
                        ->columns(2),
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
