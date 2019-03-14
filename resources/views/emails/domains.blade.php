@component('mail::message')
## Following domains are free right now!

@foreach($domains as $domain)
- {{ $domain }}
@endforeach
@endcomponent
