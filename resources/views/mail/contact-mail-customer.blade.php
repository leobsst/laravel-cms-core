<x-mail::message>
# Bonjour,

Vous avez reçu un nouveau message de <strong>{{ $name }}</strong>.<br>
<strong>{{$email}}</strong><br>
<strong>{{$phone}}</strong><br><br><br>

Au sujet de <strong>{{ $subject }}</strong>.<br><br><br>

{{ $contentMessage }}<br><br>
</x-mail::message>
