<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum VetType: string implements HasLabel
{
    case Sheep = 'sheep';
    case Cow = 'cow';
    case Camel = 'camel';
    case Chicken = 'chicken';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Sheep => 'گوشفند',
            self::Cow => 'گاو',
            self::Camel => 'شتر',
            self::Chicken => 'مرغ',
        };
    }
}
