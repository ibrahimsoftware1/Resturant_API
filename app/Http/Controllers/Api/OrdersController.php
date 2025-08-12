<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\OrdersFilter;
use App\Http\Requests\StoreOrdersRequest;
use App\Http\Requests\UpdateOrdersRequest;
use App\Http\Resources\OrderResource;
use App\Models\Orders;
use App\permissions\Abilities;
use App\Policies\OrderPolicy;
use App\Trait\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrdersController extends Controller
{
    protected $policyClass=OrderPolicy::class;

    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * you can filter orders by status using the 'status' query parameter.
     * http://127.0.0.1:8000/api/orders?status=served
     */
    public function index(OrdersFilter $filters){

        $this->authorize('viewAny', Orders::class);

        $user = auth()->user();

        $query = Orders::filter($filters);

        if ($user->tokenCan(Abilities::ORDERS_VIEW_OWN)) {
            $query->where('user_id', $user->id);
        }

        return OrderResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrdersRequest $request)
    {
        $orders=Orders::create($request->validated());
        return new OrderResource($orders);
    }

    /**
     * Display the specified resource.
     */
    public function show($orders)
    {
        try {
            $myOrders= Orders::findOrFail($orders);
            $this->authorize('view', $myOrders);
            return new OrderResource($myOrders);

        }catch (ModelNotFoundException $exception){
            return $this->error('the order with id '.$orders.' not found' , 404);
        }catch (AuthorizationException $exception){
            return $this->error('You are not Allowed to view this order', 403);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrdersRequest $request,$orders)
    {
        try {
            $myOrders= Orders::findOrFail($orders);
            $this->authorize('update', $myOrders);

                $myOrders->update($request->validated());
                return new OrderResource($myOrders);

        }catch (ModelNotFoundException $exception){
            return ['the order with id '.$orders.' not found' , 404];
        }catch (AuthorizationException $exception){
            return ['You are not Allowed to update this order', 403];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($orders)
    {
        try {
            $myOrders = Orders::findOrFail($orders);
            $this->authorize('delete', $myOrders);
            $myOrders->delete();
            return $this->error('the order was deleted successfully', 200);

        }catch (ModelNotFoundException $exception){
            return $this->error('the order with id '.$orders.' not found' , 404);
        }catch (AuthorizationException $exception){
            return $this->error('You are not Allowed to delete this order', 403);
        }
    }
}
