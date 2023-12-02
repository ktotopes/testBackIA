<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=339625fa-bfa8-4794-b5bd-099ee2140560&lang=ru_RU"
            type="text/javascript"></script>
    <script type="text/javascript">
        @foreach($order->deliveries as $delivery)
        ymaps.ready(function () {
            var myMap = new ymaps.Map('map', {
                center: [{{$delivery->from_coordinates}}],
                zoom: 5,
                controls: ['routePanelControl']
            });
            var control = myMap.controls.get('routePanelControl');

            control.routePanel.state.set({
                from: '{{$delivery->from_coordinates}}',
                to: '{{$delivery->to_coordinates}}'
            });
        })
        @endforeach
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.order-display')
            <div id="map" style="width: 600px; height: 400px"></div>
        </div>
    </div>
</x-app-layout>
