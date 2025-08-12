<?php

namespace App\Providers;


use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Orders;
use App\Models\Tables;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\MenuItemPolicy;
use App\Policies\OrderPolicy;
use App\Policies\TablePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Tables::class => TablePolicy::class,
        Category::class => CategoryPolicy::class,
        MenuItem::class => MenuItemPolicy::class,
        Orders::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Registers all policies defined in the $policies property
        $this->registerPolicies();


    }

    public function register(): void
    {

    }
}
