@component('mail::message')
# Your timesheet has been approved.
<br>
 
<p></p>
<p>Client: {{$timecard->client()->client_name}}</p>
Start Date: {{date('l, m/d/Y',strtotime($timecard->start_date))}}<br>
End Date: {{date('l, m/d/Y',strtotime($timecard->end_date))}}<br>
Total Work Hours: {{$timecard->total_work_time}} <br>
Total PTO Hours: {{$timecard->total_pto_time}}<br>
Total Holiday Hours: {{$timecard->total_holiday_time}}<br>
<br>
<br>
Thank you,<br>
{{ config('app.company_name') }}
@endcomponent
