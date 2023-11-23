@extends('template.template')
@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.holiday_edit{
    border: none;text-align: center;
} 
.panel-body{padding: 0px 15px 0px 15px !important;}
th {
  background: white;
  position: sticky;
  top: 0;
  box-shadow: 0 1px 1px -1px rgba(0, 0, 0, 0.2);
}
@media print {
    .unprint{display: none;}
    .print_holiday{display: block !important;}
    
}
</style>
@endsection

@section('content')

{!! Form::open(['route' => 'account.client.store','autocomplete' => 'off']) !!}
    <section id="CreateClient">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 unprint">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12" >
                        <h3 class="bold">Create New Client</h3>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
                        <button type="submit" class="btn btn-success pull right"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12 ">
                <div class="col-xs-12 unprint">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1"><i class="fa fa-minus-square"></i> Client Status</a></div>
                        <div class="panel-body panel-collapse collapse in" id="collapse1"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Client Status:</label>
                                    {!!  Form::select('status',$status,old("status"), ['class' => 'form-control changeStatus' ]) !!}
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 unprint">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2"><i class="fa fa-plus-square"></i> General Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse2"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Client Name:</label>
                                    <input name="client_name" class="form-control " type="text" value="" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Main Phone Line:</label>
                                    <input name="phone" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Web Site:</label>
                                    <input name="website" class="form-control " type="text" value="">
                                </div>
                                
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 unprint">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="true" aria-controls="collapse3"><i class="fa fa-plus-square"></i> Mailing Address</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse3"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Country:</label>
                                    {!!  Form::select('country',$country,old("country"), ['class' => 'form-control changeCountry','onchange'=>'changeCountry()']) !!}
                                </div>
                            </div>
                            <div class="row changeCountryOther" style="display: none">
                                <div class="col-md-6 form-group">
                                    <label>Country - Other:</label>
                                    <input name="country_other" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label> Full Address - Foreign:</label>
                                    <input name="address_foreign" class="form-control " type="text" value="">
                                </div>
                            </div>
                            <div class="row changeCountryUS">
                                <div class="col-md-6 form-group">
                                    <label>Address Line 1:</label>
                                    <input name="address1" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Address Line 2:</label>
                                    <input name="address2" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>City:</label>
                                    <input name="city" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>State:</label>
                                    <input name="state" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Zip Code:</label>
                                    <input name="zip" class="form-control " type="text" value="">
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 unprint">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="true" aria-controls="collapse4"><i class="fa fa-plus-square"></i> Internal Assignments</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse4"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Business Development:</label>
                                    {!!  Form::select('business_development',$business_development,old("business_development"), ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-6 form-group" >
                                    <label>Account Manager:</label>
                                    {!!  Form::select('account_managers_id',$account_manager,old("account_managers_id"), ['class' => 'form-control ']) !!}
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 unprint">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="true" aria-controls="collapse5"><i class="fa fa-plus-square"></i> Other Company Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse5"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Industry:</label>
                                    {!!  Form::select('industry',$industry,old("industry"), ['class' => 'form-control changeIndustry','onchange'=>'changeIndustry()']) !!}
                                     
                                </div>
                                <div class="col-md-6 form-group Industry" style="display:none">
                                    <label>Industry - Other:</label>
                                    <input name="industry_other" class="form-control " type="text" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Worker Job Functions:</label>
                                </div>
                                <div class="col-md-12 form-group">
                                    <?php $n=0;?>
                                    @foreach($job_function as $value)
                                    <?php $n++;?>
                                    <div class="col-md-3 col-sm-4">    
                                        <input type="checkbox" name="job_functions[{{$n}}]" value="{{$value}}">{{$value}}
                                    </div>
                                    @endforeach
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group WorkerJobFunctions" style="display:none">
                                    <p></p>
                                    <label>Worker Job Functions - Other:</label>
                                    <input name="job_function_other" class="form-control " type="text" value="">
                                </div>
                            </div>

                           
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Lead Generated By:</label>
                                    {!!  Form::select('lead_generated_by',$lead_generated_by,old("lead_generated_by"), ['class' => 'form-control changeLeadGenerated','onchange'=>'changeLeadGenerated()']) !!}
                                    
                                </div>
                                <div class="col-md-6 form-group LeadGenerated1">
                                    <label> Direct Contact - Business Development:</label>
                                    {!!  Form::select('direct_contact_business_accountmanager',$direct_contact_business_accountmanager,old("direct_contact_business_accountmanager"), ['class' => 'form-control ' ]) !!}
                                </div>
                                <div class="col-md-6 form-group LeadGenerated2" style="display:none">
                                    <label> Direct Contact - Other Internal:</label>
                                    {!!  Form::select('direct_contact_internal_payroll_admin',$direct_contact_internal_payroll_admin,old("direct_contact_internal_payroll_admin"), ['class' => 'form-control ' ]) !!}
                                </div>

                                <div class="col-md-6 form-group LeadGenerated5" style="display:none">
                                    <label>Marketing Program - Other:</label>
                                    <input name="marketing_program_other" class="form-control " type="text" value=""> 
                                </div>
                                <div class="col-md-6 form-group LeadGenerated7" style="display:none">
                                    <label> Client Referral:</label>
                                    {!!  Form::select('client_referral',$client_referral,old("client_referral"), ['class' => 'form-control ' ]) !!}
                                </div>
                                <div class="col-md-6 form-group LeadGenerated8" style="display:none">
                                    <label> Other:</label>
                                    <input name="lead_generated_other" class="form-control " type="text" value=""> 
                                </div>
                            </div><br>
                            
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 unprint">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="true" aria-controls="collapse6"><i class="fa fa-plus-square"></i> Billing/Payroll Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse6"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Billing Cycle - Type:</label>
                                    {!!  Form::select('billing_cycle_type',['bi-weekly'=>'Bi-Weekly', 'semi-monthly'=>'Semi-Monthly'],old("billing_cycle_type"), ['class' => 'form-control billing_cycle_type','onchange'=>'changeBillingCycleType()']) !!}
                                </div>
                                <div class="col-md-6 form-group billing_cycle_next_end_date">
                                    <label>Billing Cycle - Next End Date:</label>
                                    <input name="billing_cycle_next_end_date" class="form-control " type="date" value="{{$billing_cycle_next_end_date}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Overtime Pay Provided:</label>
                                    {!!  Form::select('overtime_pay_provided',$overtime_pay_provided,old("overtime_pay_provided"), ['class' => 'form-control changeOvertimePay','onchange'=>'changeOvertimePay()']) !!}
                                </div>
                                <div class="col-md-6 form-group OvertimePay">
                                    <label>Overtime - percent after 80 hours:</label>
                                    <input name="overtime_percent" class="form-control " type="number" min="0">
                                </div>
                                <div class="col-md-6 form-group OvertimePay">
                                    <label>Include PTO Hours in overtime calculation on invoice:</label>
                                    {!!  Form::select('include_PTO_in_overtime_invoice',$overtime_pay_provided,old("include_PTO_in_overtime_invoice"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group OvertimePay">
                                    <label>Include Paid Holiday Hours in overtime calculation on invoice:</label>
                                    {!!  Form::select('include_PH_in_overtime_invoice',$overtime_pay_provided,old("include_PH_in_overtime_invoice"), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row" style="display: none">
                                <div class="col-md-6 form-group">
                                    <label>Lunch Time Billable:</label>
                                    {!!  Form::select('lunchtime_billable',$lunchtime_billable,old("lunchtime_billable"), ['class' => 'form-control changeLunchtimebillable','onchange'=>'changeLunchtimebillable()']) !!}
                                    
                                </div>
                                <div class="col-md-6 form-group Lunchtimebillable">
                                    <label>Lunch Time - Maximum Billable Minutes Per Day:</label>
                                    <input name="lunchtime_billable_max_minutes" class="form-control " type="number" min="0">
                                </div>
                            </div>
                            <div class="row" style="display: none">
                                <div class="col-md-6 form-group">
                                    <label>Break Time Billable:</label>
                                    {!!  Form::select('breaktime_billable',$breaktime_billable,old("breaktime_billable"), ['class' => 'form-control changeBreaktimebillable','onchange'=>'changeBreaktimebillable()']) !!}
                                     
                                </div>
                                <div class="col-md-6 form-group Breaktimebillable">
                                    <label>Break Time - Maximum Billable Minutes Per Day:</label>
                                    <input name="breaktime_billable_max_minutes" class="form-control " type="number" min="0">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Invoice Method:</label>
                                    {!!  Form::select('invoice_method',$invoice_method,old("invoice_method"), ['class' => 'form-control ' ]) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>ACH Discount Participation:</label>
                                    {!!  Form::select('ACH_discount_participation',$ACH_discount_participation,old("ACH_discount_participation"), ['class' => 'form-control ' ]) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Payment Method:</label>
                                    {!!  Form::select('payment_method',$payment_method,old("payment_method"), ['class' => 'form-control changePaymentMethod','onchange'=>'changePaymentMethod()']) !!}
                                </div>
                                <div class="col-md-6 form-group PaymentMethod" style="display:none">
                                    <label>Internal Processor:</label>
                                    {!!  Form::select('internal_processor',$internal_processor,old("internal_processor"), ['class' => 'form-control ' ]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Review Time:</label>
                                    {!!  Form::select('review_time',$review_time,old("review_time"), ['class' => 'form-control ' ]) !!}
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 unprint">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse7" aria-expanded="true" aria-controls="collapse7"><i class="fa fa-plus-square"></i> PTO Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse7"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Client's workers receive PTO:</label>
                                    {!!  Form::select('pto_infomation',$pto_infomation,old("pto_infomation"), ['class' => 'form-control changeClientPTO','onchange'=>'changeClientPTO()']) !!}
                                     
                                </div>
                                <div class="col-md-6 form-group ClientPTO">
                                    <label>Who pays for PTO?:</label>
                                    {!!  Form::select('who_pays_pto',$who_pays_pto,old("who_pays_pto"), ['class' => 'form-control ' ]) !!}
                                </div>
                                <div class="col-md-6 form-group ClientPTO">
                                    <label>Default PTO Days - Full Calendar Year:</label>
                                    {!!  Form::select('default_pto_days',$default_pto_days,'10', ['class' => 'form-control ' ]) !!}
                                </div>
                            </div><br>
                            
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 print_holiday">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse8" aria-expanded="true" aria-controls="collapse8"><i class="fa fa-plus-square"></i> Paid Holidays</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse8"><br>
                            <div class="row unprint">
                                <div class="col-md-6 form-group">
                                    <label>Paid Holidays Offered?</label>
                                    {!!  Form::select('holiday_shedule_offered',$holiday_shedule_offered,old("holiday_shedule_offered"), ['class' => 'form-control changeHolidaySchedule','onchange'=>'changeHolidaySchedule()']) !!}
                                </div>
                                <div class="col-md-6 form-group HolidaySchedule">
                                    <label>Who pays for holidays?</label>
                                    {!!  Form::select('who_pays_holiday',$who_pays_holiday,old("who_pays_holiday"), ['class' => 'form-control ' ]) !!}
                                </div>
                            </div>
                            <div class="row holiday_schedule ">
                                <div class="box-body table_group">
                                     
                                    <table id="detail_table" class="table text-center table-bordered">
                                    <thead>
                                    <tr class="default">
                                        <th width="10%">Years</th>
                                        <th width="90%">Holiday List</th>
                                    </tr>
                                    </thead>
                                    <tbody class="">
                                    @foreach($holiday_defaults as $holiday_default)
                                    <tr class="year-{{$holiday_default['year']}}">
                                        <td>{{$holiday_default['year']}} - Observed</td>
                                        <td>
                                            <table class="table text-center ">
                                            <thead>
                                            <tr class="warning">
                                                <th width="60%"></th>
                                                <th width="30%"></th>
                                                <th width="10%"></th>
                                            </tr>
                                            <tbody class="tbody-year-holiday">
                                                @foreach($holiday_default['holidays'] as $holiday)
                                                <tr>
                                                    <td><input type="text" name="holiday_name[{{$holiday->holiday_date}}]" class="form-control holiday_edit" value="{{$holiday->holiday_name}}">
                                                    </td>
                                                    <td><input type="date" name="holiday_date[{{$holiday->holiday_date}}]" class="form-control holiday_edit" value="{{$holiday->holiday_date}}"></td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-xs btn-delete-holiday unprint" onclick="holiday_delete_list(this)"><i class="fa fa-close"></i></button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12 unprint">
                                        <div class="col-md-8 col-xs-6"><input type="text" class="form-control add_holiday_name"></div>
                                        <div class="col-md-4 col-xs-6"><input type="date" class="form-control add_holiday_date"></div>
                                              
                                        <div class="col-md-12 col-xs-12">
                                            <div><p></p></div> 
                                            &nbsp;<button type="button" class="btn btn-warning btn-print-holiday pull-right"><i class="fa fa-print "></i> Print to PDF</button><span class="pull-right">&nbsp;&nbsp;</span>
                                            <button type="button" class="btn btn-success btn-add-holiday pull-right"><i class="fa fa-plus"></i> Add Holiday</button> 
                                        </div>
                                    
                                </div>

                            </div><br>
                            
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 unprint">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse9" aria-expanded="true" aria-controls="collapse9"><i class="fa fa-plus-square"></i> Contact List</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse9"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Assign Contact:</label>
                                     
                                    {!!  Form::select('contacts_list',$contacts_list,old("contacts_list"), ['class' => 'form-control contacts_list' ]) !!}
                                    <p class="error_contact_assign" style="color: red;display: none;">This contact already had been assigned.</p>
                                    <p class="error_contact_assign_other" style="color: red;display: none;">This contact already had been assigned to other client.</p>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label> </label>
                                    <button type="button" class="btn btn-success form-control btn-add-contact">Add Contact</button>
                                </div>
                            </div>
                            <div class="row scroll" style="overflow-x: scroll;">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Email Address</th>
                                         
                                        <th>Receives CopyOfInvoice</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="contact_list_table">
                                    
                                    </tbody>
                                    </table>
                                </div>           
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12 unprint">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse10" aria-expanded="true" aria-controls="collapse10"><i class="fa fa-plus-square"></i> Worker List</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse10"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Assign Worker:</label>
                                    {!!  Form::select('workers_list',$workers_list,old("workers_list"), ['class' => 'form-control workers_list' ]) !!}
                                    <p class="error_worker_assign" style="color: red;display: none;">This worker already had been assigned.</p>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label> </label>
                                    <button type="button" class="btn btn-success form-control addWorker">Add Worker</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="box-body table_group scroll" style="overflow: scroll; height: 500px; padding:0px">
                                    <table id="detail_table" class="table text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Email Address</th>
                                        <th>TargetHours Per Week</th>
                                        <th>Client -&nbsp;&nbsp;Billable&nbsp;&nbsp;Rate&nbsp;&nbsp;- Regular</th>
                                        <th>Client-Billable&nbspRate-Overtime</th>
                                        <th>Client-Billable Rate-Currency Type</th>
                                        <th>Worker-Hourly&nbsp;Pay&nbsp;Rate-Regular</th>
                                        <th>Worker-Hourly&nbsp;Pay&nbsp;Rate-Overtime</th>
                                        <th>Worker-Hourly Pay Rate-Currency Type</th>
                                        <th> PTO Days - Full CalendarYear</th>
                                        <th>PTO Days - First CalendarYear</th>

                                        <th>Worker – PTO&nbsp;Hourly&nbsp;Rate</th>
                                        <th>Worker – PTO – Currency Type</th>
                                        <th>Worker – Holiday&nbsp;Hourly&nbsp;Rate</th>
                                        <th>Worker – Holiday – Currency Type</th>

                                        <th>Status</th>

                                    </tr>
                                    </thead>
                                    <tbody class="worker_list_table">
                                    
                                    </tbody>
                                    </table>
                                </div>           
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    </section>
{!! Form::close() !!}
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    //$('#detail_table').DataTable({"order": [1, 'desc']});
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });

    var anchor = window.location.hash;
    if (anchor.length >0 ) {
        $(".collapse").collapse('hide');
        $(anchor).collapse('show'); 
    }
    $('.collapse').on('shown.bs.collapse', function(){
        $(this).parent().find("i.fa-plus-square").removeClass("fa-plus-square").addClass("fa-minus-square");
    }).on('hidden.bs.collapse', function(){
        $(this).parent().find(".fa-minus-square").removeClass("fa-minus-square").addClass("fa-plus-square");
    });
});
function changeBillingCycleType() {
    if ($('.billing_cycle_type').val()=='bi-weekly'){
        $('.billing_cycle_next_end_date').removeClass('hidden');
    }else{
        $('.billing_cycle_next_end_date').addClass('hidden');
    }
}
function changeIndustry(){
    if ($('.changeIndustry').val()=='Other'){
        $('.Industry').css('display','block');
    }else{
        $('.Industry').css('display','none');
    }
}
function changeWorkerJobFunctions(){
    if ($('.changeWorkerJobFunctions').val()=='Other'){
        $('.WorkerJobFunctions').css('display','block');
    }else{
        $('.WorkerJobFunctions').css('display','none');
    }
}

function changeCountry(){
    if ($('.changeCountry').val()=='US'){
        $('.changeCountryUS').css('display','block');
        $('.changeCountryOther').css('display','none');
    }else{
        $('.changeCountryUS').css('display','none');
        $('.changeCountryOther').css('display','block');
    }
}
function changeLeadGenerated(){
    $('.LeadGenerated1').css('display','none');
    $('.LeadGenerated2').css('display','none');
    $('.LeadGenerated5').css('display','none');
    $('.LeadGenerated7').css('display','none');
    $('.LeadGenerated8').css('display','none');

    if ($('.changeLeadGenerated').val()=='Direct Contact - Business Development'){
        $('.LeadGenerated1').css('display','block');
    }
    if ($('.changeLeadGenerated').val()=='Direct Contact - Other Internal'){
        $('.LeadGenerated2').css('display','block');
    }
    if ($('.changeLeadGenerated').val()=='Marketing Program - Other'){
        $('.LeadGenerated5').css('display','block');
    }
    if ($('.changeLeadGenerated').val()=='Client Referral'){
        $('.LeadGenerated7').css('display','block');
    }
    if ($('.changeLeadGenerated').val()=='Other'){
        $('.LeadGenerated8').css('display','block');
    }
}

function changeOvertimePay(){
    if ($('.changeOvertimePay').val()=='yes'){
        $('.OvertimePay').css('display','block');
    }else{
        $('.OvertimePay').css('display','none');
    }
}

function changePaymentMethod(){
    if ($('.changePaymentMethod').val()=='client_process_ach'){
        $('.PaymentMethod').css('display','none');
    }else{
        $('.PaymentMethod').css('display','block');
    }
}

function changeClientPTO(){
    if ($('.changeClientPTO').val()=='yes'){
        $('.ClientPTO').css('display','block');
    }else{
        $('.ClientPTO').css('display','none');
    }
}
function changeHolidaySchedule(){
    if ($('.changeHolidaySchedule').val()=='yes_paid'){
        $('.HolidaySchedule').css('display','block');
    }else{
        $('.HolidaySchedule').css('display','none');
    }

    if ($('.changeHolidaySchedule').val()=='no_holiday'){
        $('.holiday_schedule').css('display','none');
    }else{
        $('.holiday_schedule').css('display','block');
    }
}

function changeLunchtimebillable(){
    if ($('.changeLunchtimebillable').val()=='yes'){
        $('.Lunchtimebillable').css('display','block');
    }else{
        $('.Lunchtimebillable').css('display','none');
    }
}
function changeBreaktimebillable(){
    if ($('.changeBreaktimebillable').val()=='yes'){
        $('.Breaktimebillable').css('display','block');
    }else{
        $('.Breaktimebillable').css('display','none');
    }
}

$('.btn-add-contact').click(function(){
    var contact_id=$('.contacts_list').val();
    $('.contact_list_table tr').each(function (e) {
        if($(this).find('input[type="hidden"]').val()==contact_id){
           $('.error_contact_assign').css('display','block');contact_id=0;return;
        }
    });
    if (contact_id==0 || !$('.contacts_list').val()) return;
    $.get("{{route('account.contact.special_contact')}}",{"id":$('.contacts_list').val()}).done(function( data ) {
        
        if (data.clients_id>0){
            $('.error_contact_assign_other').css('display','block');return;
        }
        var add='<tr>'
                    +'<td><input type="hidden" name="contact_id['+data.id+']" value="'+data.id+'"> '+data.last_name+'</td>'
                    +'<td>'+data.first_name+'</td>'
                    +'<td>'+data.email+'</td>'
                    +'<td>'
                    +    '<select name="receives_copy_invoice['+data.id+']" class="form-control">'
                    +        '<option value="Yes" selected>Yes</option>'
                    +        '<option value="No">No</option>'
                    +    '</select>'
                    +'</td>'
                    +'<td><button type="button" class="btn btn-danger contact_delete_btn btn-xs" onclick="contact_delete_list(this)"> delete</button></td>'
                +'</tr>';
                
                $('.contact_list_table').append(add);

    });

});

$('.contacts_list').click(function(){
    $('.error_contact_assign').css('display','none');
    $('.error_contact_assign_other').css('display','none');
});
$('.workers_list').click(function(){
    $('.error_worker_assign').css('display','none');
});
$('.addWorker').click(function(){
var worker_id=$('.workers_list').val();
$('.worker_list_table tr').each(function (e) {
    if($(this).find('input[type="hidden"]').val()==worker_id){
       $('.error_worker_assign').css('display','block');worker_id=0;return;
    }
});
if (worker_id==0) return;

$.get("{{route('account.worker.special_worker')}}",{"id":$('.workers_list').val()}).done(function( data ) {
        
var currency=data.currency_type.toUpperCase();

var client_pto=0;
var pto_full_string='';
for (i=0;i<31;i++){
    if (i==client_pto){
        pto_full_string+='<option value="'+i+'" selected>'+i+'</option>';
    }else{
        pto_full_string+='<option value="'+i+'">'+i+'</option>';
    }
}
var pto_current_string='';
for (i=0;i<31;i++){
    pto_current_string+='<option value="'+i+'">'+i+'</option>';
}
var add='<tr>'+
        '<td><input type="hidden" name="worker_id['+data.id+']" value="'+data.id+'">'+data.last_name+'</td>'+
        '<td>'+data.first_name+'</td>'+
        '<td>'+data.email_main+'</td>'+
        '<td>'+
            '<select name="target_hours_week['+data.id+']" class="form-control">'+
               ' <option value="10">10</option>'+
               ' <option value="20">20</option>'+
               ' <option value="30">30</option>'+
               ' <option value="40">40</option>'+
            '</select>'+
        '</td>'+
        '<td><input type="number" step=0.01 min=0  name="client_billable_rate_regular['+data.id+']" onchange="calc_overtime_rate(this)" class="form-control client_billable_rate_regular" required></td>'+
        '<td><input type="text" name="client_billable_rate_overtime['+data.id+']"  class="form-control client_billable_rate_overtime" required readonly></td>'+
        '<td>'+
            '<input class="form-control" value="USD" readonly>'+
        '</td>'+
        '<td><input type="number" step=0.01 min=0  name="worker_pay_houly_rate_regular['+data.id+']"  onchange="calc_overtime_rate(this)" class="form-control worker_pay_houly_rate_regular" required></td>'+
        '<td><input type="text" name="worker_pay_houly_rate_overtime['+data.id+']"  class="form-control worker_pay_houly_rate_overtime" required readonly></td>'+
        '<td>'+
            '<input name="currency_type['+data.id+']" class="form-control" value="'+currency+'" readonly>'+
        '</td>'+
        '<td>'+
        '<select name="ptodays_full_calendar['+data.id+']" class="form-control">'+
               pto_full_string+
            '</select>'+
        '</td>'+
        '<td>'+
        '<select name="ptodays_current_calendar['+data.id+']" class="form-control">'+
               pto_current_string+
            '</select>'+
        '</td>'+


        '<td><input type="number" step=0.01 min=0 name="worker_pto_hourly_rate['+data.id+']"  class="form-control worker_pto_hourly_rate" required onchange="roundRate(this)"></td>'+
        '<td>'+
            '<input class="form-control" value="'+currency+'" readonly>'+
        '</td>'+

        '<td><input type="number" step=0.01 min=0 name="worker_holiday_hourly_rate['+data.id+']"  class="form-control worker_holiday_hourly_rate" required onchange="roundRate(this)"></td>'+
        '<td>'+
            '<input class="form-control" value="'+currency+'" readonly>'+
        '</td>'+

        '<td><button type="button" class="btn btn-danger worker_delete_btn btn-xs" onclick="worker_delete_list(this)"> delete</button></td>'+
        '</tr>';
        $('.worker_list_table').append(add);

    });
});

function worker_delete_list(e){
     
    $(e).parent().parent().remove();
}
function holiday_delete_list(e){
     
    $(e).parent().parent().remove();
}
function contact_delete_list(e){
     
    $(e).parent().parent().remove();
}




$('.btn-add-holiday').click(function(){
    var holiday_name=$('.add_holiday_name').val();
    var holiday_date=$('.add_holiday_date').val();
    if(!holiday_date || !holiday_name) return;
    var parent_tr='.year-'+holiday_date.substr(0,4);
    var add_element=''
    +'<tr>'
    +    '<td><input type="text" name="holiday_name['+holiday_date+']" class="form-control holiday_edit" value="'+holiday_name+'"></td>'
    +    '<td><input type="date" name="holiday_date['+holiday_date+']" class="form-control holiday_edit" value="'+holiday_date+'"></td>'
    +    '<td><button type="button" class="btn btn-danger btn-xs btn-delete-holiday" onclick="holiday_delete_list(this)"><i class="fa fa-close"></i></button></td>'
    +'</tr>';
    $(parent_tr+' .tbody-year-holiday').append(add_element);
});
$('.btn-print-holiday').click(function(){
    $('#print_holiday').show();
    window.print();
});

$('input[type="checkbox"]').click(function(){
    if($(this).val()=="Other"){
        if($(this).is(':checked')){
            $('.WorkerJobFunctions').css('display','block');
        }else{
            $('.WorkerJobFunctions').css('display','none');
        }
    }
});


var percent=1;
function calc_overtime_rate(e){
    roundRate(e);
    if ($('select[name="overtime_pay_provided"]').val()=='yes') {percent+=(parseInt($('input[name="overtime_percent"]').val()) || 0) /100;}
    
    var client_billable_rate_regular=Math.round((parseFloat($(e).parent().parent().find('.client_billable_rate_regular').val())||0) * percent*100)/100;
    var worker_pay_houly_rate_regular=Math.round((parseFloat($(e).parent().parent().find('.worker_pay_houly_rate_regular').val())||0) * percent*100)/100;

    $(e).parent().parent().find('.client_billable_rate_overtime').val(client_billable_rate_regular);
    $(e).parent().parent().find('.worker_pay_houly_rate_overtime').val(worker_pay_houly_rate_regular);

    // $(e).parent().parent().find('.worker_pto_hourly_rate').val($('.changeClientPTO').val()=='yes' ? $(e).parent().parent().find('.worker_pay_houly_rate_regular').val():0);
    // $(e).parent().parent().find('.worker_holiday_hourly_rate').val($('.changeHolidaySchedule').val()=='yes_paid' ? $(e).parent().parent().find('.worker_pay_houly_rate_regular').val():0);
}

$('input[type="number"]').change(function(){
    roundRate(this);
});
function roundRate(e){
    if($(e).attr('step')=='0.01'){
        let round_val=Math.round((parseFloat($(e).val()) || 0)*100)/100;
        $(e).val(round_val);
    } 
}
</script>
<script type="text/javascript">
    $('button[type="submit"]').click(function(){
        if (!$('input[name="client_name"]').val()){
            $("#collapse2").collapse('show');
        }
    });
</script>
@endsection
