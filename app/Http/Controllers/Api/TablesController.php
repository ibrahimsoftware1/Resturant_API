<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\TableFilter;
use App\Http\Requests\StoreTablesRequest;
use App\Http\Requests\UpdateTablesRequest;
use App\Http\Resources\TableResource;
use App\Models\Tables;
use App\Trait\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TablesController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *       path="/api/tables",
     *       summary="Get all tables",
     *       tags={"Tables"},
     *       security={{"sanctum":{}}},
     *       @OA\Response(
     *           response=200,
     *           description="List of tables",
     *           @OA\MediaType(
     *               mediaType="application/json"
     *           )
     *       )
     * )
     */
    public function index(TableFilter $filters)
    {
        try{
            $this->authorize('viewAny', Tables::class);
            return TableResource::collection(Tables::filter($filters)->paginate());
        }catch (AuthorizationException $exception){
            return $this->error('you are not authorized to view tables', 403);
        }
    }


    /**
     * @OA\Post(
     *      path="/api/tables",
     *      summary="Create a table",
     *      tags={"Tables"},
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 required={"name","status"},
     *                 @OA\Property(property="name", type="string", example="Table 1"),
     *                 @OA\Property(property="status", type="string", example="available")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Table created",
     *          @OA\MediaType(mediaType="application/json")
     *      )
     * )
     */
    public function store(StoreTablesRequest $request)
    {
        try {
            $this->authorize('create', Tables::class);
            $store=Tables::create($request->validated());
            return new TableResource($store);
        }catch (AuthorizationException $exception){
            return $this->error('you are not authorized to create a table', 403);
        }
    }

    /**
     * @OA\Get(
     *       path="/api/tables/{id}",
     *       summary="Get a table",
     *       tags={"Tables"},
     *       security={{"sanctum":{}}},
     *       @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *       @OA\Response(
     *           response=200,
     *           description="Table details",
     *           @OA\MediaType(mediaType="application/json")
     *       ),
     *       @OA\Response(response=404, description="Table not found")
     * )
     */
    public function show($id)
    {
        try{
            $tables=Tables::findOrFail($id);
            $this->authorize('view', $tables);
            return new TableResource($tables);
        }catch (ModelNotFoundException $exception){
            return $this->error('the table with id '.$id.' not found' , 404);
        }catch (AuthorizationException $exception){
            return $this->error('you are not authorized to view this table', 403);
        }
    }
    /**
     * @OA\Put(
     *      path="/api/tables/{id}",
     *      summary="Update a table",
     *      tags={"Tables"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="name", type="string", example="Updated Table"),
     *                  @OA\Property(property="status", type="string", example="reserved")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Table updated",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(response=404, description="Table not found")
     * )
     */
    public function update(UpdateTablesRequest $request,$table_id)
    {
        try{
            $table=Tables::findOrFail($table_id);
            $this->authorize('update', $table);
            $table->update($request->validated());
            return new TableResource($table);

        }catch (ModelNotFoundException $exception){
            return $this->error('the table with id '.$table_id.' not found' , 404);
        }catch (AuthorizationException $exception){
            return $this->error('you are not authorized to update this table', 403);
        }
    }


    /**
     * @OA\Delete(
     *      path="/api/tables/{id}",
     *      summary="Delete a table",
     *      tags={"Tables"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(
     *          response=200,
     *          description="Table deleted",
     *          @OA\MediaType(mediaType="application/json")
     *      ),
     *      @OA\Response(response=404, description="Table not found")
     * )
     */
    public function destroy($tables)
    {
        try {
            $tables= Tables::findOrfail($tables);
            $this->authorize('delete', $tables);
            $tables->delete();
            return ('the table was deleted successfully');
        }
        catch (ModelNotFoundException $exception){
            return $this->error('the table with id '.$tables.' not found' , 404);
        }catch (AuthorizationException $exception){
            return $this->error('you are not authorized to delete this table', 403);
        }
    }
}
