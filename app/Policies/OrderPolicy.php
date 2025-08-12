<?php

namespace App\Policies;

use App\Models\Orders;
use App\Models\User;
use App\permissions\Abilities;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user,Orders $order=null): bool
    {
        if( $user->tokenCan(Abilities::ORDERS_VIEW)){
            return true;
        }
        else if($user->tokenCan(Abilities::ORDERS_VIEW_OWN)){
                return true;
             }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Orders $order): bool
    {
        if($user->tokenCan(Abilities::ORDERS_VIEW)){
            return true;
        }
        else if($user->tokenCan(Abilities::ORDERS_VIEW_OWN)){
            return $user->id === $order->user_id;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Orders $order): bool
    {
        if ($user->tokenCan(Abilities::ORDERS_UPDATE)) {
            return true;
        }

        else if ($user->tokenCan(Abilities::ORDERS_UPDATE_SERVED)) {

            if ($order->user_id === $user->id) {

                $newStatus = request()->input('status');
                $onlyStatusChanged = array_keys(request()->all()) === ['status'];
                return $onlyStatusChanged && ($newStatus === 'served' || $newStatus === 'cancelled');
            }
        }
        else if($user->tokenCan(Abilities::ORDERS_UPDATE_PENDING)){
            $newStatus = request()->input('status');
            $onlyStatusChanged = array_keys(request()->all()) === ['status'];
            return $onlyStatusChanged && ($newStatus === 'preparing'|| $newStatus === 'completed'|| $newStatus === 'pending');
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Orders $order): bool
    {
        if($user->tokenCan(Abilities::ORDERS_DELETE)){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Orders $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Orders $order): bool
    {
        return false;
    }
}
