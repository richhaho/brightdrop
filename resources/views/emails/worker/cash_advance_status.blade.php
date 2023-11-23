@component('mail::message')
@if ($status=='approved completely')
# Cash advance repayment status
<br>
Your cash advance repayments are complete. See repayment table for details.
@else
# Cash advance request status
<br>
Your cash advance request has been {{$status}}.  For your records, please find an attachment with your repayment table.
@endif

<br> 
Thank you,<br>
{{ config('app.company_name') }}
@endcomponent
