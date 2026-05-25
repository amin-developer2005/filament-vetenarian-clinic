<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SlotStatus: string implements HasColor, HasLabel
{
    case Created = 'created';
    case Available = 'available';
    case Booked = 'booked';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Created   => __('statuses.slots.created'),
            self::Available => __('statuses.slots.available'),
            self::Booked    => __('statuses.slots.booked'),
            self::Confirmed => __('statuses.slots.confirmed'),
            self::Cancelled => __('statuses.slots.cancelled'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Created   => Color::Gray,
            self::Available => Color::Green,
            self::Booked    => Color::Blue,
            self::Confirmed => Color::Indigo,
            self::Cancelled => Color::Red,
        };
    }
}
