@component('mail::message')

# {{ __('content.ola') }}, {{ $usuario->nome }}

{{ __('email.verificacao-email-linha1') }}

@component('mail::button', ['url' => $url])
{{ __('content.verificar-email') }}
@endcomponent

{{ __('email.verificacao-email-linha2') }}<br>

{{ config('app.name') }}

@component('mail::subcopy', ['url' => $url])
{{ __('email.verificacao-email-linha3') }} [{{ $url }}]({{ $url }})
@endcomponent
@endcomponent
