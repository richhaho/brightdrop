@component('mail::message')
# Payment Summary
<br>
<h4>Payment Information</h4>
<p></p>
Total Amount: {{number_format($payment->amount_updated ? $payment->amount_updated : $payment->amount, 2, '.', ',')}} {{strtoupper($payment->currency_type)}} <br>
<br>
Your payment has been sent. Please review attached document for details.

<br> <br> 
Thank you,<br>
{{ config('app.company_name') }}
@endcomponent
