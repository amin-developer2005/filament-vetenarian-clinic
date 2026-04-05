<?php

namespace App\Filament\Widgets;

use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Support\Arr;

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
        $user = User::query()->find(Filament::auth()->id());
        $roles = $user->roles;

        return [
            'user' => $user,
            'roles' => $roles->map(fn(Role $rol) => $rol->name),
        ];
    }
}


