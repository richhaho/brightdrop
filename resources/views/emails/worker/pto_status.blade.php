@component('mail::message')
# PTO Approval Status
<br>
Your PTO request for {{$pto->total_hours}} hour(s) on {{date('m/d/Y',strtotime($pto->date_pto))}} has been {{$status}}.

<br> <br> 
Thank you,<br>
{{ config('app.name') }}
@endcomponent
