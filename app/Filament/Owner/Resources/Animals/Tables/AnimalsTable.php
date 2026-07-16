<?php

namespace App\Filament\Owner\Resources\Animals\Tables;

use App\Enums\AnimalSpecies;
use App\Enums\AnimalGender;
use App\Models\Animal;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AnimalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label(__('owner/animals.table.columns.avatar'))
                    ->circular()
                    ->defaultImageUrl(asset('images/default-animal.png'))
                    ->grow(false),

                TextColumn::make('name')
                    ->label(__('owner/animals.table.columns.name'))
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('species')
                    ->label(__('owner/animals.table.columns.species'))
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('breed')
                    ->label(__('owner/animals.table.columns.breed'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('gender')
                    ->label(__('owner/animals.table.columns.gender'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('date_of_birth')
                    ->label(__('owner/animals.table.columns.date_of_birth'))
                    ->date()
                    ->sortable(),

                TextColumn::make('microchip_number')
                    ->label(__('owner/animals.table.columns.microchip_number'))
                    ->searchable()
                    ->badge(
                        fn(Animal $animal) => blank($animal->microchip_number)
                    )
                    ->color(
                        fn(Animal $animal) => blank($animal->microchip_number) ? Color::Red : Color::Emerald
                    )
                    ->getStateUsing(
                        fn(Animal $animal) => $animal->microchip_number ?: 'ثبت نشده'
                    ),

                IconColumn::make('is_neutered')
                    ->label(__('owner/animals.table.columns.is_neutered'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label(__('owner/animals.table.columns.created_at'))
                    ->since()
                    ->jalaliDateTime('d M ,Y H:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('species')
                    ->label(__('owner/animals.table.filters.species'))
                    ->options(AnimalSpecies::class)
                    ->searchable()
                    ->preload()
                    ->native(false),
                SelectFilter::make('gender')
                    ->label(__('owner/animals.table.filters.gender'))
                    ->options(AnimalGender::class)
                    ->searchable()
                    ->preload()
                    ->native(false),
                Filter::make('is_neutered')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_neutered')
                                    ->label(__('owner/animals.table.filters.is_neutered')),
                                Toggle::make('is_not_neutered')
                                    ->label(__('owner/animals.table.filters.is_not_neutered')),
                            ])
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['is_neutered']) {
                            return $query->where('is_neutered', true);
                        }

                        if ($data['is_not_neutered']) {
                            return $query->where('is_neutered', false);
                        }
                    }),
                Filter::make('microchip_number')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('has_microchip_number')
                                    ->label(__('owner/animals.table.filters.has_microchip_number')),
                                Toggle::make('has_not_microchip_number')
                                    ->label(__('owner/animals.table.filters.has_not_microchip_number')),
                                ])
                    ])->query(function (Builder $query, array $data) {
                        if ($data['has_microchip_number']) {
                            return $query
                                ->where('microchip_number', '!=', null)
                                ->latest()
                                ->get();
                        }

                        if ($data['has_not_microchip_number']) {
                            return $query
                                ->where('microchip_number',null)
                                ->latest()
                                ->get();
                        }

                    })
            ], FiltersLayout::Modal)
            ->filtersFormWidth(Width::FourExtraLarge)
            ->emptyStateHeading(
                __('owner/animals.table.empty_state.heading')
            )
            ->emptyStateDescription(
                __('owner/animals.table.empty_state.description')
            )
            ->emptyStateIcon('heroicon-o-heart')
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])->color(Color::Emerald)->size(Size::ExtraLarge),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
