<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EmailStatus: string implements HasColor, HasLabel
{
    case Verified = 'verified';
    case Unverified = 'unverified';

    public const string VERIFIED = 'verified';
    public const string UNVERIFIED = 'unverified';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::Verified   => 'تایید شده',
            self::Unverified => 'تایید نشده',
            default => null,
        };
    }


    public function getColor(): ?string
    {
        return match ($this) {
            self::Verified   => Color::Emerald,
            self::Unverified => Color::Red,
            default          => null,
        };
    }
}
