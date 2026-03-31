<?php

namespace App\Filament\Pages\Concerns;

use App\Filament\Pages\EditProfile;
use App\Filament\Pages\Profile;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Auth\Notifications\NoticeOfEmailChangeRequest;
use Filament\Auth\Notifications\VerifyEmailChange;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Panel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use League\CommonMark\Exception\LogicException;
use Filament\Notifications\Notification as FilamentNotification;
use League\Uri\Components\Query;


trait HasProfileRoutes
{
    public function fetchProfileUrl(?string $name = null, ?array $parameters = [], bool $isAbsolute = true, ?Panel $panel = null, ?Model $tenant = null): string
    {
       $panel ??= Filament::getCurrentOrDefaultPanel();

       if ($panel->hasTenancy()) {
           $parameters['tenant'] ??= $tenant ?? Filament::getTenant();
       }

        if (blank($name)) {
            return $this->fetchDefaultProfileUrl($panel);
        }

        $url = match ($name) {
            'edit' => $this->fetchProfileEditPageUrl($panel),
            default => $this->fetchDefaultProfileUrl($panel),
        };

        return $url;
    }

    public static function getRelativeRouteName(Panel $panel): string
    {
        return "profile";
    }

    protected function fetchDefaultProfileUrl(?Panel $panel = null): string
    {
        $panel ??= Filament::getCurrentOrDefaultPanel();

        return $panel->getUrl() . Profile::getRoutePath($panel);
    }

    protected function fetchProfileEditPageUrl(?Panel $panel = null): string
    {
        $panel ??= Filament::getCurrentOrDefaultPanel();

        return $panel->getUrl() . EditProfile::getRoutePath($panel);
    }

    public function getDefaultActionSuccessRedirectUrl(Action $action): string
    {
        return match (true) {
            $action instanceof EditAction => $this->fetchProfileUrl($action->getName()),
            default => $this->fetchProfileUrl(),
        };
    }

}
