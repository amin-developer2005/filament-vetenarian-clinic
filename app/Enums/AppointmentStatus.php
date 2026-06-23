<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use phpDocumentor\Reflection\Types\Self_;

enum AppointmentStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case CheckedIn = 'checked_in';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Rejected = 'rejected';
    case NoShow = 'no_show';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('statuses.appointments.pending'),
            self::Confirmed    => __('statuses.appointments.confirmed'),
            self::CheckedIn    => __('statuses.appointments.checked_in'),
            self::InProgress  => __('statuses.appointments.in_progress'),
            self::Completed => __('statuses.appointments.completed'),
            self::Cancelled => __('statuses.appointments.cancelled'),
            self::Rejected => __('statuses.appointments.rejected'),
            self::NoShow      => __('statuses.appointments.no_show'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => Color::Lime,
            self::Confirmed => Color::Blue,
            self::CheckedIn => Color::Amber,
            self::InProgress => Color::Cyan,
            self::Completed => Color::Emerald,
            self::Cancelled => Color::Red,
            self::Rejected => Color::Rose,
            self::NoShow => Color::Orange,
        };
    }
}
