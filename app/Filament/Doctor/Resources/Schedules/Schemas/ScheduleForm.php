<?php

namespace App\Filament\Doctor\Resources\Schedules\Schemas;

use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make()
                    ->steps([
                        Step::make('انتخاب گلینیک')
                            ->schema([
                                Select::make('clinic_id')
                                    ->label(__('resources/schedules.schema.form.components.clinic.label'))
                                    ->relationship('clinic', 'name', modifyQueryUsing: function (Builder $query): Builder {
                                        $clinicIds = Filament::auth()->user()->clinics->pluck('id');

                                        return $query
                                            ->where('is_active', true)
                                            ->whereHas('users', function (Builder $query) use ($clinicIds) {
                                                return $query->whereIn('clinic_id', $clinicIds);
                                            });
                                    })
                                    ->required()
                                    ->live()
                                    ->searchable()
                                    ->preload()
                                    ->noOptionsMessage(__('resources/schedules.schema.form.components.clinic.no_options_message'))
                                    ->afterStateUpdated(fn (Set $set) => $set('doctor_id', null))
                                    ->native(false),
                            ]),
                        Step::make('تعیین تاریخ')
                            ->schema([
                                Grid::make()
                                    ->schema([
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
                            ]),
                        Step::make('تعیین روز های هفته برنامه')
                            ->schema([
                                Grid::make()
                                    ->schema([
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
                            ]),
                        Step::make('تعیین ساعات و مدت زمان وقت ها')
                            ->schema([
                                Grid::make()
                                    ->schema([
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
                                        TextInput::make('slot_duration')
                                            ->label(__('resources/schedules.schema.form.components.slot_duration.label'))
                                            ->required()
                                            ->numeric()
                                            ->integer()
                                            ->minValue(5)
                                            ->step(5)
                                            ->suffix(__('resources/schedules.schema.form.components.slot_duration.minutes')),
                                    ]),
                            ]),
                    ])->columnSpanFull(),

            ]);
    }
}
