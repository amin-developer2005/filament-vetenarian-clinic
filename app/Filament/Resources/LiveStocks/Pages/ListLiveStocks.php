<?php

namespace App\Filament\Resources\LiveStocks\Pages;

use App\Filament\Resources\LiveStocks\LiveStockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLiveStocks extends ListRecords
{
    protected static string $resource = LiveStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('resources/liveStocks.pages.index.actions.create')),
        ];
    }
}
