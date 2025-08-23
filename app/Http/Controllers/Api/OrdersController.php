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
     * @OA\Get(
     *      path="/api/orders",
     *      summary="Get all orders",
     *      tags={"Orders"},
     *      security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="List of orders")
     * )
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
     *  @OA\Post(
     *      path="/api/orders",
     *      summary="Create an order",
     *      tags={"Orders"},
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"table_id","items"},
     *              @OA\Property(property="table_id", type="integer", example=1),
     *              @OA\Property(
     *                  property="items",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="menu_item_id", type="integer", example=1),
     *                      @OA\Property(property="quantity", type="integer", example=2)
     *                  )
     *             )
     *          )
     *      ),
     *      @OA\Response(response=201, description="Order created")
     *  )
     *
     */
    public function store(StoreOrdersRequest $request)
    {
        $this->authorize('create', Orders::class);
        $orders=Orders::create($request->validated());
        return new OrderResource($orders);
    }

    /**
     * @OA\Get(
     *      path="/api/orders/{id}",
     *      summary="Get a single order",
     *      tags={"Orders"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200, description="Order details"),
     *      @OA\Response(response=404, description="Order not found")
     *  )
     */
    public function show($order)
    {
        try {
            $myOrders= Orders::findOrFail($order);
            $this->authorize('view', $myOrders);
            return new OrderResource($myOrders);

        }catch (ModelNotFoundException $exception){
            return $this->error('the order with id '.$order.' not found' , 404);
        }catch (AuthorizationException $exception){
            return $this->error('You are not Allowed to view this order', 403);
        }
    }



    /**
     *  @OA\Put(
     *      path="/api/orders/{id}",
     *      summary="Update an order",
     *      tags={"Orders"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="served")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Order updated"),
     *      @OA\Response(response=403, description="Not allowed"),
     *      @OA\Response(response=404, description="Order not found")
     *  )
     */
    public function update(UpdateOrdersRequest $request, Orders $order)
    {
        $this->authorize('update', $order);

        $order->update($request->validated());

        return new OrderResource($order);
    }

    /**
     * @OA\Delete(
     *      path="/api/orders/{id}",
     *      summary="Delete an order",
     *      tags={"Orders"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200, description="Order deleted"),
     *      @OA\Response(response=404, description="Order not found")
     *  )
     */
    public function destroy($order)
    {
        try {
            $myOrders = Orders::findOrFail($order);
            $this->authorize('delete', $myOrders);
            $myOrders->delete();
            return $this->ok('the order was deleted successfully', 200);

        }catch (ModelNotFoundException $exception){
            return $this->error('the order with id '.$order.' not found' , 404);
        }catch (AuthorizationException $exception){
            return $this->error('You are not Allowed to delete this order', 403);
        }
    }
}
