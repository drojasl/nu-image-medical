<x-mail::message>
# New Client Registered

## You have a new client registered in your application.
> {{ $client->name }} | {{ $client->email }} | {{ $client->phone }}

<x-mail::button :url="'https://www.linkedin.com/in/diego-rojas-07/'" color="success">
Hire me!
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
