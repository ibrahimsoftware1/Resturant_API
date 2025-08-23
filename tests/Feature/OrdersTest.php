<?php
namespace Tests\Feature;

use App\Models\Orders;
use App\Models\Tables;
use App\permissions\Abilities;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
class OrdersTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Viewing All Orders
     */
    public function test_authorized_users_can_view_all_orders()
    {
        $user=User::whereIn('role_id',[1,2,3])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $response = $this->getJson('/api/orders');
        $response->assertStatus(200);
    }
    public function test_unauthorized_users_cannot_view_all_orders()
    {
        $user=User::where('role_id',4)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $response = $this->getJson('/api/orders');
        $response->assertStatus(403);
    }

    /**
     * Viewing a Single Order
     */
    public function test_authorized_users_can_view_single_order()
    {
        $user = User::whereIn('role_id', [1, 2, 3])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $orderId = Orders::first();
        $response = $this->getJson("/api/orders/{$orderId->id}");
        $response->assertStatus(200);
    }

    public function test_authorized_waiter_can_only_view_his_own_order()
    {
        $waiter = User::where('role_id', 4)->first(); // adjust to your waiter role_id
        $order = Orders::factory()->create(['user_id' => $waiter->id]);

        Sanctum::actingAs($waiter, Abilities::getAbilities($waiter));

        $response = $this->getJson("/api/orders/{$order->id}");
        $response->assertStatus(200);
    }

    public function test_unauthorized_waiter_cannot_view_others_order()
    {
        $waiter = User::where('role_id', 4)->first(); // waiter
        $otherUser = User::where('role_id', 4)->where('id', '!=', $waiter->id)->first();
        $order = Orders::factory()->create(['user_id' => $otherUser->id]);

        Sanctum::actingAs($waiter, Abilities::getAbilities($waiter));

        $response = $this->getJson("/api/orders/{$order->id}");
        $response->assertStatus(403);
    }
    public function test_returns_not_found_when_trying_to_view_order_that_is_nonexist()
    {
        $user = User::whereIn('role_id', [1, 2, 3])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $notfoundOrderId =Orders::max('id') + 1; // Assuming this ID does not exist
        $response = $this->getJson('/api/orders/'.$notfoundOrderId); // Assuming this ID does not exist
        $response->assertStatus(404);
    }
    /**
     * Creating an Order
     */
    public function test_authorized_users_can_create_order()
    {
        $user = User::whereIN('role_id', [1,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $table = Tables::first();
        $data = [
            'user_id' => $user->id,
            'table_id' => $table->id,
            'status' => fake()->randomElement(['pending','completed','cancelled','preparing','served']),
        ];
        $response = $this->postJson('/api/orders', $data);
        $response->assertStatus(201);
    }
    public function test_unauthorized_users_cannot_create_order()
    {
        $user = User::whereIn('role_id',[2,3])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $data = [
            'user_id' => $user->id,
            'table_id' => Tables::first()->id,
            'status' => fake()->randomElement(['pending','completed','cancelled','preparing','served']),
        ];
        $response = $this->postJson('/api/orders', $data);
        $response->assertStatus(403);
    }
    /**
     * Updating an Order
     */
    public function test_admin_and_managers_can_update_all_orders(){
        $user = User::whereIn('role_id',[ 1,2])->first(); // Admin
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $order = Orders::first();
        $data = [
            'user_id' => 13,
            'table_id' => 14,
            'status' =>fake()->randomElement(['pending','completed','cancelled','preparing','served'])
        ];
        $response = $this->putJson("/api/orders/{$order->id}", $data);
        $response->assertStatus(200);

    }
    public function test_chefs_can_update_only_status_to_preparing(){
        $chef = User::where('role_id', 3)->first();
        Sanctum::actingAs($chef, Abilities::getAbilities($chef));
        $order = Orders::factory()->create([
            'status' => 'pending',
        ]);
        $data = [
            'status' =>'preparing',
        ];
        $response = $this->putJson("/api/orders/".$order->id, $data);
        $response->assertStatus(200);
    }
    public function test_chefs_can_update_only_status_to_pending(){
        $chef = User::where('role_id', 3)->first();
        Sanctum::actingAs($chef, Abilities::getAbilities($chef));
        $order = Orders::first();
        $data = [
            'status' =>'pending',
        ];
        $response = $this->putJson("/api/orders/".$order->id, $data);
        $response->assertStatus(200);
    }
    public function test_chefs_can_update_only_status_to_completed(){
    $chef = User::where('role_id', 3)->first();
    Sanctum::actingAs($chef, Abilities::getAbilities($chef));
    $order = Orders::first();
    $data = [
        'status' =>'completed',
    ];
    $response = $this->putJson("/api/orders/".$order->id, $data);
    $response->assertStatus(200);
}
    public function test_chefs_cannot_update_status_to_served_or_cancelled(){
        $chef = User::where('role_id', 3)->first();
        Sanctum::actingAs($chef, Abilities::getAbilities($chef));
        $order = Orders::first();
        $data = [
            'status' =>'served',
        ];
        $response = $this->putJson("/api/orders/".$order->id, $data);
        $response->assertStatus(403);

        $data = [
            'status' =>'cancelled',
        ];
        $response = $this->putJson("/api/orders/".$order->id, $data);
        $response->assertStatus(403);
    }

    public function test_waiters_can_update_only_their_own_orders_to_cancelled(){
        $waiter = User::where('role_id', 4)->first(); // Waiter
        Sanctum::actingAs($waiter, Abilities::getAbilities($waiter));

        $order = Orders::factory()->create([
            'user_id' => $waiter->id,
            'status' => 'pending',
        ]);

       // $order = Orders::where('user_id', $waiter->id)->first();
        $data = [
            'status' =>'cancelled',
        ];
        $response = $this->putJson("/api/orders/{$order->id}", $data);
        $response->assertStatus(200);
    }

    public function test_waiters_can_update_only_their_own_orders_to_served(){
        $waiter = User::where('role_id', 4)->first(); // Waiter
        Sanctum::actingAs($waiter, Abilities::getAbilities($waiter));

        $order = Orders::factory()->create([
            'user_id' => $waiter->id,
            'status' => 'pending',
        ]);

        // $order = Orders::where('user_id', $waiter->id)->first();
        $data = [
            'status' =>'served',
        ];
        $response = $this->putJson("/api/orders/{$order->id}", $data);
        $response->assertStatus(200);
    }
    public function test_return_fails_when_waiters_update_orders_to_preparing_pending_completed(){
        $waiter = User::where('role_id', 4)->first(); // Waiter
        Sanctum::actingAs($waiter, Abilities::getAbilities($waiter));

        $order = Orders::factory()->create([
            'user_id' => $waiter->id,
            'status' => 'served',
        ]);

        $data = [
            'status' =>'preparing',
        ];
        $response = $this->putJson("/api/orders/{$order->id}", $data);
        $response->assertStatus(403);

        $data = [
            'status' =>'completed',
        ];
        $response = $this->putJson("/api/orders/{$order->id}", $data);
        $response->assertStatus(403);

        $data = [
            'status' =>'pending',
        ];
        $response = $this->putJson("/api/orders/{$order->id}", $data);
        $response->assertStatus(403);

    }

    /**
     * Deleting an Order
     */

    public function test_admin_and_managers_can_delete_all_orders(){
        $user = User::whereIn('role_id',[ 1,2])->first(); // Admin
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $order = Orders::first();
        $response = $this->deleteJson("/api/orders/{$order->id}");
        $response->assertStatus(200);
    }
    public function test_chefs_and_waiters_cannot_delete_orders(){
        $user = User::whereIn('role_id',[ 3,4])->first(); // chef or waiter
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $order = Orders::first();
        $response = $this->deleteJson("/api/orders/{$order->id}");
        $response->assertStatus(403);
    }


}
