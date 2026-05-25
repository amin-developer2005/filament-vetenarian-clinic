<?php

namespace App\Filament\Resources\Slots\Schemas;

use App\Enums\SlotStatus;
use App\Filament\Resources\Slots\Pages\EditSlot;
use App\Models\Schedule;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
 use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('schedule_id')
                        ->label(__('resources/slots.schema.form.components.schedule.label'))
                        ->relationship('schedule', 'start_date')
                        ->required()
                        ->getOptionLabelFromRecordUsing(fn(Schedule $schedule) => $schedule->start_date->format('Y-m-d'))
                        ->searchable()
                        ->preload()
                        ->native(false),
                    DatePicker::make('date')
                        ->label(__('resources/slots.schema.form.components.date.label'))
                        ->required()
                        ->date()
                        ->seconds(false)
                        ->displayFormat('Y-m-d')
                        ->native(false),
                ]),
                Section::make([
                    TimePicker::make('start_time')
                        ->label(__('resources/slots.schema.form.components.start_time.label'))
                        ->required()
                        ->time()
                        ->seconds(false)
                        ->displayFormat('h:i A'),
                    TimePicker::make('end_time')
                        ->label(__('resources/slots.schema.form.components.end_time.label'))
                        ->required()
                        ->time()
                        ->seconds(false)
                ]),
                Section::make([
                    Select::make('status')
                        ->options(SlotStatus::class)
                        ->required()
                        ->visibleOn(EditSlot::class)
                        ->native(false),
                ])->visibleOn(EditSlot::class),
            ]);
    }
}
