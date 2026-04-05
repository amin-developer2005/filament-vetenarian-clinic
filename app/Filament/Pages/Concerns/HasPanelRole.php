<?php

namespace App\Filament\Pages\Concerns;

use App\Enums\PanelId;
use App\Enums\PanelRole;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPanelRole
{
    protected Model $model {
        set => $this->model = $value;
        get => $this->model;
    }

    protected ?string $roleClass {
        set => $this->roleClass = $value;
        get {
            if ($this->roleClass) {
                return $this->roleClass;
            }

            return $this->roleClass = Role::class;
        }
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('roles.relations.related'),
            config('roles.relations.table'),
            config('roles.relations.foreign_pivot_key'),
            config('roles.relations.related_pivot_key'),
        );
    }

    public function role(): BelongsToMany
    {
        return $this->roles();
    }

    public function assignPanelRole(?Panel $panel = null): static
    {
        $panel ??= $this->fetchPanel();
        $role = $this->resolvePanelRole($panel);

        if ($this->hasPanelRole($role)) {
            return $this;
        }

        $this->roles()->attach($role);
        $this->model->unsetRelation('roles');

        return $this;
    }

    /**
     * @param  User  $user
     */
    public function hasPanelRole(Role|string $role): bool
    {
        if (is_string($role) && (! $role instanceof Role)) {
            $role = $this->fetchRole($role);
        }

        $roles = $this->roles()->get();

        if ($roles->contains($role)) {
            return true;
        }

        return false;
    }

    public function resolvePanelRole(Panel $panel)
    {
        $panelId = $panel->getId();

        return $this->fetchRole($panelId);
    }

    public function fetchRole(string $name)
    {
        $role = Role::query()->firstWhere('name', $name);

        if (! $role) {
            return null;
        }

        return $role;
    }

    public function removePanelRole(?Panel $panel = null): void
    {
        $panel ??= $this->fetchPanel();
        $role = $this->resolvePanelRole($panel);

        if (! $this->hasPanelRole($role)) {
            return;
        }

        $this->roles()->detach($role);
        $this->model->unsetRelation('roles');
    }

    public function resolvePanelId(): ?string
    {
        return match (true) {
            $this->isAdmin() => PanelId::ADMIN,
            $this->isOwner() => PanelId::OWNER,
            $this->isDoctor() => PanelId::DOCTOR,
            $this->isStaff() => PanelId::STAFF,
            default => PanelId::ADMIN
        };
    }

    public function isAdmin(): bool
    {
        return $this->hasPanelRole(PanelRole::ADMIN);
    }

    public function isOwner(): bool
    {
        return $this->hasPanelRole(PanelRole::OWNER);
    }

    public function isDoctor(): bool
    {
        return $this->hasPanelRole(PanelRole::DOCTOR);
    }

    public function isStaff(): bool
    {
        return $this->hasPanelRole(PanelRole::STAFF);
    }

    protected function fetchPanel(): ?Panel
    {
        return Filament::getCurrentOrDefaultPanel();
    }
}
