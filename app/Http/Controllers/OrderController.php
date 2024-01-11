<?php

namespace App\Http\Controllers;

use App\Enum\OrderStatus;
use App\Http\Requests\OrderRequest;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user()->id;

        $orders = Order::where('user_id', $user)->with('deliveries.products')->paginate(4);

        return response()->json([
            'status' => true,
            'data' => $orders
        ]);
    }

    public function show(Order $order)
    {
        $order->load('deliveries.products');

        return response()->json([
            'status' => true,
            'data' => $order
        ]);
    }

    public function store(OrderRequest $request)
    {
        $order = new Order([
            'user_id' => auth()->user()->id,
            'status' => OrderStatus::randomValue(),
        ]);
        $order->save();

        foreach ($request->get('deliveries') as $delivery) {
            $d = new Delivery([
                'order_id' => $order->id,
                'from_address' => $delivery['from_address'],
                'to_address' => $delivery['to_address'],
                'sender' => $delivery['sender'],
                'sender_contact' => $delivery['sender_contact'],
                'recipient' => $delivery['recipient'],
                'recipient_contact' => $delivery['recipient_contact'],
                'should_delivered' => $delivery['should_delivered'],
                'from_coordinates' => $delivery['from_coordinates'],
                'to_coordinates' => $delivery['to_coordinates'],
                'distance' => $this->calcDistance($delivery),
                'price' => $this->calcPrice($delivery),
                'weights' => $this->calcWeight($delivery)
            ]);
            $d->save();

            foreach ($delivery['products'] as $product) {
                (new Product([
                    'delivery_id' => $d->id,
                    'name' => $product['name'],
                    'weight' => $product['weight'],
                    'quantity' => $product['quantity'],
                ]))->save();
            }
        }

        $order->load('deliveries.products');

        return response()->json([
            'status' => true,
            'data' => $order
        ]);
    }

    private function calcDistance(array $delivery): float
    {
        return $this->getDistanceFromLatLonInKm(
            [...explode(', ', $delivery['from_coordinates']), ...explode(', ', $delivery['to_coordinates'])]
        );
    }

    private function getDistanceFromLatLonInKm(): float
    {
        [$lat1, $lon1, $lat2, $lon2] = func_get_args()[0];

        $r = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $r * $c;
    }

    private function calcPrice(array $delivery): float
    {
        $distance = $this->calcDistance($delivery);
        $sum = 0;

        foreach ($delivery['products'] as $product) {
            $sum += ((int)$product['weight'] * 4) + ((int)$product['quantity'] * 2);
        }
        $sum += $distance / 6;

        return $sum;
    }

    private function calcWeight(array $delivery): float
    {
        $weights = 0;

        foreach ($delivery['products'] as $product) {
            $weights += (int)$product['weight'];
        }

        return $weights;
    }
}
