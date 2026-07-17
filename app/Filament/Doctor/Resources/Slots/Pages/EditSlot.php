<?php

namespace App\Filament\Doctor\Resources\Slots\Pages;

use App\Filament\Doctor\Resources\Slots\SlotResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSlot extends EditRecord
{
    protected static string $resource = SlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
