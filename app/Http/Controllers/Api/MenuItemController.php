<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use App\Trait\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MenuItemController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', MenuItem::class);
            return MenuItemResource::collection(MenuItem::paginate());
        }catch (AuthorizationException $exception){
            return $this->error('You are not authorized to view menu items', 403);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuItemRequest $request)
    {
        try {
            $this->authorize('create', MenuItem::class);
            $menuItem = MenuItem::create($request->validated());
            return new MenuItemResource($menuItem);

        }catch (AuthorizationException $exception){
            return $this->error('You are not authorized to create menu items', 403);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show( $menuItem)
    {
        try {
            $myMenuItem = MenuItem::findOrFail($menuItem);
            $this->authorize('view', $myMenuItem);
            return new MenuItemResource($myMenuItem);

        }catch (ModelNotFoundException $exception){
            return $this->error('the menu item with id '.$menuItem.' not found' , 404);
        }catch (AuthorizationException $exception){
            return $this->error('You are not authorized to view this menu item', 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuItemRequest $request ,$menuItem)
    {
        try{
            $item= MenuItem::findOrFail($menuItem);
            $this->authorize('update', $item);
            $item->update($request->validated());
            return $this->ok('the menu item was updated successfully',200);

        }catch (ModelNotFoundException $exception){
            return $this->error('the menu item with id '.$menuItem.' not found' , 404);
        }catch (AuthorizationException $exception){
            return $this->error('You are not authorized to update this menu item', 403);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($menuItem)
    {
        try{
            $item= MenuItem::findOrFail($menuItem);
            $this->authorize('delete', $item);
            $item->delete();
            return $this->ok('the menu item was deleted successfully',200);
        }catch (ModelNotFoundException $exception){
            return $this->error('the menu item with id '.$menuItem.' not found' , 404);
        }catch (AuthorizationException $exception){
            return $this->error('You are not authorized to delete this menu item', 403);
        }
    }
}
