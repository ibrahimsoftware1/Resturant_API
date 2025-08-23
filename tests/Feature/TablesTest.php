<?php
namespace Tests\Feature;

use App\Models\Tables;
use App\permissions\Abilities;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;

class TablesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_Authorized_users_can_view_list_of_tables()
    {
        $user = User::whereIn('role_id', [1, 2, 4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $response = $this->getJson('/api/tables');
        $response->assertStatus(200);
    }

    public function test_unauthorized_user_cannot_view_list_of_tables()
    {

        $user = User::where('role_id', 3)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $response = $this->getJson('/api/tables');
        $response->assertStatus(403);
    }

    public function test_Authorized_users_can_view_single_table()
    {
        $user = User::whereIn('role_id', [1, 2, 4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $table = Tables::first();
        $response = $this->getJson('/api/tables/'.$table->id);
        $response->assertStatus(200);
    }


    public function test_unauthorized_user_cannot_view_single_table()
    {
        $user = User::where('role_id', 3)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $table = Tables::first();
        $response = $this->getJson('/api/tables/'.$table->id);
        $response->assertStatus(403);
    }


    public function test_returns_not_found_when_viewing_non_existent_table()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $nonExistentTableId = Tables::max('id') + 1;
        $response = $this->getJson('/api/tables/'.$nonExistentTableId);
        $response->assertStatus(404);
    }


    public function test_Authorized_users_can_create_table()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $response = $this->postJson('/api/tables', [
            'name' => 'Table 1',
            'status' => 'available',
        ]);
        $response->assertStatus(201);
    }


    public function test_unauthorized_user_cannot_create_table()
    {
        $user = User::whereIn('role_id', [3, 4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $response = $this->postJson('/api/tables', [
            'name' => 'Table 1',
            'status' => 'available',
        ]);
        $response->assertStatus(403);
    }


    public function test_cannot_create_table_with_invalid_name()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $response = $this->postJson('/api/tables', [
            'name' => 22,
            'status' => 'available',
        ]);
        $response->assertStatus(422);
    }


    public function test_cannot_create_table_with_invalid_status()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $response = $this->postJson('/api/tables', [
            'name' => 'Table 1',
            'status' => 'some_invalid_status',
        ]);
        $response->assertStatus(422);
    }


    public function test_Authorized_users_can_update_table()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $table = Tables::first();
        $response = $this->putJson('/api/tables/'.$table->id, [
            'name' => 'Updated Table',
            'status' => 'available',
        ]);
        $response->assertStatus(200);
    }


    public function test_unauthorized_user_cannot_update_table()
    {
        $user = User::whereIn('role_id', [3, 4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $table = Tables::first();
        $response = $this->putJson('/api/tables/'.$table->id, [
            'name' => 'Updated Table',
            'status' => 'available',
        ]);
        $response->assertStatus(403);
    }


    public function test_cannot_update_table_with_invalid_name()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $table = Tables::first();
        $response = $this->putJson('/api/tables/'.$table->id, [
            'name' => 3232,
            'status' => 'available',
        ]);
        $response->assertStatus(422);
    }


    public function test_cannot_update_table_with_invalid_status()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $table = Tables::first();
        $response = $this->putJson('/api/tables/'.$table->id, [
            'name' => 'Updated Table',
            'status' => 'some_invalid_status',
        ]);
        $response->assertStatus(422);
    }


    public function test_returns_not_found_when_updating_non_existent_table()
    {
        $user = User::whereIn('role_id', [1, 2])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $nonExistentTableId = Tables::max('id') + 1;
        $response = $this->putJson('/api/tables/'.$nonExistentTableId, [
            'name' => 'Updated Table',
            'status' => 'available',
        ]);
        $response->assertStatus(404);
    }


    public function test_only_admin_can_delete_table()
    {
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $table = Tables::first();
        $response = $this->deleteJson("/api/tables/{$table->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tables', ['id' => $table->id]);
    }


    public function test_unauthorized_user_cannot_delete_table()
    {
        $user = User::whereIn('role_id', [2, 3, 4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $table = Tables::first();
        $response = $this->deleteJson("/api/tables/{$table->id}");
        $response->assertStatus(403);
    }


    public function test_returns_not_found_when_deleting_non_existent_table()
    {
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $nonExistentTableId = Tables::max('id') + 1;
        $response = $this->deleteJson("/api/tables/{$nonExistentTableId}");
        $response->assertStatus(404);
    }
}
