<div class="p-5">
    <form wire:submit.prevent>
        @foreach($deliveries as $key => $delivery)
            <div @class(['mt-5' => $key > 0])>
                <div class="flex justify-between">
                    <div class="w-1/2 px-5">
                        <h3 class="block text-sm font-medium leading-6 text-gray-900">Данные отправки</h3>

                        <x-input
                            list="fromAddresses{{ $key }}"
                            wire:model.live.debounce.500ms="deliveries.{{ $key }}.from_address"
                            class="w-full my-2"
                            placeholder="Адрес"
                        />
                        @if(count($delivery['from_addresses']) && $delivery['from_addresses'][0]['display_name'] != $delivery['from_address'])
                            <datalist id="fromAddresses{{ $key }}">
                                @foreach($delivery['from_addresses'] as $address)
                                    <option value="{{ $address['display_name'] }}">
                                @endforeach
                            </datalist>
                        @endif
                        <x-input-error for="deliveries.{{ $key }}.from_address"/>

                        <x-input type="text" wire:model.live.debounce.500ms="deliveries.{{$key}}.sender" name="sender"
                                 class="w-full my-2"
                                 placeholder="Отправитель"/>
                        <x-input-error for="deliveries.{{$key}}.sender"/>

                        <x-input type="tel" wire:model.live.debounce.500ms="deliveries.{{$key}}.sender_contact"
                                 name="senderContact"
                                 class="w-full my-2" placeholder="Контакты"/>
                        <x-input-error for="deliveries.{{$key}}.sender_contact"/>
                    </div>
                    <div class="w-1/2 px-5">
                        <h3 class="block text-sm font-medium leading-6 text-gray-900">Получатель</h3>

                        <x-input list="toAddresses{{$key}}" type="text"
                                 wire:model.live.debounce.500ms="deliveries.{{$key}}.to_address"
                                 placeholder="Манежная, д 11" class="w-full my-2"/>
                        @if(count($delivery['to_addresses']) && $delivery['to_addresses'][0]['display_name'] != $delivery['to_address'])
                            <datalist id="toAddresses{{ $key }}">
                                @foreach($delivery['to_addresses'] as $address)
                                    <option value="{{ $address['display_name'] }}">
                                @endforeach
                            </datalist>
                        @endif
                        <x-input-error for="deliveries.{{$key}}.to_address"/>

                        <x-input type="text" wire:model.live.debounce.500ms="deliveries.{{$key}}.recipient"
                                 class="w-full my-2"
                                 placeholder="Получатель"/>
                        <x-input-error for="deliveries.{{$key}}.recipient"/>

                        <x-input type="tel" wire:model.live.debounce.500ms="deliveries.{{$key}}.recipient_contact"
                                 class="w-full my-2"
                                 placeholder="Контакты"/>
                        <x-input-error for="deliveries.{{$key}}.recipient_contact"/>
                    </div>
                </div>

                <div class="w-full px-5">
                    <x-label value="Дата доставки"/>
                    <x-input wire:model.live.debounce.500ms="deliveries.{{$key}}.should_delivered" type="datetime-local"
                             class="w-full my-2"/>
                    <x-input-error for="deliveries.{{$key}}.should_delivered"/>
                </div>
                <div class="w-full mt-3 px-5 flex justify-between">
                    <h3 class="block text-sm font-medium leading-6 text-gray-900">Объекты перевозки:</h3>
                    @if(count($deliveries) > 1 && $key > 0)
                        <x-secondary-button wire:click="deleteAddress({{ $key }})">Удалить адрес</x-secondary-button>
                    @endif
                    <x-secondary-button wire:click="addProduct({{ $key }})">Добавить объект</x-secondary-button>
                </div>
                @foreach($delivery['products'] as $k => $product)
                    <div class="px-5">
                        <div class="mt-2 p-2 border rounded">
                            <x-input type="text"
                                     wire:model.live.debounce.500ms="deliveries.{{ $key }}.products.{{ $k }}.name"
                                     class="w-full my-2" placeholder="Что везем?"/>
                            <x-input-error for="deliveries.{{ $key }}.products.{{ $k }}.name"/>

                            <x-input type="number"
                                     wire:model.live.debounce.500ms="deliveries.{{ $key }}.products.{{ $k }}.weight"
                                     class="w-full my-2" placeholder="Примерный вес"/>
                            <x-input-error for="deliveries.{{ $key }}.products.{{ $k }}.weight"/>

                            <x-input type="number"
                                     wire:model.live.debounce.500ms="deliveries.{{ $key }}.products.{{ $k }}.quantity"
                                     placeholder="Количество единиц" class="w-full my-2"/>
                            <x-input-error for="deliveries.{{ $key }}.products.{{ $k }}.quantity"/>
                        </div>
                    </div>
                @endforeach
                @if($delivery['price'])
                    <div class="px-5 pt-5">
                        <p>Цена доставки:{{round((int)$delivery['price'])}} руб</p>
                        <p>Вес посылки:{{$delivery['weights']}} кг</p>
                    </div>
                @endif
            </div>
        @endforeach
        <div class="mt-5 px-5 flex justify-between">
            <x-button wire:click="save">Оформить заказ</x-button>
            <x-secondary-button wire:click="addDelivery">Добавить адрес</x-secondary-button>
        </div>
    </form>
</div>
