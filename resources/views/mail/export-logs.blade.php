<x-mail::message>
# Export des logs

Veuillez trouver ci-dessous tous les 200 derniers logs enregistrés dans le système.

<x-mail::table>
| Crée le       | Message       | Statut       |
| ------------- | ------------ | ------------ |
@foreach($logs as $log)
| {{$log->created_at->format('d/m/Y H:i:s')}}      | {{$log->message}}           | {{$log->status->value === 'success' ? '✅' : '❌'}}           |
@endforeach
</x-mail::table>

<x-mail::button :url="route('filament.dashboard.resources.logs.index')">
Voir les logs
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
