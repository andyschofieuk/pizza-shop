<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCouponRequest;
use App\Models\Coupon;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\OrderItem;
use App\Models\Pizza;
use App\Models\Drink;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->all();
        $data['status'] = 'pending';
        $data['total_amount'] = 0;
        $order = Order::create($data);

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $status = $order->status;
        if ($status != 'pending') {
            return [
                'error' => 'Order already processed or complete, information cannot be updated'
            ];
        }

        $order->update($request->all());

        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function addItem(Order $order, UpdateOrderItemRequest $request)
    {
        $status = $order->status;
        if ($status != 'pending') {
            return [
                'error' => 'Order already processed or complete, items can no longer be modified'
            ];
        }

        $itemType = $request->item_type;
        $itemId = $request->item_id;
        $qty = $request->qty;

        $itemModel = match($itemType) {
            'drink' => Drink::find($itemId),
            'pizza' => Pizza::find($itemId)
        };

        if (is_null($itemModel->id)) {
            return [
                'error' => 'item not found'
            ];
        }

        $orderItem = [
            'order_id' => $order->id,
            'item_type' => $itemType,
            'item_id' => $itemId,
            'quantity' => $qty
        ];

        OrderItem::create($orderItem);
        $order->calculateTotal();

        $order->load('items');
        return new OrderResource($order);
    }

    /**
     * Delete order item and recalculate totals
     *
     * @param Order $order
     * @param OrderItem $orderItem
     * @param FormRequest $request
     * @return OrderResource
     */
    public function removeItem(Order $order, OrderItem $orderItem, FormRequest $request)
    {
        $orderItem->delete();
        $order->calculateTotal();

        return new OrderResource($order);
    }

    public function place(Order $order): array
    {
        $status = $order->status;
        if ($status != 'pending') {
            return [
                'error' => 'Order already processed or complete, you cannot place this order again'
            ];
        }

        $order->status = 'processing';
        $order->save();

        return [
            'success' => 'Your order has been placed! Your ETA is 100000000000000 years. Enjoy :D'
        ];
    }

    /**
     * Apply coupon to order if possible
     *
     * @param Order $order
     * @param StoreCouponRequest $request
     * @return OrderResource|string[]
     */
    public function applyCoupon(Order $order, StoreCouponRequest $request)
    {
//        $status = $order->status;
//        if ($status != 'pending') {
//            return [
//                'error' => 'Order already processed or complete, you cannot place this order again'
//            ];
//        }

        $coupon = Coupon::where('code', $request->coupon)->first();
        if (!$coupon->id || $coupon->status == 'used') {
            return [
                'error' => 'Coupon not found or in use'
            ];
        }

        $order->coupon_id = $coupon->id;
        $order->save();

//        $coupon->status = 'used';
//        $coupon->save();

        $order = $order->calculateTotal();
        $order->load('items');
        return new OrderResource($order);
    }
}
