<?php

namespace App\Policies;

use App\Models\LiveStock;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LiveStockPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LiveStock $liveStock): bool
    {
        return $user->isAdmin() || $liveStock->isBelongsToOwner($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LiveStock $liveStock): bool
    {
        return $user->isAdmin() || $liveStock->isBelongsToOwner($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LiveStock $liveStock): bool
    {
        return $user->isAdmin() || $liveStock->isBelongsToOwner($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LiveStock $liveStock): bool
    {
        return $user->isAdmin() || $liveStock->isBelongsToOwner($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LiveStock $liveStock): bool
    {
        return $user->isAdmin() || $liveStock->isBelongsToOwner($user);
    }
}
