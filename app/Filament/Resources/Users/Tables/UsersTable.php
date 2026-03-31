<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\EmailStatus;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Support\Colors\Color;
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label('Email Status')
                    ->badge()
                    ->getStateUsing(fn (User $user) => $user->email_verified_at ? EmailStatus::Verified : EmailStatus::Unverified)
                    ->color(fn (User $user) => $user->email_verified_at ? Color::Green : Color::Red)
                    ->sortable(),
                TextColumn::make('profile.mobile')
                    ->label('Mobile')
                    ->searchable(),
                TextColumn::make('role.name')
                    ->searchable()
                    ->badge(),
                TextColumn::make('clinics.name')
                    ->searchable()
                    ->badge()
                    ->color(Color::Sky),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('M d Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role_id')
                    ->label('Role')
                    ->relationship('role', 'name')
                    ->searchable()
                    ->native(false)
                    ->preload(),
                Filter::make('email_verified_at')
                    ->schema([
                        Select::make('email_status')
                            ->label('Email Status')
                            ->options(EmailStatus::class)
                            ->native(false),
                    ])->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['email_status'] ?? null,
                            fn ($q, $v) => $v === EmailStatus::VERIFIED ? $q->whereNotNull('email_verified_at') : $q->whereNull('email_verified_at'),
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
