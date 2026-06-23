<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AnimalSpecies: string implements HasLabel
{
    case Dog = 'dog';

    case Cat = 'cat';

    case Bird = 'bird';

    case Rabbit = 'rabbit';
    case Horse = 'horse';
    case Cow = 'cow';
    case Sheep = 'sheep';
    case Goat = 'goat';
    case Chicken = 'chicken';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Dog => __('resources/animals.species.dog'),
            self::Cat => __('resources/animals.species.cat'),
            self::Bird => __('resources/animals.species.bird'),
            self::Rabbit => __('resources/animals.species.rabbit'),
            self::Horse => __('resources/animals.species.horse'),
            self::Cow => __('resources/animals.species.cow'),
            self::Sheep => __('resources/animals.species.sheep'),
            self::Goat => __('resources/animals.species.goat'),
            self::Chicken => __('resources/animals.species.chicken'),
        };
    }
}
