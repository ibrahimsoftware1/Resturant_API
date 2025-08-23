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
     *  @OA\Get(
     *      path="/api/menu-items",
     *      summary="Get all menu items",
     *      tags={"Menu Items"},
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="List of menu items",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     *  )
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
     * @OA\Post(
     *      path="/api/menu-items",
     *      summary="Create a menu item",
     *      tags={"Menu Items"},
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"name","category_id","price"},
     *                  @OA\Property(property="name", type="string", example="Pizza"),
     *                  @OA\Property(property="category_id", type="integer", example=1),
     *                  @OA\Property(property="price", type="number", format="float", example=12.5)
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Menu item created",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     * )
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
     * @OA\Get(
     *      path="/api/menu-items/{id}",
     *      summary="Get a menu item",
     *      tags={"Menu Items"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Menu item details"),
     *     @OA\Response(response=404, description="Menu item not found")
     *  )
     *
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
     * @OA\Put(
     *      path="/api/menu-items/{id}",
     *      summary="Update a menu item",
     *      tags={"Menu Items"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="Updated Pizza"),
     *              @OA\Property(property="category_id", type="integer", example=2),
     *              @OA\Property(property="price", type="number", format="float", example=14.0)
     *          )
     *      ),
     *      @OA\Response(response=200, description="Menu item updated"),
     *      @OA\Response(response=404, description="Menu item not found")
     *  )
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
     * @OA\Delete(
     *       path="/api/menu-items/{id}",
     *       summary="Delete a menu item",
     *       tags={"Menu Items"},
     *       security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200, description="Menu item deleted"),
     *      @OA\Response(response=404, description="Menu item not found")
     *  )
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
