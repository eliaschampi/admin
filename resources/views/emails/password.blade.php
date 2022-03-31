@component('mail::message')
# Mensaje de recuperación de contraseña

@component('mail::panel')
 ¡Hola! Has solicitado la recuperación de tu contraseña de acceso al Sistema.
 Aqui te enviamos tu nueva contraseña: {{ $generatedPassword }}
@endcomponent

@component('mail::button', ['url' => 'https://admin.carrioneduca.edu.pe', 'color' => 'success'])
Aeduca Admin
@endcomponent
<br>
@component('mail::subcopy')
*Por seguridad, elimina este mensaje despues de cambiar tu contraseña. Si no fuiste tu comunicate con el soporte*
@endcomponent
<br>

@component('mail::footer')
Enviado por: {{"@" . config("app.name")}} Todos los derechos reservados.
@endcomponent

@endcomponent