<?php

namespace App\Filament\Pages\Concerns;

use App\Filament\Owner\Pages\Profile;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;


trait HasProfileRoutes
{
    public function fetchProfileUrl(?string $name = null, ?array $parameters = [], bool $isAbsolute = true, ?Panel $panel = null, ?Model $tenant = null): string
    {
       $panel ??= Filament::getCurrentOrDefaultPanel();

       if ($panel->hasTenancy()) {
           $parameters['tenant'] ??= $tenant ?? Filament::getTenant();
       }

        if (filled($name) && $name == 'edit') {
            return $this->fetchProfileEditPageUrl($panel);
        }

        return $this->fetchDefaultProfileUrl($panel);
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

        return $panel->getUrl() . Profile\Schemas\EditProfileSchema::fetchRoutePath();
    }

    public function getDefaultActionSuccessRedirectUrl(Action $action): string
    {
        return match (true) {
            $action instanceof EditAction => $this->fetchProfileUrl($action->getName()),
            default => $this->fetchProfileUrl(),
        };
    }

}
