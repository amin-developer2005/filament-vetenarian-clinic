<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProfileGender: string implements HasLabel
{
    case Male = 'male';
    case Female = 'female';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Male => __('genders.profile.male'),
            self::Female => __('genders.profile.female'),
            default => null,
        };
    }
}
