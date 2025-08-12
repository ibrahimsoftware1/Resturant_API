<?php

namespace App\Policies;

use App\Models\Tables;
use App\Models\User;
use App\permissions\Abilities;
use App\Trait\ApiResponse;

class TablePolicy
{
    use ApiResponse;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->tokenCan(Abilities::TABLES_VIEW)){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tables $table): bool
    {

        if($user->tokenCan(Abilities::TABLES_VIEW)){
            return true;
        }
        return false;

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {

        if($user->tokenCan(Abilities::TABLES_CREATE)){
            return true;
        }
        return false;

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tables $table): bool
    {
        if($user->tokenCan(Abilities::TABLES_UPDATE)){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tables $table): bool
    {
        if($user->tokenCan(Abilities::TABLES_DELETE)) {
                return true;
            }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tables $table): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tables $table): bool
    {
        return false;
    }
}
