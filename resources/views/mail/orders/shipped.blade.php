<x-mail::message>
# Доброго времени суток!<br>
Аккаунт <b>{{$account->login}}</b> освободился

<x-mail::button url="{{ route('accounts.edit', $account->id) }}">
Перейти к аккаунту
</x-mail::button>

Спасибо,<br>
{{ config('app.name') }}
</x-mail::message>
