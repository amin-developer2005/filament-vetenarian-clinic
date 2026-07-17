<?php

namespace App\Filament\Resources\Doctors\Tables;

use App\Enums\EmailStatus;
use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Query\Builder;

class DoctorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('resources/doctors.table.columns.name.label'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('resources/doctors.table.columns.email.label'))
                    ->searchable(),
                TextColumn::make('clinics.name')
                    ->label(__('resources/doctors.table.columns.clinics.label'))
                    ->searchable()
                    ->badge()
                    ->color(Color::Sky),
                TextColumn::make('created_at')
                    ->label(__('resources/doctors.table.columns.created_at.label'))
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resources/doctors.table.columns.updated_at.label'))
                    ->dateTime('M d Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('clinics')
                    ->schema([
                        Select::make('clinic')
                            ->label(__('resources/doctors.table.filters.clinics.label'))
                            ->relationship(titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ])->query(function (Builder $query, array $data) {
                        return $query->when(
                            $clinic = $data['clinic'] ?? null,
                            function (Builder $query) use ($clinic) {
                                return $query->whereHas('clinics', function (Builder $query) use ($clinic) {
                                    return $query->where('clinics.id', $clinic);
                                });
                            }
                        );
                    }),
            ], FiltersLayout::Modal)
            ->recordActions([

                    EditAction::make(),
                    DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
