<?php

namespace App\Filament\Staff\Pages;

use Filament\Auth\Pages\Login;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class SignIn extends Login
{
    public function getTitle(): string|Htmlable
    {
        return __('ورود به پنل کارکنان | کلینیک دامپزشکی');
    }

    public function getHeading(): string|Htmlable|null
    {
        return __('ورود به پنل کارکنان');
    }

}
