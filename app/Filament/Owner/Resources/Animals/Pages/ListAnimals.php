<?php

namespace App\Filament\Owner\Resources\Animals\Pages;

use App\Filament\Owner\Resources\Animals\AnimalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;

class ListAnimals extends ListRecords
{
    protected static string $resource = AnimalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-s-plus')
                ->iconSize(IconSize::TwoExtraLarge)
                ->label(__('owner/animals.pages.index.actions.create')),
        ];
    }
}
