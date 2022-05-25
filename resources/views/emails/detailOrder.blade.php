<table style="width: 100%">
    <tbody>
        <tr>
            <th style="text-align: left; color: #0275d8;">Fecha</th>
            <td>{{$order->created_at}}</td>
        </tr>
        <tr>
            <th style="text-align: left; color: #0275d8;">A nombre de </th>
            <td>{{$order->customer->firstname}} {{$order->customer->lastname}}</td>
        </tr>
        <tr>
            <th style="text-align: left; color: #0275d8;">Celular</th>
            <td>{{$order->customer->phone_movil}}</td>
        </tr>
        <tr>
            <th style="text-align: left; color: #0275d8;">Dirección de entrega</th>
            <td>
                {{$order->customer->address}} {{$order->customer->address2}}, <br />
                {{$order->customer->suburb}}, {{$order->customer->municipality}}, {{$order->customer->state}},
                Código Postal {{$order->customer->zip_code}}
            </td>
        </tr>
        <tr>
            <th style="text-align: left; color: #0275d8;">Referencia</th>
            <td>{{$order->customer->between_streets}}</td>
        </tr>
    </tbody>
</table>

<table style="width: 100%; margin-top: 2rem; margin-bottom: 2rem;">
    <thead>
        <tr>
            <th style="background: #0275d8; color: white">SKU</th>
            <th style="background: #0275d8; color: white">Nombre</th>
            <th style="background: #0275d8; color: white">Cantidad</th>
            <th style="background: #0275d8; color: white">P. U.</th>
            <th style="background: #0275d8; color: white">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @php
            $subtotal = 0;
        @endphp
        @foreach ($order->items as $item)
            <tr>
                <td>{{$item->sku}}</td>
                <td>{{$item->name}}</td>
                <td style="text-align: right">{{$item->pivot->amount}}</td>
                <td style="text-align: right">
                    @if ($item->pivot->price_list > $item->pivot->price)
                        <del style="color: red;">{{$item->pivot->price_list}}</del> 
                    @endif
                    {{$item->pivot->price}}
                </td>
                <td style="text-align: right">@money($item->pivot->amount * $item->pivot->price)</td>

                @php
                $subtotal = $subtotal + $item->pivot->amount * $item->pivot->price;
                @endphp
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" style="text-align: right">Subtotal</td>
            <td style="text-align: right">@money($subtotal)</td>
        </tr>
        <tr>
            <th colspan="4" style="text-align: right">Envío</td>
            <td style="text-align: right">@money($order->shipping_price) <small>({{$order->shipping_name}})</small></td>
        </tr>
        <tr>
            <th colspan="4" style="text-align: right">Total</td>
            <td style="text-align: right">@money($subtotal + $order->shipping_price)</td>
        </tr>
        <tr>
            <th colspan="4" style="text-align: right">Forma de pago</td>
            <td style="text-align: right"><small>({{$order->payment_name}})</small></td>
        </tr>
    </tfoot>
</table>