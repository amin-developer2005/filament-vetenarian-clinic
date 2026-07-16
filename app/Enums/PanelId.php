<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum PanelId: string implements HasLabel
{
    case Admin = 'admin';
    case Owner = 'owner';
    case Doctor = 'doctor';
    case Staff = 'staff';

    public const string ADMIN = 'admin';
    public const string OWNER = 'owner';
    public const string DOCTOR = 'doctor';
    public const string STAFF = 'staff';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Admin => 'ادمین',
            self::Owner => 'مالک',
            self::Doctor => 'دکتر',
            self::Staff => 'کارکنان',
            default => null,
        };
    }
}
