<?php

namespace App\Filament\Resources\Slots\Schemas;

use App\Enums\AppointmentStatus;
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
                    DatePicker::make('date')
                        ->required()
                        ->date()
                        ->displayFormat('M d Y')
                        ->native(false),
                    TimePicker::make('start')
                        ->required()
                        ->time()
                        ->displayFormat('h:i A'),
                    TimePicker::make('end')
                        ->required()
                        ->time()
                        ->displayFormat('h:i A'),
                    Select::make('owner_id')
                        ->relationship('owner', 'name')
                        ->label('Owner')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),
                    Select::make('status')
                        ->options(SlotStatus::class)
                        ->required()
                        ->visibleOn(EditSlot::class)
                        ->native(false),
                ])->columnSpanFull(),
            ]);
    }
}
