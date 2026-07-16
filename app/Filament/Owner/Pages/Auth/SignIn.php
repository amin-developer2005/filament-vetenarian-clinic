<?php

namespace App\Filament\Owner\Pages\Auth;

use App\Models\Clinic;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Events\Registered;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Auth\Pages\Login;
use Filament\Auth\Pages\Register;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;

class SignIn extends Login
{
    public function getTitle(): string|Htmlable
    {
        return __('ورود به پنل مالک | کلینیک دامپزشکی');
    }

    public function getHeading(): string|Htmlable|null
    {
        return __('ورود به پنل مالک');
    }

}
