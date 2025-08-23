<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Trait\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     * path="/api/category",
     * summary="Get all categories",
     * tags={"Categories"},
     * security={{"sanctum":{}}},
     * @OA\Response(response=200, description="List of categories")
     * )
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', Category::class);
                return CategoryResource::collection(Category::paginate());
        }catch (AuthorizationException $exception){
            return $this->error('You are not authorized to view categories', 403);
        }
    }

/**
* @OA\Post(
*     path="/api/category",
*     summary="Create a category",
*     tags={"Categories"},
*     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="Beverages")
*         )
 *     ),
 *     @OA\Response(response=201, description="Category created")
* )
 * */
    public function store(StoreCategoryRequest $request)
    {
        try{
            $this->authorize('create', Category::class);
                $category = Category::create($request->validated());
                return new CategoryResource($category);
        }catch (AuthorizationException $exception){
            return $this->error('You are not authorized to create categories', 403);

        }

    }

    /**
     * @OA\Get(
     *     path="/api/category/{id}",
     *     summary="Get a category",
     *    tags={"Categories"},
     *      security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200, description="Category details"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function show($category)
    {
        try {
            $myCategory = Category::findOrFail($category);
            $this->authorize('view', $myCategory);
            return new CategoryResource($myCategory);

        } catch (ModelNotFoundException $exception) {
            return $this->error('The category with ID ' . $category . ' not found', 404);
        }catch (AuthorizationException $exception){
            return $this->error('You are not authorized to view this category', 403);
        }
    }


    /**
     *  @OA\Put(
     *      path="/api/category/{id}",
     *      summary="Update a category",
     *      tags={"Categories"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="Updated Category")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Category updated"),
     *      @OA\Response(response=404, description="Category not found")
     *  )
 */
    public function update(UpdateCategoryRequest $request,$category)
    {
        try {
            $myCategory = Category::findOrFail($category);
            $this->authorize('update', $myCategory);
            $myCategory->update($request->validated());
            return new CategoryResource($myCategory);

        }catch (ModelNotFoundException $exception){
            return $this->error('The category with ID ' . $category . ' not found', 404);
        }catch (AuthorizationException $exception){
            return $this->error('You are not authorized to update this category', 403);
        }

    }

    /**
     *  @OA\Delete(
     *      path="/api/category/{id}",
     *      summary="Delete a category",
     *      tags={"Categories"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Category deleted"),
     *      @OA\Response(response=404, description="Category not found")
     *  )
 */
    public function destroy($category)
    {
        try{
            $myCategory = Category::findOrFail($category);
            $this->authorize('delete', $myCategory);
                // Check if category has menu items
                if ($myCategory->menuItems()->exists()) {
                    return response()->json([
                        'message' => 'Cannot delete category because it is being used by menu items.'
                    ], 422);
                }
                $myCategory->delete();
                return $this->ok('The category was deleted successfully', 200);

        }catch (ModelNotFoundException $exception){
            return $this->error('The category with ID ' . $category . ' not found', 404);
        }
        catch (AuthorizationException $exception){
            return $this->error('You are not authorized to delete this category', 403);
        }
    }
}
