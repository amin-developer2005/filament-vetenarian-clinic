<?php

namespace App\Filament\Staff\Resources\Slots\Pages;

use App\Filament\Staff\Resources\Slots\SlotResource;
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
