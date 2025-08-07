<?php

namespace App\Http\Controllers\Api;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TableResource;
use App\Http\Requests\StoreTablesRequest;
use App\Http\Requests\UpdateTablesRequest;
use App\Models\Tables;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use function Laravel\Prompts\error;

class TablesController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TableResource::collection(Tables::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTablesRequest $request)
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(Tables $tables)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTablesRequest $request, Tables $tables)
    {
        //
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
