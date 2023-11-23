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
    .unprint{display: none}
    .print_holiday{display: block !important}
    .print_holiday td{border: none}

}
</style>
@endsection

@section('content')
{!! Form::open(['route' => 'admin.client.update','autocomplete' => 'off']) !!}
    <input type="hidden" name="client_id" value="{{$client->id}}">
    <section id="Profile">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 unprint">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                        <h3 class="bold">Client: {{$client->client_name}}'s Profile</h3>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                        <button type="submit" class="btn btn-success pull-right btn-save" disabled><i class="fa fa-save"></i> Save Profile</button>
                        <button type="button" class="btn btn-warning pull-right btn-edit"><i class="fa fa-edit"></i> Edit Profile</button>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12 main_content">
                <div class="col-xs-12 unprint">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1"><i class="fa fa-minus-square"></i> Client Status</a></div>
                        <div class="panel-body panel-collapse collapse in" id="collapse1"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Client Status:</label>
                                    {!!  Form::select('status',$status,$client->status, ['class' => 'form-control changeStatus' ]) !!}
                                </div>
                            </div>
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
                                    <input name="client_name" class="form-control " type="text" value="{{$client->client_name}}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Main Phone Line:</label>
                                    <input name="phone" class="form-control " type="text" value="{{$client->phone}}">
                                </div>
                                <div class="col-md-12 form-group" style="pointer-events: initial;opacity: 1">
                                    <label>Web Site:</label><br>
                                    <a href="{{substr($client->website,0,4)=='http' ? $client->website:'http://'.$client->website }}" class="website_link">{{$client->website}}</a>
                                    <input name="website" class="form-control website" type="text" value="{{$client->website}}" style="display: none">
                                </div>
                                
                            </div>
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
                                    {!!  Form::select('country',$country,$client->country, ['class' => 'form-control changeCountry','onchange'=>'changeCountry()']) !!}
                                </div>
                            </div>
                            <?php $styleOther = ($client->country==='Other') ? '' : 'display:none'; ?>
                            <div class="row changeCountryOther" style="{{$styleOther}}">
                                <div class="col-md-6 form-group">
                                    <label>Country - Other:</label>
                                    <input name="country_other" class="form-control " type="text" value="{{$client->country_other}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label> Full Address - Foreign:</label>
                                    <input name="address_foreign" class="form-control " type="text" value="{{$client->address_foreign}}">
                                </div>
                            </div>
                            <?php $styleUS = ($client->country==='Other') ? 'display:none':''; ?>
                            <div class="row changeCountryUS" style="{{$styleUS}}">
                                <div class="col-md-6 form-group">
                                    <label>Address Line 1:</label>
                                    <input name="address1" class="form-control " type="text" value="{{$client->address1}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Address Line 2:</label>
                                    <input name="address2" class="form-control " type="text" value="{{$client->address2}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>City:</label>
                                    <input name="city" class="form-control " type="text" value="{{$client->city}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>State:</label>
                                    <input name="state" class="form-control " type="text" value="{{$client->state}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Zip Code:</label>
                                    <input name="zip" class="form-control " type="text" value="{{$client->zip}}">
                                </div>
                            </div>
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
                                    {!!  Form::select('business_development',$business_development,$client->business_development, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-6 form-group" >
                                    <label>Account Manager:</label>
                                    {!!  Form::select('account_managers_id',$account_manager,$client->account_managers_id, ['class' => 'form-control ']) !!}
                                </div>
                            </div>
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
                                    {!!  Form::select('industry',$industry,$client->industry, ['class' => 'form-control changeIndustry','onchange'=>'changeIndustry()']) !!}
                                     
                                </div>
                                <div class="col-md-6 form-group Industry" style="display:none">
                                    <label>Industry - Other:</label>
                                    <input name="industry_other" class="form-control " type="text" value="{{$client->industry_other}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Worker Job Functions:</label>
                                </div>
                                <div class="col-md-12 form-group">
                                    <?php $n=0;$job_functions=explode(',', $client->job_function);
                                        $style_jobfunction=array_search('Other', $job_functions)===false ? 'display:none':'';

                                    ?>
                                    @foreach($job_function as $value)
                                    <?php $n++;
                                    
                                    $check=array_search($value, $job_functions)===false ? '':'checked';
                                    ?>
                                    <div class="col-md-3 col-sm-4">    
                                        <input type="checkbox" name="job_functions[{{$n}}]" value="{{$value}}" {{$check}}>{{$value}}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group WorkerJobFunctions" style="{{$style_jobfunction}}">
                                    <p></p>
                                    <label>Worker Job Functions - Other:</label>
                                    <input name="job_function_other" class="form-control " type="text" value="{{$client->job_function_other}}">
                                </div>
                            </div>

                           
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Lead Generated By:</label>
                                    {!!  Form::select('lead_generated_by',$lead_generated_by,$client->lead_generated_by, ['class' => 'form-control changeLeadGenerated','onchange'=>'changeLeadGenerated()']) !!}
                                    
                                </div>
                                <?php 
                                switch ($client->lead_generated_by) {
                                    case 'Direct Contact - Business Development':
                                        $style_direct_business='';
                                        $style_direct_other='display:none';
                                        $style_marketing_other='display:none';
                                        $style_client_referral='display:none';
                                        $style_other='display:none';
                                        break;
                                    case 'Direct Contact - Other Internal':
                                        $style_direct_business='display:none';
                                        $style_direct_other='';
                                        $style_marketing_other='display:none';
                                        $style_client_referral='display:none';
                                        $style_other='display:none';
                                        break;
                                    case 'Marketing Program - Other':
                                        $style_direct_business='display:none';
                                        $style_direct_other='display:none';
                                        $style_marketing_other='';
                                        $style_client_referral='display:none';
                                        $style_other='display:none';
                                        break;
                                    case 'Client Referral':
                                        $style_direct_business='display:none';
                                        $style_direct_other='display:none';
                                        $style_marketing_other='display:none';
                                        $style_client_referral='';
                                        $style_other='display:none';
                                        break;
                                    case 'Other':
                                        $style_direct_business='display:none';
                                        $style_direct_other='display:none';
                                        $style_marketing_other='display:none';
                                        $style_client_referral='display:none';
                                        $style_other='';
                                        break;
                                    default:
                                        $style_direct_business='display:none';
                                        $style_direct_other='display:none';
                                        $style_marketing_other='display:none';
                                        $style_client_referral='display:none';
                                        $style_other='display:none';
                                        break;
                                }
                                ?>
                                <div class="col-md-6 form-group LeadGenerated1" style="{{$style_direct_business}}">
                                    <label> Direct Contact - Business Development:</label>
                                    {!!  Form::select('direct_contact_business_accountmanager',$direct_contact_business_accountmanager,$client->direct_contact_business_accountmanager, ['class' => 'form-control ' ]) !!}
                                </div>
                                <div class="col-md-6 form-group LeadGenerated2" style="{{$style_direct_other}}">
                                    <label> Direct Contact - Other Internal:</label>
                                    {!!  Form::select('direct_contact_internal_payroll_admin',$direct_contact_internal_payroll_admin,$client->direct_contact_internal_payroll_admin, ['class' => 'form-control ' ]) !!}
                                </div>

                                <div class="col-md-6 form-group LeadGenerated5" style="{{$style_marketing_other}}">
                                    <label>Marketing Program - Other:</label>
                                    <input name="marketing_program_other" class="form-control " type="text" value="{{$client->marketing_program_other}}"> 
                                </div>
                                <div class="col-md-6 form-group LeadGenerated7" style="{{$style_client_referral}}">
                                    <label> Client Referral:</label>
                                    {!!  Form::select('client_referral',$client_referral,$client->client_referral, ['class' => 'form-control ' ]) !!}
                                </div>
                                <div class="col-md-6 form-group LeadGenerated8" style="{{$style_other}}">
                                    <label> Other:</label>
                                    <input name="lead_generated_other" class="form-control " type="text" value="{{$client->lead_generated_other}}"> 
                                </div>
                            </div>
                            
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
                                    {!!  Form::select('billing_cycle_type',['bi-weekly'=>'Bi-Weekly', 'semi-monthly'=>'Semi-Monthly'],$client->billing_cycle_type, ['class' => 'form-control billing_cycle_type','onchange'=>'changeBillingCycleType()']) !!}
                                </div>
                                <div class="col-md-6 form-group billing_cycle_next_end_date {{$client->billing_cycle_type != 'bi-weekly' ? 'hidden':''}}">
                                    <label>Billing Cycle - Next End Date:</label>
                                    <input name="billing_cycle_next_end_date" class="form-control " type="date" value="{{$client->billing_cycle_next_end_date}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Overtime Pay Provided:</label>
                                    {!!  Form::select('overtime_pay_provided',$overtime_pay_provided,$client->overtime_pay_provided, ['class' => 'form-control changeOvertimePay','onchange'=>'changeOvertimePay()']) !!}
                                </div>
                                <div class="col-md-6 form-group OvertimePay" style="{{$client->overtime_pay_provided=='no' ? 'display:none':''}}">
                                    <label>Overtime - percent after 80 hours:</label>
                                    <input name="overtime_percent" class="form-control " type="number" min="0" value="{{$client->overtime_percent}}">
                                </div>
                                <div class="col-md-6 form-group OvertimePay" style="{{$client->overtime_pay_provided=='no' ? 'display:none':''}}">
                                    <label>Include PTO Hours in overtime calculation on invoice:</label>
                                    {!!  Form::select('include_PTO_in_overtime_invoice',$overtime_pay_provided,$client->include_PTO_in_overtime_invoice, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group OvertimePay" style="{{$client->overtime_pay_provided=='no' ? 'display:none':''}}">
                                    <label>Include Paid Holiday Hours in overtime calculation on invoice:</label>
                                    {!!  Form::select('include_PH_in_overtime_invoice',$overtime_pay_provided,$client->include_PH_in_overtime_invoice, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row" style="display: none">
                                <div class="col-md-6 form-group">
                                    <label>Lunch Time Billable:</label>
                                    {!!  Form::select('lunchtime_billable',$lunchtime_billable,$client->lunchtime_billable, ['class' => 'form-control changeLunchtimebillable','onchange'=>'changeLunchtimebillable()']) !!}
                                    
                                </div>
                                <?php 
                                $style=$client->lunchtime_billable=='yes' ? '':'display:none';
                                ?>
                                <div class="col-md-6 form-group Lunchtimebillable" style="{{$style}}">
                                    <label>Lunch Time - Maximum Billable Minutes Per Day:</label>
                                    <input name="lunchtime_billable_max_minutes" class="form-control " type="number" min="0" value="{{$client->lunchtime_billable_max_minutes}}">
                                </div>
                            </div>
                            <div class="row" style="display: none">
                                <div class="col-md-6 form-group">
                                    <label>Break Time Billable:</label>
                                    {!!  Form::select('breaktime_billable',$breaktime_billable,$client->breaktime_billable, ['class' => 'form-control changeBreaktimebillable','onchange'=>'changeBreaktimebillable()']) !!}
                                     
                                </div>
                                <?php 
                                $style=$client->breaktime_billable=='yes' ? '':'display:none';
                                ?>
                                <div class="col-md-6 form-group Breaktimebillable" style="{{$style}}">
                                    <label>Break Time - Maximum Billable Minutes Per Day:</label>
                                    <input name="breaktime_billable_max_minutes" class="form-control " type="number" min="0" value="{{$client->breaktime_billable_max_minutes}}">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Invoice Method:</label>
                                    {!!  Form::select('invoice_method',$invoice_method,$client->invoice_method, ['class' => 'form-control ' ]) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>ACH Discount Participation:</label>
                                    {!!  Form::select('ACH_discount_participation',$ACH_discount_participation,$client->ACH_discount_participation, ['class' => 'form-control ' ]) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Payment Method:</label>
                                    {!!  Form::select('payment_method',$payment_method,$client->payment_method, ['class' => 'form-control changePaymentMethod','onchange'=>'changePaymentMethod()']) !!}
                                </div>
                                <?php 
                                $style=$client->payment_method=='client_process_ach' ? 'display:none' : '';
                                ?>
                                <div class="col-md-6 form-group PaymentMethod" style="{{$style}}">
                                    <label>Internal Processor:</label>
                                    {!!  Form::select('internal_processor',$internal_processor,$client->internal_processor, ['class' => 'form-control ' ]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Review Time:</label>
                                    {!!  Form::select('review_time',$review_time,$client->review_time, ['class' => 'form-control ' ]) !!}
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
                                    {!!  Form::select('pto_infomation',$pto_infomation,$client->pto_infomation, ['class' => 'form-control changeClientPTO','onchange'=>'changeClientPTO()']) !!}
                                     
                                </div>
                                <?php 
                                $style=$client->pto_infomation=='yes' ? '':'display:none';
                                ?>
                                <div class="col-md-6 form-group ClientPTO" style="{{$style}}">
                                    <label>Who pays for PTO?:</label>
                                    {!!  Form::select('who_pays_pto',$who_pays_pto,$client->who_pays_pto, ['class' => 'form-control ' ]) !!}
                                </div>
                                <div class="col-md-6 form-group ClientPTO" style="{{$style}}">
                                    <label>Default PTO Days - Full Calendar Year:</label>
                                    {!!  Form::select('default_pto_days',$default_pto_days,$client->default_pto_days, ['class' => 'form-control ' ]) !!}
                                </div>
                            </div>
                            
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
                                    {!!  Form::select('holiday_shedule_offered',$holiday_shedule_offered,$client->holiday_shedule_offered, ['class' => 'form-control changeHolidaySchedule','onchange'=>'changeHolidaySchedule()']) !!}
                                </div>
                                <?php 
                                $style=$client->holiday_shedule_offered=='no_holiday' ? 'display:none;':'';
                                $style2=$client->holiday_shedule_offered=='yes_unpaid' ? 'display:none;':'';
                                ?>
                                <div class="col-md-6 form-group HolidaySchedule" style="{{$style}}{{$style2}}">
                                    <label>Who pays for holidays?</label>
                                    {!!  Form::select('who_pays_holiday',$who_pays_holiday,$client->who_pays_holiday, ['class' => 'form-control ' ]) !!}
                                </div>
                            </div>
                            <div class="row holiday_schedule" style="{{$style}}">
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

                            </div>
                            
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
                                        <!-- <th>Timesheets-Able To Approve</th>
                                        <th>Timesheets-View Only </th> -->
                                        <th>Receives CopyOfInvoice</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="contact_list_table">
                                    @foreach ($contacts as $contact)    
                                    <tr>
                                        <td><input type="hidden" name="contact_id[{{$contact->id}}]"  value="{{$contact->id}}">{{$contact->last_name}}</td>
                                        <td>{{$contact->first_name}}</td>
                                        <td>{{$contact->email}}</td>
                                        <!-- <td>
                                            {!!  Form::select('timesheet_able_to_approve['.$contact->id.']',$yesno,$contact->timesheet_able_to_approve, ['class' => 'form-control contacts_list' ]) !!}
                                        </td>
                                        <td> {!!  Form::select('timesheet_view_only['.$contact->id.']',$yesno,$contact->timesheet_view_only, ['class' => 'form-control contacts_list' ]) !!}
                                        </td> -->
                                        <td> {!!  Form::select('receives_copy_invoice['.$contact->id.']',$yesno,$contact->receives_copy_invoice, ['class' => 'form-control contacts_list' ]) !!}
                                        </td>
                                        <td><button type="button" class="btn btn-danger contact_delete_btn btn-xs" onclick="contact_delete_list(this)"> delete</button></td> 
                                    </tr>
                                    @endforeach 
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
                                        <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>

                                    </tr>
                                    </thead>
                                    <tbody class="worker_list_table">
                                    @foreach ($workers as $worker)
                                        <?php
                                        $clientinfo=\App\ClientInfoWorkers::where('clients_id',$client->id)->
                                            where('workers_id',$worker->id)->first();

                                        ?>
                                        <tr>
                                        <td><input type="hidden" name="worker_id[{{$worker->id}}]"  value="{{$worker->id}}">{{$worker->last_name}}</td>
                                        <td>{{$worker->first_name}}</td>
                                        <td>{{$worker->email_main}}</td>
                                        <td>
                                            {!!  Form::select('target_hours_week['.$worker->id.']',$target_hours,isset($clientinfo->target_hours_week) ? $clientinfo->target_hours_week:"10", ['class' => 'form-control' ]) !!}
                                        </td>
                                        <td><input type="number" step=0.01 min=0 name="client_billable_rate_regular[{{$worker->id}}]" value="{{isset($clientinfo->client_billable_rate_regular) ? $clientinfo->client_billable_rate_regular:''}}" onchange="calc_overtime_rate(this)" class="form-control client_billable_rate_regular" required ></td>
                                        <td><input type="text" name="client_billable_rate_overtime[{{$worker->id}}]"  value="{{isset($clientinfo->client_billable_rate_overtime) ? $clientinfo->client_billable_rate_overtime:''}}" class="form-control client_billable_rate_overtime" required readonly></td>
                                        <td><input type="text" value="USD" class="form-control" readonly></td>
                                        <td><input type="number" step=0.01 min=0 name="worker_pay_houly_rate_regular[{{$worker->id}}]"  value="{{isset($clientinfo->worker_pay_houly_rate_regular) ? $clientinfo->worker_pay_houly_rate_regular:''}}" onchange="calc_overtime_rate(this)" class="form-control worker_pay_houly_rate_regular" required></td>
                                        <td><input type="text" name="worker_pay_houly_rate_overtime[{{$worker->id}}]"  value="{{isset($clientinfo->worker_pay_houly_rate_overtime) ? $clientinfo->worker_pay_houly_rate_overtime:''}}" class="form-control worker_pay_houly_rate_overtime" required readonly></td>
                                        <td>
                                             <input name="currency_type[{{$worker->id}}]" type="text" value="{{strtoupper($worker->currency_type)}}" class="form-control" readonly> 
                                        </td>
                                        <td>
                                            {!!  Form::select('ptodays_full_calendar['.$worker->id.']',$pto_days,isset($clientinfo->ptodays_full_calendar) ? $clientinfo->ptodays_full_calendar:$client->default_pto_days, ['class' => 'form-control' ]) !!}
                                        </td>
                                        <td>
                                            {!!  Form::select('ptodays_current_calendar['.$worker->id.']',$pto_days,isset($clientinfo->ptodays_current_calendar) ? $clientinfo->ptodays_current_calendar:"0", ['class' => 'form-control' ]) !!}
                                        </td>
                                        <td><input type="number" step=0.01 min=0 name="worker_pto_hourly_rate[{{$worker->id}}]"  value="{{$clientinfo->worker_pto_hourly_rate!==null ? $clientinfo->worker_pto_hourly_rate :$clientinfo->worker_pay_houly_rate_regular}}" class="form-control worker_pto_hourly_rate" required></td>
                                        <td>
                                             <input type="text" value="{{strtoupper($worker->currency_type)}}" class="form-control" readonly> 
                                        </td>
                                        <td><input type="number" step=0.01 min=0 name="worker_holiday_hourly_rate[{{$worker->id}}]"  value="{{$clientinfo->worker_holiday_hourly_rate}}" class="form-control worker_holiday_hourly_rate" required></td>
                                        <td>
                                             <input type="text" value="{{strtoupper($worker->currency_type)}}" class="form-control" readonly> 
                                        </td> 

                                        <td>
                                            @if (count($worker->timecards->where('clients_id',$client->id))==0)
                                            <button type="button" class="btn btn-danger worker_delete_btn btn-xs" onclick="worker_delete_list(this)"> delete</button>
                                            @else
                                            <?php
                                                $statuses=[
                                                    'active' => 'Active',
                                                    'inactive' => 'Inactive'
                                                ];
                                            ?>
                                                {!!  Form::select('action_status['.$worker->id.']',$statuses,$clientinfo->status, ['class' => 'form-control' ]) !!}
                                            @endif
                                        </td> 
                                    </tr>
                                    @endforeach
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
    $.get("{{route('admin.contact.special_contact')}}",{"id":$('.contacts_list').val()}).done(function( data ) {
        var client_id='{{$client->id}}';         
        if (data.clients_id>0 && data.clients_id!=parseInt(client_id)){
            $('.error_contact_assign_other').css('display','block');return;
        }
        var add='<tr>'
                    +'<td><input type="hidden" name="contact_id['+data.id+']" value="'+data.id+'"> '+data.last_name+'</td>'
                    +'<td>'+data.first_name+'</td>'
                    +'<td>'+data.email+'</td>'
                    // +'<td>'
                    // +    '<select name="timesheet_able_to_approve['+data.id+']" class="form-control">'
                    // +        '<option value="Yes" selected>Yes</option>'
                    // +        '<option value="No">No</option>'
                    // +    '</select>'
                    // +'</td>'
                    // +'<td>'
                    // +   '<select name="timesheet_view_only['+data.id+']" class="form-control">'
                    // +        '<option value="Yes" selected>Yes</option>'
                    // +        '<option value="No">No</option>'
                    // +    '</select>'
                    // +'</td>'
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

$.get("{{route('admin.worker.special_worker')}}",{"id":$('.workers_list').val()}).done(function( data ) {
        
var currency=data.currency_type.toUpperCase();

var client_pto=$("select[name='pto_infomation']").val()=='yes' ? parseInt($("select[name='default_pto_days']").val())||0 :0;
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
    $('.add_holiday_name').val('');
    $('.add_holiday_date').val('');
});

$('.panel-body').css('pointer-events','none');
$('.panel-body').css('opacity','0.8');

$('.scroll').css('pointer-events','auto');
$('.scroll table').css('pointer-events','none');
 

$('.btn-edit').click(function(){
    $('.panel-body').css('pointer-events','auto');
    $('.panel-body').css('opacity','1');
    $('.btn-save').removeAttr('disabled');
    $('.scroll table').css('pointer-events','auto');

    $('.website_link').css('display','none');
    $('.website').css('display','block');

});
$('.btn-save').click(function(){
    //$('.btn-save').attr('disabled',true);
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
</script>
@if ($client->overtime_pay_provided=='yes' && $client->overtime_percent)
<script type="text/javascript">
percent+=(parseInt("{{$client->overtime_percent}}") || 0) /100;
 
</script>
@endif
<script type="text/javascript">

function calc_overtime_rate(e){
    roundRate(e);
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
