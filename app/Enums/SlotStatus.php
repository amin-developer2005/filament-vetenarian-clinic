<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SlotStatus: string implements HasColor, HasLabel
{
    case Available = 'available';
    case Booked = 'booked';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Available => __('statuses.slots.available'),
            self::Booked    => __('statuses.slots.booked'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Available => Color::Green,
            self::Booked    => Color::Blue,
        };
    }
}
