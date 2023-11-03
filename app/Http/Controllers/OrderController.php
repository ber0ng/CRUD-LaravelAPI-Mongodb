<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function createOrder(Request $request){
        // Create a new order
    $order = new Order();
    $order->shipping_info = $request->input('shipping_info');
    $order->user_id = $request->input('user_id');
    $order->payment_info = $request->input('payment_info');
    $order->total_price = 0; // You can calculate the total price based on order items
    // Save the order
    $order->save();

    $orderItems = $request->input('order_items');

    if (!is_array($orderItems)) {
        return response()->json(['error' => 'order_items must be an array'], 400);
    }

    Log::info('Received request data:', ['request' => $request->all()]);

    // Handle order items
    foreach ($request->input('order_items') as $itemData) {
        $orderItem = new OrderItem();
        $orderItem->name = $itemData['name'];
        $orderItem->quantity = $itemData['quantity'];
        $orderItem->image = $itemData['image'];
        $orderItem->price = $itemData['price'];

        // Calculate the total price per order item
        $orderItem->total_price = $itemData['quantity'] * $itemData['price'];
        // Save the order item and associate it with the order
        $order->items()->save($orderItem);
        // Update the total price of the order
        $order->total_price += $orderItem->total_price;
    }
    $order->save();
    return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    }
}
