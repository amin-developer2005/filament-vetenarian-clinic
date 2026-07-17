<?php

namespace App\Services;

use App\Enums\PanelId;
use Filament\Facades\Filament;

class PanelService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function fetchPanelHeader(): string
    {
        $panel = Filament::getCurrentPanel();
        $tenant = Filament::getTenant();

        if (! $panel) {
            return config('app.name');
        }

        $panel = match ($panel?->getId()) {
            PanelId::ADMIN => 'پنل مدیریت',
            PanelId::DOCTOR => 'پنل پزشک',
            PanelId::STAFF => 'پنل کارکنان',
            PanelId::OWNER => 'پنل مالک',
            default => 'پنل',
        };

        if (blank($tenant?->name)) {
            return $panel;
        }

        return $panel . " | " . Filament::getTenant()->name;
    }
}
