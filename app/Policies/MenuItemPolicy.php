<?php

namespace App\Policies;

use App\Models\MenuItem;
use App\Models\User;
use App\permissions\Abilities;
use Illuminate\Auth\Access\Response;

class MenuItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->tokenCan(Abilities::MENU_ITEMS_VIEW)){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MenuItem $menuItem): bool
    {
        if($user->tokenCan(Abilities::MENU_ITEMS_VIEW)){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->tokenCan(Abilities::MENU_ITEMS_CREATE)){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MenuItem $menuItem): bool
    {
        if($user->tokenCan(Abilities::MENU_ITEMS_UPDATE)){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MenuItem $menuItem): bool
    {
        if($user->tokenCan(Abilities::MENU_ITEMS_DELETE)){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MenuItem $menuItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MenuItem $menuItem): bool
    {
        return false;
    }
}
