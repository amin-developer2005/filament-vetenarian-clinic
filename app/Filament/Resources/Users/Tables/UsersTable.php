<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\EmailStatus;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Query\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('resources/users.table.columns.name.label'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('resources/users.table.columns.email.label'))
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label(__('resources/users.table.columns.roles.label'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('clinics.name')
                    ->label(__('resources/users.table.columns.clinics.label'))
                    ->searchable()
                    ->badge()
                    ->color(Color::Sky),
                TextColumn::make('created_at')
                    ->label(__('resources/users.table.columns.created_at.label'))
                    ->jalaliDateTime('d M ,Y H:i A')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('resources/users.table.columns.updated_at.label'))
                    ->dateTime('M d Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('roles')
                    ->schema([
                        Select::make('role')
                            ->label(__('resources/users.table.filters.roles.label'))
                            ->relationship(titleAttribute: 'name')
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn(Role $role) => $role->name->value)
                            ->native(false)
                            ->preload(),
                    ])->query(function (Builder $query, array $data) {
                        return $query->when(
                            $role = $data['role'] ?? null,
                                function (Builder $query) use ($role) {
                                    return $query->whereHas('roles', function (Builder $query) use ($role) {
                                        return $query->where('roles.id', $role);
                                    });
                            });
                    }),
                Filter::make('clinics')
                    ->schema([
                        Select::make('clinic')
                            ->label(__('resources/users.table.filters.clinics.label'))
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
