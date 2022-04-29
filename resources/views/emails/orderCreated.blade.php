<div style="background: #EAEAEA; padding: 50px;">
    <div style="width: 600px; margin: 0 auto;">
        @include('emails.header')

        <div style="padding: 40px; min-height: 300px; background: white;">

            <h2 style="color: #0275d8">Hola {{$order->customer->firstname}}</h2>

            <p style="text-align: center">Tenemos buenas noticias </p>
            <p style="text-align: center"><b>¡¡¡ Hemos recibido tu pedido con No. {{$order->id}} !!!</b></p>
            
            @include('emails.detailOrder', [$order])

            <p style="text-align: justify">Estamos trabajando para entregártelo lo más pronto posible. Te mantendremos informado</p>
        </div>
    </div>
</div>