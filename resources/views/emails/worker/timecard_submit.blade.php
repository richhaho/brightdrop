@component('mail::message')
# Your hours have been submitted.
<br>
Thank you for submitting your hours.  They are now being reviewed by your Account Manager.  You will be notified again when your hours have been approved.  For your records, please find an attachment with the hours you submitted.
<br><br>
Thank you,<br>
{{ config('app.company_name') }}
@endcomponent
