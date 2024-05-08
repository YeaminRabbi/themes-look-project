<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(10);

        return view('order.list', compact('orders'));
    }

    public function orderItems(Order $order){
        $orderItems = OrderItem::where('order_id', $order->id)->paginate(10);

        return view('order.items', compact('orderItems'));
    }
}
