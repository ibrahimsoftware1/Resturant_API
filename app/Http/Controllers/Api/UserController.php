<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Trait\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse;

    public function index(){

        try {
            $this->authorize('viewAny', User::class);
            return (User::all());
        }
        catch (AuthorizationException $e) {
            return $this->error('You are not authorized to view users', 403);
        }
    }

    public function show($user){
        try {
            $user = User::findOrFail($user);
            $this->authorize('view', $user);
            return $user;
        }catch (AuthorizationException $e){
            return $this->error('You are not authorized to view this user', 403);
        }catch (ModelNotFoundException $e){
            return $this->error('User not found', 404);
        }

    }

    public function update($userId) {
        try {
            $user = User::findOrFail($userId);
            $this->authorize('update', $user);

            $validatedData = request()->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:8|confirmed',
                'role_id' => 'sometimes|integer|in:1,2,3,4'
            ]);

            // Hash password if present
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $user->update($validatedData);
            return $this->ok('User updated successfully', $user);

        } catch (AuthorizationException $e) {
            return $this->error('You are not authorized to update this user', 403);
        } catch (ModelNotFoundException $e) {
            return $this->error('User not found', 404);
        } catch (Exception $e) {
            return $this->error('Failed to update user: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(User $user){
        try {
            $this->authorize('delete', $user);
            $user->delete();
            return $this->ok('User deleted successfully');
        }catch (AuthorizationException $e){
            return $this->error('You are not authorized to delete this user', 403);
        }
    }
}
