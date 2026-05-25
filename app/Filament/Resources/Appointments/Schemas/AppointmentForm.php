<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Models\Slot;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('slot_id')
                        ->relationship('slot', 'start')
                        ->getOptionLabelFromRecordUsing(fn (Slot $slot) => $slot->start->format('h:i A'))
                        ->required()
                        ->visible(fn ($get): bool => filled($get('date')) && filled($get('doctor_id')))
                        ->searchable()
                        ->preload()
                        ->native(false),

                    DatePicker::make('date')
                        ->required()
                        ->date()
                        ->live()
                        ->extraAttributes(['id' => 'date']),
                    Select::make('doctor_id')
                        ->options(function ($get) {
                            $doctorsHaveDate = new Collection;
                            $date = Carbon::parse($get('date'))->format('Y-m-d');

                            $doctors = Filament::getTenant()->users()->whereHas('roles', function ($q) {
                                return $q->where('name', 'doctor');
                            });

                            foreach ($doctors as $doctor) {
                                $schedule = $doctor->schedules()->whereDate('date', $date);

                                if (! $schedule) {
                                    continue;
                                }

                                $doctorsHaveDate->push($doctor);
                            }

                            return Filament::getTenant()->users()->whereHas('roles', function ($query) {
                                return $query->where('name', 'doctor');
                            })
                                ->whereHas('schedules', function (Builder $query) use ($date) {
                                    return $query->whereDate('date', $date);
                                })
                                ->get()
                                ->pluck('name', 'id');
                        })
                        ->options(function (Get $get) {

                            if (! $selectedDate = $get('data')) {
                                return [];
                            }

                            $tenant = Filament::getTenant();
                            $selectedDate = Carbon::parse($selectedDate)->format('Y-m-d');


                            $doctors = $tenant->users()->whereHas('roles', function (Builder $query)  {
                                return $query->where('name', 'doctor');
                            });

                            return $doctors
                                ->whereHas('selectedDate', function (Builder $query) use ($selectedDate) {
                                    return $query->whereDate('selectedDate', $selectedDate);
                                })
                                ->get()
                                ->pluck('name', 'id');
                        })
                        ->visible(fn(Get $get) => filled($get('date')))
                        ->searchable()
                        ->preload()
                        ->native(false),
                    Textarea::make('description')
                        ->nullable(),
                ])->columnSpanFull(),
            ]);
    }
}
