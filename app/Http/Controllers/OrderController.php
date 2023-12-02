<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user()->id;

        $orders = Order::where('user_id', $user)->with('deliveries.products')->paginate(4);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function show(Order $order)
    {
        $order->load('deliveries');

        return view('orders.show', compact('order'));
    }
}
