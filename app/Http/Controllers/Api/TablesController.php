<?php

namespace App\Http\Controllers\Api;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Filters\TableFilter;
use App\Http\Resources\TableResource;
use App\Http\Requests\StoreTablesRequest;
use App\Http\Requests\UpdateTablesRequest;
use App\Models\Tables;
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
        return TableResource::collection(Tables::filter($filters)->paginate());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTablesRequest $request)
    {
        $store=Tables::create($request->validated());

        return new TableResource($store);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $tables=Tables::findOrFail($id);
            return new TableResource($tables);
        }catch (ModelNotFoundException $exception){

            return $this->error('the table with id '.$id.' not found' , 404);
        }

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTablesRequest $request,$table_id)
    {
        try{
            $table=Tables::findOrFail($table_id);
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
            $tables->delete();
            return $this->success('the table was deleted successfully', 200);
        }
        catch (ModelNotFoundException $exception){
            return $this->error('the table with id '.$tables.' not found' , 404);
        }
    }
}
