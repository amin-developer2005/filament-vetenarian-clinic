<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum GenderType: string implements HasLabel
{
    case Male = 'male';
    case Female = 'female';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Male => __('owner/animals.genders.male'),
            self::Female => __('owner/animals.genders.female'),
            default => null,
        };
    }
}
