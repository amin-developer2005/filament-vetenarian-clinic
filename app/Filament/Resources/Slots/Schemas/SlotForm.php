<?php

namespace App\Filament\Resources\Slots\Schemas;

use App\Enums\SlotStatus;
use App\Filament\Resources\Slots\Pages\EditSlot;
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
                        ->relationship('schedule', 'date')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),
                    TimePicker::make('start')
                        ->label(__('resources/slots.schema.form.components.start.label'))
                        ->required()
                        ->time()
                        ->displayFormat('h:i A'),
                    TimePicker::make('end')
                        ->label(__('resources/slots.schema.form.components.end.label'))
                        ->required()
                        ->time()
                        ->displayFormat('h:i A'),
                    Select::make('status')
                        ->options(SlotStatus::class)
                        ->required()
                        ->visibleOn(EditSlot::class)
                        ->native(false),
                ])->columnSpanFull(),
            ]);
    }
}
