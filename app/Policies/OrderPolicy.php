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
        if ($user->tokenCan(Abilities::ORDERS_CREATE)) {
            return true;
        }
        return false;
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Orders $order): bool
    {

        // Admin or full update ability
        if ($user->tokenCan(Abilities::ORDERS_UPDATE)) {
            return true;
        }

        // Waiter: update own order to served/cancelled
        if ($order->user_id === $user->id) {
            if($user->tokenCan(Abilities::ORDERS_UPDATE_CANCELLED) || $user->tokenCan(Abilities::ORDERS_UPDATE_SERVED)){
                $status = request()->input('status');
                return in_array($status, ['served', 'cancelled']);
            }
        }

        // Chef: update pending/preparing/completed
        if ($user->tokenCan(Abilities::ORDERS_UPDATE_PENDING)) {
            $status = request()->input('status');
            return in_array($status, ['pending', 'preparing', 'completed']);
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
