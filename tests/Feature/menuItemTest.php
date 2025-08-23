<?php
namespace Tests\Feature;

use App\Models\Category;
use App\permissions\Abilities;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\MenuItem;
use App\Models\User;

class menuItemTest extends TestCase{

    use DatabaseTransactions;

    /**
     * Views the list of menu items.
     */
    public function test_Authorized_users_can_view_all_menuItems()
    {
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $response = $this->getJson('/api/menu-items');
        $response->assertStatus(200);

    }
    public function test_unAuthorized_users_cannot_view_all_menuItems()
    {
        $user=User::whereIn('role_id',[3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $response = $this->getJson('/api/menu-items');
        $response->assertStatus(403);
    }

    /**
     * Views a single menu item.
     */
    public function test_Authorized_users_can_view_single_menu_item(){
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem = MenuItem::first();
        $response = $this->getJson('/api/menu-items/'.$menuItem->id);
        $response->assertStatus(200);
    }
    public function test_unAuthorized_users_cannot_view_single_menu_items(){
        $user=User::whereIn('role_id',[3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem = MenuItem::first();
        $response = $this->getJson('/api/menu-items/'.$menuItem->id);
        $response->assertStatus(403);
    }

    public function test_returns_not_found_when_viewing_non_existent_menu_item(){
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $nonExistentMenuItemId = MenuItem::max('id') + 1;
        $response = $this->getJson('/api/menu-items/'.$nonExistentMenuItemId);
        $response->assertStatus(404);
    }
    /**
     * Create a new menu item.
     */
    public function test_Authorized_users_can_create_menu_item(){
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category=Category::first();
        $data = [
            'name' => 'Test Menu Item'.uniqid(),
            'description' => 'Test Description',
            'price' => 9.99,
            'category_id' => $category->id, // Assuming this category exists
        ];
        $response = $this->postJson('/api/menu-items', $data);
        $response->assertStatus(201);
    }
    public function test_unAuthorized_users_cannot_create_menu_item(){
        $user=User::whereIn('role_id',[3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category=Category::first();
        $data = [
            'name' => 'Test Menu Item',
            'description' => 'Test Description',
            'price' => 9.99,
            'category_id' => $category->id, // Assuming this category exists
        ];
        $response = $this->postJson('/api/menu-items', $data);
        $response->assertStatus(403);
    }

    public function test_return_fails_when_creating_Item_with_invalid_name()
    {
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $data = [
            'name' => 22,
            'description' => 'Test Description',
            'price' => 9.99,
            'category_id' => 1,
        ];
        $response = $this->postJson('/api/menu-items', $data);
        $response->assertStatus(422);

    }
    public function test_return_fails_when_creating_Item_with_invalid_price()
    {
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $data = [
            'name' => 'Test Menu Item',
            'description' => 'Test Description',
            'price' => 'invalid_price',
            'category_id' => 1,
        ];
        $response = $this->postJson('/api/menu-items', $data);
        $response->assertStatus(422);
    }
    public function test_return_fails_when_creating_Item_with_invalid_category_id()
    {
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $data = [
            'name' => 'Test Menu Item',
            'description' => 'Test Description',
            'price' => 9.99,
            'category_id' => 9999, // Assuming this category does not exist
        ];
        $response = $this->postJson('/api/menu-items', $data);
        $response->assertStatus(422);
    }
    public function test_return_fails_when_creating_Item_with_invalid_description()
    {
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $data = [
            'name' => 'Test Menu Item',
            'description' => 33,
            'price' => 9.99,
            'category_id' => 1, // Assuming this category does not exist
        ];
        $response = $this->postJson('/api/menu-items', $data);
        $response->assertStatus(422);
    }
    public function test_return_fails_when_creating_Item_with_empty_requests()
    {
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $data = [
            'name' => null,
            'description' => null,
            'price' =>null,
            'category_id' => null, // Assuming this category does not exist
        ];
        $response = $this->postJson('/api/menu-items', $data);
        $response->assertStatus(422);
    }

    /**
     * Update a menu item.
     */
    public function test_Authorized_users_can_update_menu_item(){
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem = MenuItem::first();
        $category = Category::first();
        $data = [
            'name' => 'Updated Menu Item',
            'description' => 'Updated Description',
            'price' => 12.99,
            'category_id' => $category->id, // Assuming this category exists
        ];
        $response = $this->putJson('/api/menu-items/'.$menuItem->id, $data);
        $response->assertStatus(200);
    }
    public function test_unAuthorized_users_cannot_update_menu_item(){
        $user=User::whereIn('role_id',[3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem = MenuItem::first();
        $category= Category::first();
        $data = [
            'name' => 'Updated Menu Item',
            'description' => 'Updated Description',
            'price' => 12.99,
            'category_id' => $category->id, // Assuming this category exists
        ];
        $response = $this->putJson('/api/menu-items/'.$menuItem->id, $data);
        $response->assertStatus(403);
    }
    public function test_return_fails_when_updating_menu_item_with_invalid_name()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem = MenuItem::first();
        $data = [
            'name' => 22,
            'description' => 'Updated Description',
            'price' => 12.99,
            'category_id' => 1,
        ];
        $response = $this->putJson('/api/menu-items/' . $menuItem->id, $data);
        $response->assertStatus(422);
    }
    public function test_return_fails_when_updating_menu_item_with_invalid_price()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem =MenuItem::first();
        $data = [
            'name' => 'Updated Menu Item',
            'description' => 'Updated Description',
            'price' => 'invalid_price',
            'category_id' => 1,
        ];
        $response = $this->putJson('/api/menu-items/' . $menuItem->id, $data);
        $response->assertStatus(422);
    }
    public function test_return_fails_when_updating_menu_item_with_invalid_category_id(){
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem = MenuItem::first();
        $data = [
            'name' => 'Updated Menu Item',
            'description' => 'Updated Description',
            'price' => 12.99,
            'category_id' => 9999, // Assuming this category does not exist
        ];
        $response = $this->putJson('/api/menu-items/' . $menuItem->id, $data);
        $response->assertStatus(422);
    }
    public function test_return_fails_when_updating_menu_item_with_invalid_description()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem = MenuItem::first();
        $data = [
            'name' => 'Updated Menu Item',
            'description' => 33,
            'price' => 12.99,
            'category_id' => 1,
        ];
        $response = $this->putJson('/api/menu-items/' . $menuItem->id, $data);
        $response->assertStatus(422);
    }
    public function test_return_fails_when_updating_menu_item_with_empty_requests()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem = MenuItem::first();
        $data = [
            'name' => null,
            'description' => null,
            'price' => null,
            'category_id' => null,
        ];
        $response = $this->putJson('/api/menu-items/' . $menuItem->id, $data);
        $response->assertStatus(422);
    }
    public function test_returns_not_found_when_updating_non_existent_menu_item()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $nonExistentMenuItemId = MenuItem::max('id') + 1;
        $category = Category::first();
        $data = [
            'name' => 'Updated Menu Item',
            'description' => 'Updated Description',
            'price' => 12.99,
            'category_id' => $category->id, // Assuming this category exists
        ];
        $response = $this->putJson('/api/menu-items/' . $nonExistentMenuItemId, $data);
        $response->assertStatus(404);
    }
    /**
     * Delete a menu item.
     */
    public function test_Authorized_users_can_delete_menu_item()
    {
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem = MenuItem::first();
        $response = $this->deleteJson('/api/menu-items/' . $menuItem->id);
        $response->assertStatus(200);

    }
    public function test_unAuthorized_users_cannot_delete_menu_item(){
        $user = User::whereIn('role_id', [2, 3, 4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $menuItem = MenuItem::first();
        $response = $this->deleteJson('/api/menu-items/' . $menuItem->id);
        $response->assertStatus(403);
    }
    public function test_returns_not_found_when_deleting_non_existent_menu_item()
    {
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $nonExistentMenuItemId = MenuItem::max('id') + 1;
        $response = $this->deleteJson('/api/menu-items/' . $nonExistentMenuItemId);
        $response->assertStatus(404);
    }





}
