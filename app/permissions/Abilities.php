<?php

namespace App\permissions;

use App\Models\User;

final class Abilities
{
    // Tables
    public const TABLES_VIEW    = 'tables.view';
    public const TABLES_CREATE  = 'tables.create';
    public const TABLES_UPDATE  = 'tables.update';
    public const TABLES_DELETE  = 'tables.delete';

    // Categories
    public const CATEGORIES_VIEW    = 'categories.view';
    public const CATEGORIES_CREATE  = 'categories.create';
    public const CATEGORIES_UPDATE  = 'categories.update';
    public const CATEGORIES_DELETE  = 'categories.delete';

    // Menu Items
    public const MENU_ITEMS_VIEW    = 'menuItems.view';
    public const MENU_ITEMS_CREATE  = 'menuItems.create';
    public const MENU_ITEMS_UPDATE  = 'menuItems.update';
    public const MENU_ITEMS_DELETE  = 'menuItems.delete';

    // Orders general
    public const ORDERS_VIEW         = 'orders.view';
    public const ORDERS_VIEW_OWN = 'orders.own.view';
    public const ORDERS_CREATE       = 'orders.create';
    public const ORDERS_UPDATE       = 'orders.update';
    public const ORDERS_DELETE       = 'orders.delete';

    // Orders status updates
    public const ORDERS_UPDATE_PENDING    = 'orders.update.status.pending';
    public const ORDERS_UPDATE_PREPARING  = 'orders.update.status.preparing';
    public const ORDERS_UPDATE_COMPLETED  = 'orders.update.status.completed';
    public const ORDERS_UPDATE_SERVED     = 'orders.update.status.served';
    public const ORDERS_UPDATE_CANCELLED  = 'orders.update.status.cancelled';

    // Users
    public const USERS_VIEW    = 'users.view';
    public const USERS_CREATE  = 'users.create';
    public const USERS_UPDATE  = 'users.update';
    public const USERS_DELETE  = 'users.delete';

    /**
     * Get abilities for a given user based on role.
     */
    public static function getAbilities(User $user): array
    {
        $rolesAbilities = [
            // Admin: Full access to everything
            'admin' => [
                //tables
                self::TABLES_VIEW,
                self::TABLES_CREATE,
                self::TABLES_UPDATE,
                self::TABLES_DELETE,
                //categories
                self::CATEGORIES_VIEW,
                self::CATEGORIES_CREATE,
                self::CATEGORIES_UPDATE,
                self::CATEGORIES_DELETE,
                //menu items
                self::MENU_ITEMS_VIEW,
                self::MENU_ITEMS_CREATE,
                self::MENU_ITEMS_UPDATE,
                self::MENU_ITEMS_DELETE,
                //orders
                self::ORDERS_VIEW,
                self::ORDERS_CREATE,
                self::ORDERS_UPDATE,
                self::ORDERS_DELETE,
                self::ORDERS_UPDATE_PENDING,
                self::ORDERS_UPDATE_PREPARING,
                self::ORDERS_UPDATE_COMPLETED,
                self::ORDERS_UPDATE_SERVED,
                self::ORDERS_UPDATE_CANCELLED,
                //users
                self::USERS_VIEW,
                self::USERS_CREATE,
                self::USERS_UPDATE,
                self::USERS_DELETE,
            ],

            // Manager: Can manage tables, categories, menu items, and update order statuses
            'manager' => [
                //tables
                self::TABLES_VIEW,
                self::TABLES_CREATE,
                self::TABLES_UPDATE,
                //categories
                self::CATEGORIES_VIEW,
                self::CATEGORIES_CREATE,
                self::CATEGORIES_UPDATE,
                //menu items
                self::MENU_ITEMS_VIEW,
                self::MENU_ITEMS_CREATE,
                self::MENU_ITEMS_UPDATE,
                //orders
                self::ORDERS_VIEW,
                self::ORDERS_UPDATE_PENDING,
                self::ORDERS_UPDATE_PREPARING,
                self::ORDERS_UPDATE_COMPLETED,
                self::ORDERS_UPDATE_SERVED,
                self::ORDERS_UPDATE_CANCELLED,
            ],

            // Chef: Can view orders and update status
            'chef' => [
                self::ORDERS_VIEW,
                self::ORDERS_UPDATE_PENDING,
                self::ORDERS_UPDATE_PREPARING,
                self::ORDERS_UPDATE_COMPLETED,
            ],

            // Waiter: Can view orders, create orders, and mark them as served or cancelled
            'waiter' => [
                self::ORDERS_VIEW_OWN,
                self::ORDERS_CREATE,
                self::ORDERS_UPDATE_SERVED,
                self::ORDERS_UPDATE_CANCELLED,
                self::TABLES_VIEW,
                self::TABLES_UPDATE,
            ],
        ];

        return $rolesAbilities[$user->role->name] ?? [];
    }
}
