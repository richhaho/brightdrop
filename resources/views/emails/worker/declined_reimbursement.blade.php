@component('mail::message')
# Your reimbursement request was {{$status}}.
<br>
Client: {{$reimbursement->client()->client_name}}<br>
<br>
Date:   {{date('D, m/d/Y',strtotime($reimbursement->date))}}<br>
Type:   {{$reimbursement->type=='Other' ? $reimbursement->other_type:$reimbursement->type}}<br>
Amount: {{$reimbursement->amount}} {{strtoupper($reimbursement->currency_type)}}<br>
<br>
Thank you,<br>
{{ config('app.company_name') }}
@endcomponent
