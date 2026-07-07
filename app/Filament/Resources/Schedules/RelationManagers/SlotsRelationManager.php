<?php

namespace App\Filament\Resources\Schedules\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SlotsRelationManager extends RelationManager
{
    protected static string $relationship = 'slots';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label(__('resources/slots.table.columns.date'))
                    ->date(),
                TextColumn::make('start_time')
                    ->label(__('resources/slots.table.columns.start_time'))
                    ->time('H:i A'),
                TextColumn::make('end_time')
                    ->label(__('resources/slots.table.columns.end_time'))
                    ->time('H:i A'),
                TextColumn::make('schedule.doctor.name')
                    ->label(__('resources/slots.table.columns.doctor'))
                    ->searchable(),
                TextColumn::make('schedule.clinic.name')
                    ->label(__('resources/slots.table.columns.clinic'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('resources/slots.table.columns.status'))
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->recordActions([
            ])
            ->toolbarActions([

            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('resources/slots.label');
    }
}
