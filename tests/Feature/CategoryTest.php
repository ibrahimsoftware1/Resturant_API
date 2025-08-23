<?php
namespace Tests\Feature;

use App\Models\Category;
use App\permissions\Abilities;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;

class CategoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * View all categories
     */
    public function test_Authorized_users_can_view_all_categories()
    {
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $response = $this->getJson('/api/category');
        $response->assertStatus(200);

    }
    public function test_Unauthorized_users_cannot_view_all_categories()
    {
        $user=User::whereIn('role_id',[3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $response = $this->getJson('/api/category');
        $response->assertStatus(403);
    }
    /**
     * View a single category
     */
    public function test_Authorized_users_can_view_a_single_category(){
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $response = $this->getJson('/api/category/'.Category::first()->id);
        $response->assertStatus(200);
    }

    public function test_Unauthorized_users_cannot_view_a_single_category(){
        $user=User::whereIn('role_id',[3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category=Category::first();
        $response = $this->getJson('/api/category/'.$category->id);
        $response->assertStatus(403);
    }

    public function test_returns_not_found_when_trying_to_view_nonexisting_category(){
        $user=User::whereIn('role_id',[3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category=Category::max('id') + 1;
        $response = $this->getJson('/api/category/'.$category);
        $response->assertStatus(404);
    }
    /**
     * Create a new category
     */
    public function test_Authorized_users_can_create_a_new_category(){
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $response = $this->postJson('/api/category', [
            'name' => 'Category'.uniqid()
        ]);
        $response->assertStatus(201);
    }

    public function test_Unauthorized_users_cannot_create_a_new_category(){
        $user=User::whereIn('role_id',[3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $response = $this->postJson('/api/category', [
            'name' => 'myCategory'
        ]);
        $response->assertStatus(403);
    }

    public function test_return_fails_when_trying_to_create_a_new_category_with_existing_name(){
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category=Category::first();
        $response = $this->postJson('/api/category', [
            'name' => $category->name
        ]);
        $response->assertStatus(422);
    }

    public function test_return_fails_when_trying_to_create_a_new_category_with_invalid_name(){
        $user=User::whereIn('role_id',[1,2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $response = $this->postJson('/api/category', [
            'name' => 323
        ]);
        $response->assertStatus(422);
    }


    /**
     * Update an category
     */
    public function test_Authorized_users_can_update_an_category()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category = Category::first();


        $response = $this->putJson('/api/category/' . $category->id, [
            'name' => 'Updated Category'.uniqid(),
        ]);
        $response->assertStatus(200);
    }
    public function test_Unauthorized_users_cannot_update_an_category()
    {
        $user = User::whereIn('role_id', [3, 4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category = Category::first();
        $response = $this->putJson('/api/category/' . $category->id, [
            'name' => 'Updated'
        ]);
        $response->assertStatus(403);
    }
    public function test_return_fails_when_trying_to_update_an_category_with_existing_name()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category = Category::first();
        $response = $this->putJson('/api/category/' . $category->id, [
            'name' => $category->name
        ]);
        $response->assertStatus(422);
    }

    public function test_return_fails_when_trying_to_update_an_category_that_is_nonexist(){
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category = Category::max('id') + 1;
        $response = $this->putJson('/api/category/' . $category, [
            'name' => 'Updated Category'
        ]);
        $response->assertStatus(404);
    }

    public function test_return_fails_when_trying_to_update_an_category_with_invalid_name()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category = Category::first();
        $response = $this->putJson('/api/category/' . $category->id, [
            'name' => 323
        ]);
        $response->assertStatus(422);
    }

    /**
     * Delete an category
     */
    public function test_Authorized_users_can_delete_an_category()
    {
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        // Find a category without menu items
        $category = Category::doesntHave('menuItems')->first();

        // If none exist, create one
        if (!$category) {
            $category = Category::factory()->create();
        }

        $response = $this->deleteJson('/api/category/' . $category->id);
        $response->assertStatus(200);
    }

    public function test_Unauthorized_users_cannot_delete_an_category()
    {
        $user = User::whereIn('role_id', [3, 4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category = Category::first();
        $response = $this->deleteJson('/api/category/' . $category->id);
        $response->assertStatus(403);
    }
    public function test_return_fails_when_trying_to_delete_an_category_that_is_nonexist()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category = Category::max('id') + 1;
        $response = $this->deleteJson('/api/category/' . $category);
        $response->assertStatus(404);
    }
    public function test_return_fails_when_trying_to_delete_an_category_that_is_used_in_menu_items()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $category = Category::whereHas('menuItems')->first();
        $response = $this->deleteJson('/api/category/' . $category->id);
        $response->assertStatus(422);
    }


}
