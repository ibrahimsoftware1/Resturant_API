<?php

namespace Database\Seeders;

use App\Models\Tables;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Orders;
use App\Models\OrderItem;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {




//        // Create roles with fixed IDs
//        Role::insert([
//            ['id' => 1, 'name' => 'admin'],
//            ['id' => 2, 'name' => 'manager'],
//            ['id' => 3, 'name' => 'chef'],
//            ['id' => 4, 'name' => 'waiter'],
//        ]);

        User::factory(10)->create([
            'role_id' => fn() => fake()->numberBetween(1, 4),
        ]);

        // Create categories
        $categories = Category::factory(3)->create();

        // Create menu items
        $menuItems = collect();
        for ($i = 0; $i < 10; $i++) {
            $menuItems->push(MenuItem::factory()->create([
                'category_id' => $categories->random()->id,
            ]));
        }

        // Create tables
        $tables = Tables::factory(10)->create();

        $orders = collect();
        for ($i = 0; $i < 10; $i++) {
            $orders->push(Orders::factory()->create([
                'table_id' => $tables->random()->id,
                'user_id' => User::inRandomOrder()->first()->id,
            ]));
        }
        // Create order items for each order


        foreach ($orders as $order) {
            // Get a random, non-repeating set of menu items for this order
            $uniqueMenuItems = $menuItems->shuffle()->take(rand(2, 3));
            foreach ($uniqueMenuItems as $menuItem) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                ]);
            }
        }

    }
}
