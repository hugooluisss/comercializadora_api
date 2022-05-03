<div style="background: #EAEAEA; padding: 50px;">
    <div style="width: 600px; margin: 0 auto;">
        @include('emails.header')

        <div style="padding: 40px; min-height: 300px; background: white;">
            <h2 style="color: #0275d8">Hola</h2>

            <p style="text-align: center"><b>Tienes un nuevo cliente registrado con el correo {{$customer->user->email}}, recuerda que debes ingresar a la plataforma Abastock, validar su información y aprobarlo si es que su registro está correcto</b></p>
        </div>
    </div>
</div>