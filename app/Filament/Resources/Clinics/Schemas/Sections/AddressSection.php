<?php

namespace App\Filament\Resources\Clinics\Schemas\Sections;

use Dotswan\MapPicker\Facades\MapPicker;
use Dotswan\MapPicker\Fields\Map;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\IconSize;

class AddressSection extends Section
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->build();
    }

    private function build(): void
    {
        $this
            ->label(__('resources/clinics.schema.form.sections.address.label'))
            ->icon('heroicon-o-building-office-2')
            ->description(__('resources/clinics.schema.form.sections.address.description'))
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextInput::make('address.country')
                            ->label(__('resources/clinics.schema.address_form.components.country.label'))
                            ->placeholder(__('resources/clinics.schema.address_form.components.country.placeholder'))
                            ->required()
                            ->string()
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('address.province')
                            ->label(__('resources/clinics.schema.address_form.components.province.label'))
                            ->placeholder(__('resources/clinics.schema.address_form.components.province.placeholder'))
                            ->required()
                            ->string()
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('address.city')
                            ->label(__('resources/clinics.schema.address_form.components.city.label'))
                            ->placeholder(__('resources/clinics.schema.address_form.components.city.placeholder'))
                            ->required()
                            ->string()
                            ->maxLength(255)
                            ->columnSpan(1),
                    ]),
                Textarea::make('address.address')
                    ->label(__('resources/clinics.schema.address_form.components.address.label'))
                    ->placeholder(__('resources/clinics.schema.address_form.components.address.placeholder'))
                    ->rows(3)
                    ->columnSpanFull()
                    ->maxLength(500),

                Grid::make(3)
                    ->schema([
                        Actions::make([
                            Action::make('map-picker')
                                ->label(__('resources/clinics.schema.form.sections.address.actions.map-picker.label'))
                                ->modalHeading(__('resources/clinics.schema.form.sections.address.actions.map-picker.modalHeading'))
                                ->modalCloseButton()
                                ->iconSize(IconSize::ExtraLarge)
                                ->icon('heroicon-o-map-pin')
                                ->schema([
                                    Map::make('location')
                                        ->zoom(12)
                                        ->reactive()
                                        ->defaultLocation(34.6416, 50.8746)
                                        ->afterStateUpdated(function ($state, Set $set) {
                                            $location = $state ?? [];

                                            if (! $location) {
                                                return;
                                            }

                                            $set('address.latitude', $location['lat'] ?? null);
                                            $set('address.longitude', $location['lng'] ?? null);
                                        })
                                        ->live()
                                        ->draggable()
                                        ->clickable(true),
                                ])->action(function (array $data, Set $set) {
                                    $location = $data['location'] ?? [];

                                    if (! $location) {
                                        return;
                                    }

                                    $set('address.latitude', $location['lat'] ?? null);
                                    $set('address.longitude', $location['lng'] ?? null);
                                }),
                        ]),

                        TextInput::make('address.longitude')
                            ->live()
                            ->label(__('resources/clinics.schema.address_form.components.longitude.label'))
                            ->minValue(-90)
                            ->maxValue(90),
                        TextInput::make('address.latitude')
                            ->live()
                            ->label(__('resources/clinics.schema.address_form.components.latitude.label'))
                            ->minValue(-90)
                            ->maxValue(90),
                    ]),

            ]);
    }
}
