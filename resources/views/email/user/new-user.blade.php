@component('mail::message')
Hola {{ $user }}.
# Tus credenciales para acceder a CAPROIN SYSTEM

Utiliza estas credenciales para acceder al sistema.

@component('mail::table')
| Usuario | Contraseña |
|:---------|:------------|
| {{ $email }} | {{ $password }} |

@endcomponent
@component('mail::button', ['url' => url('/login')])
Iniciar Sesión
@endcomponent

Gracias,<br>
Caproin System
@endcomponent
