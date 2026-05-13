<?php

namespace App\Filament\Pages\Concerns;

use App\Models\Clinic;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use function Filament\authorize;

trait InteractWithTenantClinic
{
    public Clinic $clinic {
        set => $this->clinic = $value;
        get => $this->clinic;
    }

    public string $model {
        set => $this->model = $value;
        get => $this->model ?? Clinic::class;
    }

    public function resolveClinic(): Model
    {
        if (filled($this->clinic) && $this->clinic instanceof Model) {
            return $this->clinic;
        }

        $tenantModel = $this->getModel();
        $tenant = app()->make($tenantModel);

        if (! $tenant) {
            throw new ModelNotFoundException()->setModel($tenantModel);
        }

        return $tenant;
    }

    public function getTenant(): ?Model
    {
        return Filament::getTenant();
    }

    public function getTenantModel(): ?string
    {
        if (blank($this->model)) {
            return null;
        }

        if (is_string($this->model)) {
            return $this->model;
        }

        if ($this->model instanceof Model) {
            return $this->model::class;
        }

        return Filament::getTenantModel();
    }

    public function canAccess(Model | string $tenant, string $action): bool
    {
        try {
            return authorize($action, $tenant)->allowed();
        } catch (AuthorizationException $exception) {
            return $exception->toResponse()->allowed();
        }
    }
}
