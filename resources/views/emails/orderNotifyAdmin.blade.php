<div style="background: #EAEAEA; padding: 50px;">
    <div style="width: 600px; margin: 0 auto;">
        @include('emails.header')

        <div style="padding: 40px; min-height: 300px; background: white;">

            <h2 style="color: #0275d8">La cotización con No {{ $order->id }} ha sido generado</h2>
            
            @include('emails.detailOrder', [$order])
        </div>
    </div>
</div>