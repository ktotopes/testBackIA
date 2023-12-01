@foreach($order->deliveries as $delivery)
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg text-center">
        <div class="p-5"></div>
        <h2>Ваш заказ</h2>
        <div class="flex flex row">
            <div class="basis-1/3">
                <h3>Отправитель</h3>
                <p>Имя отправителя: {{$delivery->sender}}</p>
                <p>Контакты отправителя: {{$delivery->sender_contact}}</p>
                <p>Откуда: {{$delivery->from_address}}</p>
                <p>Координаты: {{$delivery->from_coordinates}}</p>
            </div>
            <div class="basis-1/3">
                <h3>Получатель</h3>
                <p>Имя отправителя: {{$delivery->recipient}}</p>
                <p>Контакты отправителя: {{$delivery->recipient_contact}}</p>
                <p>Откуда: {{$delivery->to_address}}</p>
                <p>Координаты: {{$delivery->to_coordinates}}</p>
            </div>
            <div class="basis-1/3">
                <h3>Информация об отправлении</h3>
                <p>Дата отправки: {{$delivery->should_delivered}}</p>
                <p>Вес посылки: {{$delivery->weights}}</p>
                <p>Цена посылки: {{$delivery->price}}</p>
                <p>Расстояние: {{$delivery->distance}}</p>
                <p>Статус заказа: {{$delivery->order->status}}</p>
            </div>
        </div>
    </div>
@endforeach
