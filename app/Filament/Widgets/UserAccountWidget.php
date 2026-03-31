<?php

namespace App\Filament\Widgets;

use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class UserAccountWidget extends Widget
{
    protected static ?int $sort = -3;
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.user-account-widget';

    public static function canView(): bool
    {
        return Filament::auth()->check();
    }

    protected function getViewData(): array
    {
        $user = Filament::auth()->user();
        $role = $user->role->name;

        return [
            'user' => $user,
            'role' => $role,
        ];
    }
}


