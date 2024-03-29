@if(count($orders))
    @foreach($orders as $order)
        <div class="py-3">
            <h1>Order #{{$order->id}}</h1>
            <table class="w-full border-x border-b">
                <thead>
                <tr>
                    <th class="font-bold p-2 border-b border-l border-indigo-700 text-left bg-indigo-700 text-white">
                        Номер доставки
                    </th>
                    <th class="font-bold p-2 border-b border-l text-left border-indigo-700 bg-indigo-700 text-white">
                        Статус
                    </th>
                    <th class="font-bold py-2 px-4 border-b border-l text-left border-indigo-700 bg-indigo-700 text-white">
                        Стоимость
                    </th>
                    <th class="font-bold py-2 px-4 border-b border-l text-left border-indigo-700 bg-indigo-700 text-white">
                        Вес
                    </th>
                    <th class="font-bold py-2 px-4 border-b border-l text-left border-indigo-700 bg-indigo-700 text-white">
                        Дата доставки
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->deliveries as $delivery)
                    <tr class="odd:bg-gray-100 hover:!bg-stone-200">
                        <td class="p-2 border-b border-l text-left">
                            <a
                                class="text-blue-link underline"
                                href="{{route('admin.orders.show',$order->id)}}"
                            >
                                {{$delivery->id}}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-xs border">
                            <span
                                @class([
                                    'text-orange-700 bg-gray-100' => $order->status == 'formed',
                                    'text-green-700 bg-green-100' => $order->status == 'delivered',
                                    'text-red-700 bg-red-100' => $order->status == 'cancel',
                                    'text-gray-700 bg-gray-100' => $order->status == 'passed'
                                ])
                                class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-gray-100 rounded-sm">
                                {{$order->status}}
                            </span>
                        </td>
                        <td class="p-2 border-b border-l text-left">{{$delivery->price}}</td>
                        <td class="p-2 border-b border-l text-left">{{$delivery->weights}}</td>
                        <td class="py-2 px-4 border-b border-l text-left">
                            {{ \Illuminate\Support\Carbon::parse($delivery->should_delivered)->format('d-m-Y H:i') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
    {{$orders->links()}}
@else
    <h2 class="text-center">У вас нет истории заказов</h2>
@endif
