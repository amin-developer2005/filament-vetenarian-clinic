<?php

namespace App\Filament\Resources\Clinics\Tables;

use App\Models\Clinic;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClinicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('resources/clinics.table.columns.name.label'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('code')
                    ->label(__('resources/clinics.table.columns.code.label'))
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),

                TextColumn::make('phone')
                    ->label(__('resources/clinics.table.columns.phone.label'))
                    ->searchable()
                    ->getStateUsing(
                        fn (Clinic $clinic) => $clinic?->phone ?: 'ثبت نشده'
                    )
                    ->badge(fn (Clinic $clinic) => blank($clinic?->phone))
                    ->toggleable(),

                TextColumn::make('email')
                    ->label(__('resources/clinics.table.columns.email.label'))
                    ->searchable()
                    ->getStateUsing(
                        fn (Clinic $clinic) => $clinic?->email ?: 'ثبت نشده'
                    )
                    ->badge(fn (Clinic $clinic) => blank($clinic?->email))
                    ->toggleable(),

                TextColumn::make('address.city')
                    ->label(__('resources/clinics.table.columns.city.label'))
                    ->searchable(
                        query: function (Builder $query, string $search): Builder {
                            return $query->whereHas(
                                'address',
                                fn (Builder $q): Builder => $q->where('city', 'like', "%{$search}%"),
                            );
                        },
                    )
                    ->getStateUsing(
                        fn (Clinic $clinic) => $clinic->address?->city ?: 'ثبت نشده'
                    )
                    ->badge(fn (Clinic $clinic) => blank($clinic->address?->city))
                    ->sortable(
                        query: function (Builder $query, string $direction): Builder {
                            return $query->orderBy(
                                \App\Models\ClinicAddress::select('city')
                                    ->whereColumn('clinic_id', 'clinics.id')
                                    ->limit(1),
                                $direction,
                            );
                        },
                    )
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label(__('resources/clinics.table.columns.is_active.label'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('updated_at')
                    ->label(__('resources/clinics.table.columns.updated_at.label'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('resources/clinics.table.columns.created_at.label'))
                    ->jalaliDateTime('d M ,Y H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('resources/clinics.table.filters.is_active.label'))
                    ->boolean()
                    ->trueLabel(__('resources/clinics.table.filters.is_active.options.active'))
                    ->falseLabel(__('resources/clinics.table.filters.is_active.options.inactive'))
                    ->native(false),
            ], FiltersLayout::Modal)
            ->recordActions([
                Action::make('goToClinic')
                    ->label(__('resources/clinics.table.actions.goToClinic'))
                    ->button()
                    ->visible(fn(Clinic $clinic) => $clinic->isActive())
                    ->url(fn (Clinic $clinic) => Filament::getUrl($clinic)),
                Action::make('activate')
                    ->label(__('resources/clinics.table.actions.activate'))
                    ->button()
                    ->color(Color::Emerald)
                    ->visible(fn (Clinic $clinic) => $clinic->isNotActive())
                    ->action(fn (Clinic $clinic) => $clinic->activate()),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('resources/clinics.table.empty_state.heading'))
            ->emptyStateDescription(__('resources/clinics.table.empty_state.description'))
            ->emptyStateIcon('heroicon-o-building-office-2');
    }
}
