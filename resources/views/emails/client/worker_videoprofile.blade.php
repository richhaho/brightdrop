@component('mail::message')
<br>
Please click the link below to view {{$worker->fullname}}’s video profile.
<br> <br> 
<a href="{{$link}}">{{$link}}</a>
<br> <br> 
Thank you,<br>
{{ config('app.company_name') }}
@endcomponent
