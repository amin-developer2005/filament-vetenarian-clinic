<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SlotStatus: string implements HasColor, HasLabel
{
    case Created = 'created';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Created => 'Created',
            self::Confirmed => 'Confirmed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Created => Color::Yellow,
            self::Confirmed => Color::Emerald,
            self::Cancelled => Color::Red,
        };
    }
}
