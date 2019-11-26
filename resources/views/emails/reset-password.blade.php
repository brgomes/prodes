@component('mail::message')

# {{ __('content.ola') }}, {{ $usuario->nome }}

{{ __('email.redefinicao-senha-linha1') }}

@component('mail::button', ['url' => $url])
{{ __('content.redefinir-senha') }}
@endcomponent

{{ __('email.redefinicao-senha-linha2') }}<br>

{{ config('app.name') }}

@component('mail::subcopy', ['url' => $url])
{{ __('email.redefinicao-senha-linha3') }} [{{ $url }}]({{ $url }})
@endcomponent
@endcomponent
