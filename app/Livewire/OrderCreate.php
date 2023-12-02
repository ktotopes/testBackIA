<?php

namespace App\Livewire;

use App\Enum\OrderStatus;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Product;
use App\Rules\AddressRule;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class OrderCreate extends Component
{
    public array $deliveries = [];

    public function rules(): array
    {
        return [
            'deliveries.*.from_address' => ['required', 'string', new AddressRule()],
            'deliveries.*.to_address' => ['required', 'string', new AddressRule()],
            'deliveries.*.sender' => 'required|string|min:2',
            'deliveries.*.sender_contact' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'deliveries.*.recipient' => 'required|string|min:2',
            'deliveries.*.recipient_contact' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'deliveries.*.should_delivered' => 'required|date',


            'deliveries.*.products.*.name' => 'required|string|min:2',
            'deliveries.*.products.*.weight' => 'required|integer|min:0.1',
            'deliveries.*.products.*.quantity' => 'required|integer|min:1',
        ];
    }

    private array $deliveryTemplate = [
        'order_id' => null,

        'from_address' => '',
        'to_address' => '',

        'from_coordinates' => '',
        'to_coordinates' => '',

        'distance' => '',
        'price' => '',
        'weights' => '',

        'sender' => '',
        'sender_contact' => '',
        'recipient' => '',
        'recipient_contact' => '',

        'should_delivered' => '',

        'from_addresses' => [],
        'to_addresses' => [],
        'products' => [],
    ];

    private array $productTemplate = [
        'delivery_id' => null,
        'name' => '',
        'weight' => '',
        'quantity' => '',
    ];

    public function mount(): void
    {
        $this->addNewDelivery();
    }

    public function updated(): void
    {
        foreach ($this->deliveries as $key => $delivery) {
            if ($fromAddressString = $delivery['from_address']) {
                $fromAddresses = $this->loadAddressesFromApi($fromAddressString);

                $this->deliveries[$key]['from_addresses'] = count($fromAddresses) ? $fromAddresses : $this->deliveries[$key]['from_addresses'];
                $this->deliveries[$key]['from_coordinates'] = $this->parseCoordinates($this->deliveries[$key]['from_addresses']);
            }

            if ($toAddressString = $delivery['to_address']) {
                $toAddresses = $this->loadAddressesFromApi($toAddressString);

                $this->deliveries[$key]['to_addresses'] = count($toAddresses) ? $toAddresses : $this->deliveries[$key]['to_addresses'];
                $this->deliveries[$key]['to_coordinates'] = $this->parseCoordinates($this->deliveries[$key]['to_addresses']);;
            }

            if ($delivery['from_coordinates'] && $delivery['to_coordinates']) {
                $this->deliveries[$key]['price'] = $this->calcPrice($delivery);
                $this->deliveries[$key]['weights'] = $this->calcWeight($delivery);
            }
        }
    }

    public function save(): void
    {
        $this->validate();

        $order = new Order([
            'user_id' => auth()->user()->id,
            'status' => OrderStatus::randomValue(),
        ]);
        $order->save();

        foreach ($this->deliveries as $delivery) {

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
        $this->redirectRoute('orders.show', ['order' => $order->id]);
    }

    public function addDelivery(): void
    {
        $this->addNewDelivery();
    }

    public function addProduct($deliveryIndex): void
    {
        $this->deliveries[$deliveryIndex]['products'][] = $this->productTemplate;
    }

    public function deleteAddress(int $key): void
    {
        array_splice($this->deliveries, $key, 1);
    }

    public function render()
    {
        return view('livewire.order-create');
    }

    private function loadAddressesFromApi(string $address): array
    {
        $response = Http::get('https://geocode.maps.co/search', ['q' => $address]);

        if ($response->status() !== 200) {
            return [];
        }

        return $response->json();
    }

    private function addNewDelivery(): void
    {
        $delivery = [...$this->deliveryTemplate, ...['products' => [$this->productTemplate]]];

        $this->deliveries[] = $delivery;
    }

    private function parseCoordinates(array $data): string
    {
        if (!count($data)) {
            return '';
        }

        return $data[0]['lat'] . ', ' . $data[0]['lon'];
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
