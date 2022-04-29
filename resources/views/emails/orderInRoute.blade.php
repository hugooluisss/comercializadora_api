<div style="background: #EAEAEA; padding: 50px;">
    <div style="width: 600px; margin: 0 auto;">
        @include('emails.header')

        <div style="padding: 40px; min-height: 300px; background: white;">

            <h2 style="color: #0275d8">Hola {{$order->customer->firstname}}</h2>

            <p style="text-align: center"><b>¡¡¡ Tu pedido con No. {{$order->id}} está listo y se encuentra en camino !!!</b></p>
            <p style="text-align: center">Uno de nuestros repartidores va en camino a entregarte tu pedido, en poco tiempo estaremos en el domicilio para hacerte entrega del mismo</p>

            @include('emails.detailOrder', [$order])
        </div>
    </div>
</div>