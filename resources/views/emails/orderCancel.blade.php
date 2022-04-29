<div style="background: #EAEAEA; padding: 50px;">
    <div style="width: 600px; margin: 0 auto;">
        @include('emails.header')

        <div style="padding: 40px; min-height: 300px; background: white;">

            <h2 style="color: #0275d8">Hola {{$order->customer->firstname}}</h2>

            <p style="text-align: center"><b>¡¡¡ Tu pedido con No. {{$order->id}} ha sido cancelado!!!</b></p>

            @include('emails.detailOrder', [$order])
        </div>
    </div>
</div>