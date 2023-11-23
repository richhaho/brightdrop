@component('mail::message')
# Invoice Summary
<br>
<h4>Invoice Information</h4>
<p></p>
Invoice Number: {{$invoice->invoice_number}}<br>
Date in Queue: {{date('l, m/d/Y',strtotime($invoice->date_queue))}}<br>
Total Amount: {{$invoice->amount}} {{strtoupper($invoice->currency_type)}} <br>
<br>
Please review attached docment.
<br> <br> 
Thank you,<br>
{{ config('app.company_name') }}
@endcomponent
