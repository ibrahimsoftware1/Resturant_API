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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy($category)
    {
        try{
            $myCategory = Category::findOrFail($category);
            $this->authorize('delete', $myCategory);

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
