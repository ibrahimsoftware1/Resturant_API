<?php
namespace Tests\Feature;

use App\Models\Tables;
use App\permissions\Abilities;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase{

    use DatabaseTransactions;

    /**
    * Views the list of users.
     */
    public function test_Authorized_users_can_view_list_of_users(){
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
    }

    public function test_unauthorized_user_cannot_view_list_of_users(){
        $user = User::whereIn('role_id',[2,3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $response = $this->getJson('/api/users');
        $response->assertStatus(403);
    }

    public function test_Authorized_users_can_view_single_user(){
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $targetUser = User::first();
        $response = $this->getJson('/api/users/'.$targetUser->id);
        $response->assertStatus(200);
    }

    public function test_unauthorized_user_cannot_view_single_user(){
        $user = User::whereIn('role_id',[2,3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $targetUser = User::first();
        $response = $this->getJson('/api/users/'.$targetUser->id);
        $response->assertStatus(403);
    }

    public function test_returns_not_found_when_viewing_non_existent_user(){
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $nonExistentUserId = User::max('id') + 1;
        $response = $this->getJson('/api/users/'.$nonExistentUserId);
        $response->assertStatus(404);
    }

    /**
     * Update the users.
     */
    public function test_Authorized_user_can_update_user(){
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $email=User::factory()->make()->email;
        $response = $this->patchJson('/api/users/'.$user->id, [
            'name' => 'Updated Name',
            'email' => $email,
            'password' => 'newpassword',
            'role_id' => 1
            ]);
        $response->assertStatus(200);
    }
    public function test_unauthorized_user_cannot_update_user(){
        $user = User::whereIn('role_id',[2,3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $response = $this->patchJson('/api/users/'.$user->id, [
            'name' => 'Updated Name',
            'email' => 'updated1@email',
            'password' => 'newpassword',
            'role_id' => 1
            ]);
        $response->assertStatus(403);
    }
    public function test_returns_not_found_when_updating_non_existent_user(){
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $email=User::factory()->make()->email;
        $nonExistentUserId = User::max('id') + 1;
        $response = $this->patchJson('/api/users/'.$nonExistentUserId, [
            'name' => 'Updated Name',
            'email' => $email,
            'password' => 'newpassword',
            'role_id' => 1
            ]);
        $response->assertStatus(404);
    }
    public function test_returns_successful_when_updating_with_empty_requests(){
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $email=User::factory()->make()->email;
        $existingUser = User::max('id');
        $response = $this->patchJson('/api/users/'.$existingUser, [

        ]);
        $response->assertStatus(200);
    }
    public function test_return_fails__when_updating_with_invalid_name(){
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $email=User::factory()->make()->email;
        $existingUser = User::max('id');
        $response = $this->patchJson('/api/users/'.$existingUser, [
            'name' => 2,
            'email' => $email,
            'password' => 'newpassword',
            'role_id' => 1
        ]);
        $response->assertStatus(500);
    }
    public function test_return_fails_when_updating_with_invalid_email(){
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $email=User::factory()->make()->email;
        $existingUser = User::max('id');
        $response = $this->patchJson('/api/users/'.$existingUser, [
            'name' => 'name',
            'email' => 'email',
            'password' => 'newpassword',
            'role_id' => 1
        ]);
        $response->assertStatus(500);
    }
    public function test_return_fails_when_updating_with_invalid_role_id(){
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $email=User::factory()->make()->email;
        $existingUser = User::max('id');
        $response = $this->patchJson('/api/users/'.$existingUser, [
            'name' => 'name1',
            'email' => $email,
            'password' => 'newpassword',
            'role_id' => 5
        ]);
        $response->assertStatus(500);
    }
    /**
     * Delete the users.
     */
    public function test_Authorized_user_can_delete_user(){
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $targetUser = User::first();
        $response = $this->deleteJson('/api/users/'.$targetUser->id);
        $response->assertStatus(200);
    }
    public function test_unauthorized_user_cannot_delete_user(){
        $user = User::whereIn('role_id',[2,3,4])->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));

        $targetUser = User::first();
        $response = $this->deleteJson('/api/users/'.$targetUser->id);
        $response->assertStatus(403);
    }
    public function test_returns_not_found_when_deleting_non_existent_user()
    {
        $user = User::where('role_id', 1)->first();
        Sanctum::actingAs($user, Abilities::getAbilities($user));
        $nonExistentUserId = User::max('id') + 1;
        $response = $this->deleteJson('/api/users/' . $nonExistentUserId);
        $response->assertStatus(404);
    }
}
