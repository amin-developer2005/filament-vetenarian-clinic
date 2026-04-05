<?php

namespace App\Filament\Resources\Roles\Tables;

use App\Models\Role;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('resources/roles.table.columns.name.label'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('resources/roles.table.columns.description.label'))
                    ->sortable()
                    ->getStateUsing(fn (Role $role) => filled($description = $role->description) ? $description : 'Null')
                    ->badge(fn (Role $role) => blank($role->description)),
                TextColumn::make('created_at')
                    ->label(__('resources/roles.table.columns.created_at.label'))
                    ->dateTime()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('name')
                    ->label(__('resources/roles.table.filters.name.label'))
                    ->options(
                        fn () => Role::query()->pluck('name', 'id')
                    )
                    ->query(
                        fn ($query, $data) => $query->when(
                                    $data['value'],
                                    fn ($q, $v) => $q->where('id', $v)
                        )
                    )
                    ->searchable()
                    ->preload()
                    ->native(false),
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
