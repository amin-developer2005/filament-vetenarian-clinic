<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum PanelRole: string implements HasLabel
{
    case Admin = 'ادمین';
    case Owner = 'مالک';
    case Doctor = 'دکتر';
    case Staff = 'کارمند';

    public const string ADMIN = 'ادمین';
    public const string OWNER = 'مالک';
    public const string DOCTOR = 'دکتر';
    public const string Receptionist = 'پذیرش';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Admin => 'ادمین',
            self::Owner => 'مالک',
            self::Doctor => 'دکتر',
            self::Receptionist => 'پذیرش',
            default => null,
        };
    }
}
