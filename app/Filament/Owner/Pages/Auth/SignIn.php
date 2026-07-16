<?php

namespace App\Filament\Owner\Pages\Auth;

use App\Models\Clinic;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Events\Registered;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Auth\Pages\Register;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;

class Signup extends Register
{
    protected int $maxAttempts = 4;

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit($this->maxAttempts);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $this->beginDatabaseTransaction();

        $data = $this->mutateFormDataBeforeRegister(
            $this->form->getState()
        );

        $user = $this->handleRegistration($data);

        $this->form->model($user)->saveRelationships();

        $this->commitDatabaseTransaction();

        event(new Registered($user));
        $this->sendEmailVerificationNotification($user);

        $authGuard = Filament::auth();
        $user->profile()->create();

        $user->assignPanelRole(
            Filament::getCurrentOrDefaultPanel()
        );

        $authGuard->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }
}
