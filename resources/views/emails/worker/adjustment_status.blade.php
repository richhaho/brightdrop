@component('mail::message')
# Payroll adjustment status
<br>
A payroll adjustment has been filed for you.  Here is a summary of the adjustment:
<br><br> 
@if($adjustment->type=='Time Adjustment')
Type: {{$adjustment->type}}<br>
Date: {{date('m/d/Y',strtotime($adjustment->adjustment_date))}}<br>
Total Hours: {{$adjustment->adjustment_total_hours}}<br>
Rate: {{$adjustment->rate}}<br>
@endif

@if($adjustment->type=='Other')
Description: {{$adjustment->other_description}}<br>
Amount: {{$adjustment->other_amount}}<br>
Currency Type: {{$adjustment->other_currency}}<br>
@endif

<br> 
Thank you,<br>
{{ config('app.company_name') }}
@endcomponent
