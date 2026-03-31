<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Enums\AppointmentStatus;
use App\Filament\Resources\Appointments\Pages\EditAppointment;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('slot_id')
                        ->relationship('slot', 'start', modifyQueryUsing: function ($query) {
                            $query->whereHas('clinics', function ($q) {
                                $userClinicIds = Filament::auth()->user()->clinics()->pluck('clinics.id');

                                return $q->whereIn('clinics.id', $userClinicIds);
                            });
                        } )
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),
                    Textarea::make('description')
                        ->nullable(),
                ])->columnSpanFull(),
            ]);
    }
}
