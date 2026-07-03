<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AnimalGender: string implements HasLabel
{
    case Male = 'male';
    case Female = 'female';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Male => __('genders.animals.male'),
            self::Female => __('genders.animals.female'),
            default => null,
        };
    }
}
