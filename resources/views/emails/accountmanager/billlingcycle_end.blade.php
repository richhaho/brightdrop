@component('mail::message')
# Worker timesheets are due for {{$client->client_name}}.
<br> <br> 
{{ config('app.company_name') }}
@endcomponent
