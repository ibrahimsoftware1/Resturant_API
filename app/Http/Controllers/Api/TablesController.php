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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
