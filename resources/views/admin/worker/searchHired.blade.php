@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
 
</style>
@endsection

@section('content')


    <section id="WorkerSearch">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Search Worker - Hired</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12">
                {!! Form::open(['route' => 'admin.worker.setfilterHired','autocomplete' => 'off']) !!}
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Search Fields<a class="btn btn-default pull-right btn-xs" data-toggle="modal" data-target="#modal-advanced-search"><i class="fa fa-plus"></i> Advanced Search Fields</a></div>
                        
                        <div class="modal fade" id="modal-advanced-search" tabindex="-1" role="dialog">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Add Advanced Fields to Search.</h4>
                              </div>
                              <div class="modal-body row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <label>- General Information Fields</label>
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.currency_type_checkbox'))
                                            <input type="checkbox" name="currency_type_checkbox" class="check_advanced_search1" checked><span> Currency Type</span>
                                            @else
                                            <input type="checkbox" name="currency_type_checkbox" class="check_advanced_search1" ><span> Currency Type</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.birthday_checkbox'))
                                            <input type="checkbox" name="birthday_checkbox" class="check_advanced_search2" checked><span> Date of Birth</span>
                                            @else
                                            <input type="checkbox" name="birthday_checkbox" class="check_advanced_search2" ><span> Date of Birth</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.email_veem_checkbox'))
                                            <input type="checkbox" name="email_veem_checkbox" class="check_advanced_search3" checked><span> Email address - Veem</span>
                                            @else
                                            <input type="checkbox" name="email_veem_checkbox" class="check_advanced_search3" ><span> Email address - Veem</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.legal_name_checkbox'))
                                            <input type="checkbox" name="legal_name_checkbox" class="check_advanced_search4" checked><span> Full Legal Name</span>
                                            @else
                                            <input type="checkbox" name="legal_name_checkbox" class="check_advanced_search4" ><span> Full Legal Name</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.gender_checkbox'))
                                            <input type="checkbox" name="gender_checkbox" class="check_advanced_search5" checked><span> Gender</span>
                                            @else
                                            <input type="checkbox" name="gender_checkbox" class="check_advanced_search5" ><span> Gender</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.phone_checkbox'))
                                            <input type="checkbox" name="phone_checkbox" class="check_advanced_search6" checked><span> Phone Number</span>
                                            @else
                                            <input type="checkbox" name="phone_checkbox" class="check_advanced_search6" ><span> Phone Number</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.address_checkbox'))
                                            <input type="checkbox" name="address_checkbox" class="check_advanced_search7" checked><span> Physical Address</span>
                                            @else
                                            <input type="checkbox" name="address_checkbox" class="check_advanced_search7" ><span> Physical Address</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.skype_checkbox'))
                                            <input type="checkbox" name="skype_checkbox" class="check_advanced_search8" checked><span> Skype Id</span>
                                            @else
                                            <input type="checkbox" name="skype_checkbox" class="check_advanced_search8" ><span> Skype Id</span>
                                            @endif
                                        </div>

                                        <div class="col-md-12">
                                            <br>
                                            <label>- Hired Worker-Specific Fields:</label>
                                        </div>

                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.target_hours_week_checkbox'))
                                            <input type="checkbox" name="target_hours_week_checkbox" class="check_advanced_search9" checked><span> Target Hours Per Week</span>
                                            @else
                                            <input type="checkbox" name="target_hours_week_checkbox" class="check_advanced_search9" ><span> Target Hours Per Week</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.client_billable_rate_regular_checkbox'))
                                            <input type="checkbox" name="client_billable_rate_regular_checkbox" class="check_advanced_search10" checked><span> Client - Billable Rate - Regular</span>
                                            @else
                                            <input type="checkbox" name="client_billable_rate_regular_checkbox" class="check_advanced_search10" ><span> Client - Billable Rate - Regular</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.client_billable_rate_overtime_checkbox'))
                                            <input type="checkbox" name="client_billable_rate_overtime_checkbox" class="check_advanced_search11" checked><span> Client  Billable Rate - Overtime</span>
                                            @else
                                            <input type="checkbox" name="client_billable_rate_overtime_checkbox" class="check_advanced_search11" ><span> Client  Billable Rate - Overtime</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.client_billable_currency_type_checkbox'))
                                            <input type="checkbox" name="client_billable_currency_type_checkbox" class="check_advanced_search12" checked><span> Client - Billable Rate - Currency Type</span>
                                            @else
                                            <input type="checkbox" name="client_billable_currency_type_checkbox" class="check_advanced_search12" ><span> Client - Billable Rate - Currency Type</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.worker_pay_houly_rate_regular_checkbox'))
                                            <input type="checkbox" name="worker_pay_houly_rate_regular_checkbox" class="check_advanced_search13" checked><span> Worker - Hourly Pay Rate - Regular</span>
                                            @else
                                            <input type="checkbox" name="worker_pay_houly_rate_regular_checkbox" class="check_advanced_search13" ><span> Worker - Hourly Pay Rate - Regular</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.worker_pay_houly_rate_overtime_checkbox'))
                                            <input type="checkbox" name="worker_pay_houly_rate_overtime_checkbox" class="check_advanced_search14" checked><span> Worker - Hourly Pay Rate - Overtime</span>
                                            @else
                                            <input type="checkbox" name="worker_pay_houly_rate_overtime_checkbox" class="check_advanced_search14" ><span> Worker - Hourly Pay Rate - Overtime</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.worker_hourly_rate_currency_type_checkbox'))
                                            <input type="checkbox" name="worker_hourly_rate_currency_type_checkbox" class="check_advanced_search15" checked><span> Worker - Hourly Pay Rate - Currency Type</span>
                                            @else
                                            <input type="checkbox" name="worker_hourly_rate_currency_type_checkbox" class="check_advanced_search15" ><span> Worker - Hourly Pay Rate - Currency Type</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.ptodays_full_calendar_checkbox'))
                                            <input type="checkbox" name="ptodays_full_calendar_checkbox" class="check_advanced_search16" checked><span> PTO Days - Full Calendar Year</span>
                                            @else
                                            <input type="checkbox" name="ptodays_full_calendar_checkbox" class="check_advanced_search16" ><span> PTO Days - Full Calendar Year</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.ptodays_current_calendar_checkbox'))
                                            <input type="checkbox" name="ptodays_current_calendar_checkbox" class="check_advanced_search17" checked><span> PTO Days - First Calendar Year</span>
                                            @else
                                            <input type="checkbox" name="ptodays_current_calendar_checkbox" class="check_advanced_search17" ><span> PTO Days - First Calendar Year</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.worker_pto_hourly_rate_checkbox'))
                                            <input type="checkbox" name="worker_pto_hourly_rate_checkbox" class="check_advanced_search18" checked><span> Worker - PTO Hourly Rate</span>
                                            @else
                                            <input type="checkbox" name="worker_pto_hourly_rate_checkbox" class="check_advanced_search18" ><span> Worker - PTO Hourly Rate</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.worker_pto_currency_type_checkbox'))
                                            <input type="checkbox" name="worker_pto_currency_type_checkbox" class="check_advanced_search19" checked><span> Worker - PTO - Currency Type</span>
                                            @else
                                            <input type="checkbox" name="worker_pto_currency_type_checkbox" class="check_advanced_search19" ><span> Worker - PTO - Currency Type</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.worker_holiday_hourly_rate_checkbox'))
                                            <input type="checkbox" name="worker_holiday_hourly_rate_checkbox" class="check_advanced_search20" checked><span> Worker - Holiday Hourly Rate</span>
                                            @else
                                            <input type="checkbox" name="worker_holiday_hourly_rate_checkbox" class="check_advanced_search20" ><span> Worker - Holiday Hourly Rate</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search_hired.worker_holiday_currency_type_checkbox'))
                                            <input type="checkbox" name="worker_holiday_currency_type_checkbox" class="check_advanced_search21" checked><span> Worker - Holiday - Currency Type</span>
                                            @else
                                            <input type="checkbox" name="worker_holiday_currency_type_checkbox" class="check_advanced_search21" ><span> Worker - Holiday - Currency Type</span>
                                            @endif
                                        </div>
                                    </div>

                              </div>
                              <div class="modal-footer">
                                    <button class="btn btn-success btn-advanced-search" type="button"  data-dismiss="modal"> Apply</button>
                                    <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                              </div>
                            </div>
                          </div>
                        </div>


                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Worker Status:</label>
                                    {!!  Form::select('status',$status_list,session('worker_search_hired.status'), ['class' => 'form-control changeStatus','onchange'=>'changeStatus()']) !!}
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Account Manager:</label>
                                    {!!  Form::select('account_manager_id',$account_manager_id,session('worker_search_hired.account_manager_id'), ['class' => 'form-control search_account']) !!}
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Client:</label>
                                    {!!  Form::select('clients_id',$clients_id,session('worker_search_hired.clients_id'), ['class' => 'form-control search_client']) !!}
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>First Name:</label>
                                    <input name="first_name" class="form-control " type="text" value="{{session('worker_search_hired.first_name')}}"  >
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Last Name:</label>
                                    <input name="last_name" class="form-control " type="text" value="{{session('worker_search_hired.last_name')}}"  >
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Email - Main:</label>
                                    <input name="email_main" class="form-control " type="text" value="{{session('worker_search_hired.email_main')}}"  >
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Country:</label>
                                    {!!  Form::select('country',$country,session('worker_search_hired.country'), ['class' => 'form-control  ' ]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group advanced_field1" style="{{session('worker_search_hired.currency_type_checkbox') ? '':'display:none'}}">
                                    <label>Currency Type:</label>
                                    <input name="currency_type" class="form-control advanced_field1" type="text" value="{{session('worker_search_hired.currency_type')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field2" style="{{session('worker_search_hired.birthday_checkbox') ? '':'display:none'}}">
                                    <label>Date of Birth:</label>
                                    <input name="birthday" class="form-control advanced_field2" type="date" value="{{session('worker_search_hired.birthday')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field3" style="{{session('worker_search_hired.email_veem_checkbox') ? '':'display:none'}}">
                                    <label>Email address - Veem:</label>
                                    <input name="email_veem" class="form-control advanced_field3" type="text" value="{{session('worker_search_hired.email_veem')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field4" style="{{session('worker_search_hired.legal_name_checkbox') ? '':'display:none'}}">
                                    <label>Full Legal Name:</label>
                                    <input name="legal_name" class="form-control advanced_field4" type="text" value="{{session('worker_search_hired.legal_name')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field5" style="{{session('worker_search_hired.gender_checkbox') ? '':'display:none'}}">
                                    <label>Gender:</label>
                                    {!!  Form::select('gender',$gender,session('worker_search_hired.gender'), ['class' => 'form-control advanced_field5']) !!}
                                </div>
                                <div class="col-md-3 form-group advanced_field6" style="{{session('worker_search_hired.phone_checkbox') ? '':'display:none'}}">
                                    <label>Phone Number:</label>
                                    <input name="phone" class="form-control advanced_field6" type="text" value="{{session('worker_search_hired.phone')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field7" style="{{session('worker_search_hired.address_checkbox') ? '':'display:none'}}">
                                    <label>Physical Address:</label>
                                    <input name="address" class="form-control advanced_field7" type="text" value="{{session('worker_search_hired.address')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field8" style="{{session('worker_search_hired.skype_checkbox') ? '':'display:none'}}">
                                    <label>Skype Id:</label>
                                    <input name="skype" class="form-control advanced_field8" type="text" value="{{session('worker_search_hired.skype')}}">
                                </div>



                                <div class="col-md-4 form-group advanced_field9" style="{{session('worker_search_hired.target_hours_week_checkbox') ? '':'display:none'}}">
                                    <label>Target Hours Per Week:</label>
                                    <input name="target_hours_week" class="form-control advanced_field9" type="text" value="{{session('worker_search_hired.target_hours_week')}}"> 
                                </div>
                                <div class="col-md-6 form-group advanced_field10" style="{{session('worker_search_hired.client_billable_rate_regular_checkbox') ? '':'display:none'}}">
                                    <label>Client - Billable Rate - Regular:</label>
                                    <input name="client_billable_rate_regular" class="form-control advanced_field10" type="text" value="{{session('worker_search_hired.client_billable_rate_regular')}}"> 
                                </div>
                                <div class="col-md-6 form-group advanced_field11" style="{{session('worker_search_hired.client_billable_rate_overtime_checkbox') ? '':'display:none'}}">
                                    <label>Client  Billable Rate - Overtime:</label>
                                    <input name="client_billable_rate_overtime" class="form-control advanced_field11" type="text" value="{{session('worker_search_hired.client_billable_rate_overtime')}}"> 
                                </div>
                                <div class="col-md-6 form-group advanced_field12" style="{{session('worker_search_hired.client_billable_currency_type_checkbox') ? '':'display:none'}}">
                                    <label>Client - Billable Rate - Currency Type:</label>
                                    <input name="client_billable_currency_type" class="form-control advanced_field12" type="text" value="USD" readonly> 
                                </div>
                                <div class="col-md-4 form-group advanced_field13" style="{{session('worker_search_hired.worker_pay_houly_rate_regular_checkbox') ? '':'display:none'}}">
                                    <label>Worker - Hourly Pay Rate - Regular:</label>
                                    <input name="worker_pay_houly_rate_regular" class="form-control advanced_field13" type="text" value="{{session('worker_search_hired.worker_pay_houly_rate_regular')}}"> 
                                </div>
                                <div class="col-md-6 form-group advanced_field14" style="{{session('worker_search_hired.worker_pay_houly_rate_overtime_checkbox') ? '':'display:none'}}">
                                    <label>Worker - Hourly Pay Rate - Overtime:</label>
                                    <input name="worker_pay_houly_rate_overtime" class="form-control advanced_field14" type="text" value="{{session('worker_search_hired.worker_pay_houly_rate_overtime')}}"> 
                                </div>
                                <div class="col-md-6 form-group advanced_field15" style="{{session('worker_search_hired.worker_hourly_rate_currency_type_checkbox') ? '':'display:none'}}">
                                    <label>Worker - Hourly Pay Rate - Currency Type:</label>
                                    {!!  Form::select('worker_hourly_rate_currency_type',[''=>'', 'mxn'=>'MXN', 'php'=>'PHP', 'usd'=>'USD'],session('worker_search_hired.worker_hourly_rate_currency_type'), ['class' => 'form-control advanced_field15']) !!}
                                </div>
                                <div class="col-md-6 form-group advanced_field16" style="{{session('worker_search_hired.ptodays_full_calendar_checkbox') ? '':'display:none'}}">
                                    <label>PTO Days - Full Calendar Year:</label>
                                    <input name="ptodays_full_calendar" class="form-control advanced_field16" type="number" value="{{session('worker_search_hired.ptodays_full_calendar')}}">
                                </div>
                                <div class="col-md-6 form-group advanced_field17" style="{{session('worker_search_hired.ptodays_current_calendar_checkbox') ? '':'display:none'}}">
                                    <label>PTO Days - First Calendar Year:</label>
                                    <input name="ptodays_current_calendar" class="form-control advanced_field17" type="text" value="{{session('worker_search_hired.ptodays_current_calendar')}}"> 
                                </div>
                                <div class="col-md-4 form-group advanced_field18" style="{{session('worker_search_hired.worker_pto_hourly_rate_checkbox') ? '':'display:none'}}">
                                    <label>Worker - PTO Hourly Rate:</label>
                                    <input name="worker_pto_hourly_rate" class="form-control advanced_field18" type="text" value="{{session('worker_search_hired.worker_pto_hourly_rate')}}"> 
                                </div>
                                <div class="col-md-4 form-group advanced_field19" style="{{session('worker_search_hired.worker_pto_currency_type_checkbox') ? '':'display:none'}}">
                                    <label>Worker - PTO - Currency Type:</label>
                                    {!!  Form::select('worker_pto_currency_type',[''=>'', 'mxn'=>'MXN', 'php'=>'PHP', 'usd'=>'USD'],session('worker_search_hired.worker_pto_currency_type'), ['class' => 'form-control advanced_field19']) !!}
                                </div>
                                <div class="col-md-4 form-group advanced_field20" style="{{session('worker_search_hired.worker_holiday_hourly_rate_checkbox') ? '':'display:none'}}">
                                    <label>Worker - Holiday Hourly Rate:</label>
                                    <input name="worker_holiday_hourly_rate" class="form-control advanced_field20" type="text" value="{{session('worker_search_hired.worker_holiday_hourly_rate')}}"> 
                                </div>
                                <div class="col-md-4 form-group advanced_field21" style="{{session('worker_search_hired.worker_holiday_currency_type_checkbox') ? '':'display:none'}}">
                                    <label>Worker - Holiday - Currency Type:</label>
                                    {!!  Form::select('worker_holiday_currency_type',[''=>'', 'mxn'=>'MXN', 'php'=>'PHP', 'usd'=>'USD'],session('worker_search_hired.worker_holiday_currency_type'), ['class' => 'form-control  advanced_field21' ]) !!}
                                </div>                                
                                
                                <div class="col-md-12 form-group" style="margin-top:30px">
                                    <button type="submit" class="btn btn-success pull-right" style="margin-left: 10px"><i class="fa fa-search"></i> Search</button> 
                                    <a href="{{route('admin.worker.resetfilterHired')}}" class="btn btn-warning pull-right"><i class="fa fa-remove"></i> Clear</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Search Result
                            @if($worker_emails)
                            <a href="{{route('admin.worker.downloadcsv')}}?worker_ids={{$worker_ids}}" class="btn btn-default pull-right btn-xs"><i class="fa fa-download"></i> Download Data</a>
                            <button class="btn btn-default pull-right btn-xs" onclick="createEmailList()" style="margin-right: 5px">Copy Email List</button>
                            <a class="hidden btn btn-default pull-right btn-xs" data-toggle="modal" data-target="#modal-created-emails" style="margin-right: 5px">Create Email List</a>
                            <div class="modal fade" id="modal-created-emails" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Email List was created.</h4>
                                    </div>
                                    <div class="modal-body row">
                                        <div class="col-md-12" style="word-break: break-all">{{$worker_emails}}</div>
                                    </div>
                                    <div class="modal-footer">
                                         <button class="btn btn-primary" type="button"  data-dismiss="modal"> Okay </button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="box-body table_group" style="overflow-x: scroll;">
                                    <table id="list_table" class="table table-hover text-center table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Account Manager</th>
                                            <th>Client</th>
                                            <th>Last&nbsp;Name</th>
                                            <th>First&nbsp;Name</th>
                                            <th>Email&nbsp;Main</th>
                                            <th>Country</th>
                                            @if(session('worker_search_hired.currency_type_checkbox'))
                                            <th>Currency&nbsp;Type</th>
                                            @endif
                                            @if(session('worker_search_hired.birthday_checkbox'))
                                            <th>Date&nbsp;of&nbsp;Birth</th>
                                            @endif
                                            @if(session('worker_search_hired.email_veem_checkbox'))
                                            <th>Email&nbsp;address-Veem</th>
                                            @endif
                                            @if(session('worker_search_hired.legal_name_checkbox'))
                                            <th>Full Legal Name</th>
                                            @endif
                                            @if(session('worker_search_hired.gender_checkbox'))
                                            <th>Gender</th>
                                            @endif
                                            @if(session('worker_search_hired.phone_checkbox'))
                                            <th>Phone&nbsp;Number</th>
                                            @endif
                                            @if(session('worker_search_hired.address_checkbox'))
                                            <th>Physical&nbsp;&nbsp;Address</th>
                                            @endif
                                            @if(session('worker_search_hired.skype_checkbox'))
                                            <th>Skype&nbsp;&nbsp;Id</th>
                                            @endif


                                            @if(session('worker_search_hired.target_hours_week_checkbox'))
                                            <th>Target Hours Per Week</th>
                                            @endif
                                            @if(session('worker_search_hired.client_billable_rate_regular_checkbox'))
                                            <th>Client - Billable Rate - Regular</th>
                                            @endif
                                            @if(session('worker_search_hired.client_billable_rate_overtime_checkbox'))
                                            <th>Client  Billable Rate - Overtime</th>
                                            @endif
                                            @if(session('worker_search_hired.client_billable_currency_type_checkbox'))
                                            <th>Client - Billable Rate - Currency Type</th>
                                            @endif
                                            @if(session('worker_search_hired.worker_pay_houly_rate_regular_checkbox'))
                                            <th>Worker - Hourly Pay Rate - Regular</th>
                                            @endif
                                            @if(session('worker_search_hired.worker_pay_houly_rate_overtime_checkbox'))
                                            <th>Worker - Hourly Pay Rate - Overtime</th>
                                            @endif
                                            @if(session('worker_search_hired.worker_hourly_rate_currency_type_checkbox'))
                                            <th>Worker - Hourly Pay Rate - Currency Type</th>
                                            @endif
                                            @if(session('worker_search_hired.ptodays_full_calendar_checkbox'))
                                            <th>PTO Days - Full Calendar Year</th>
                                            @endif
                                            @if(session('worker_search_hired.ptodays_current_calendar_checkbox'))
                                            <th>PTO Days - First Calendar Year</th>
                                            @endif
                                            @if(session('worker_search_hired.worker_pto_hourly_rate_checkbox'))
                                            <th>Worker - PTO Hourly Rate</th>
                                            @endif
                                            @if(session('worker_search_hired.worker_pto_currency_type_checkbox'))
                                            <th>Worker - PTO - Currency Type</th>
                                            @endif
                                            @if(session('worker_search_hired.worker_holiday_hourly_rate_checkbox'))
                                            <th>Worker - Holiday Hourly Rate</th>
                                            @endif
                                            @if(session('worker_search_hired.worker_holiday_currency_type_checkbox'))
                                            <th>Worker - Holiday - Currency Type</th>
                                            @endif                                                      
                                            <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($workers as $worker)
                                        <tr>
                                            <td>{{ucfirst($worker->worker_status)}}</td>
                                            <td>{{$worker->account_manager_name}}</td>
                                            <td>{{$worker->client_name}}</td>
                                            <td>{{$worker->last_name}}</td>
                                            <td>{{$worker->first_name}}</td>
                                            <td>{{$worker->email_main}}</td>
                                            <td>{{$worker->country}}</td>
                                            @if(session('worker_search_hired.currency_type_checkbox'))
                                            <td>{{strtoupper($worker->currency_type)}}</td>
                                            @endif
                                            @if(session('worker_search_hired.birthday_checkbox'))
                                            <td>{{date('m/d/y',strtotime($worker->birthday))}}</td>
                                            @endif
                                            @if(session('worker_search_hired.email_veem_checkbox'))
                                            <td>{{$worker->email_veem}}</td>
                                            @endif
                                            @if(session('worker_search_hired.legal_name_checkbox'))
                                            <td>{{$worker->legal_name}}</td>
                                            @endif
                                            @if(session('worker_search_hired.gender_checkbox'))
                                            <td>{{ucfirst($worker->gender)}}</td>
                                            @endif
                                            @if(session('worker_search_hired.phone_checkbox'))
                                            <td>{{$worker->phone}}</td>
                                            @endif
                                            @if(session('worker_search_hired.address_checkbox'))
                                            <td>{{$worker->address}}</td>
                                            @endif
                                            @if(session('worker_search_hired.skype_checkbox'))
                                            <td>{{$worker->skype}}</td>
                                            @endif


                                            @if(session('worker_search_hired.target_hours_week_checkbox'))
                                            <td>{{$worker->target_hours_week}}</td>
                                            @endif
                                            @if(session('worker_search_hired.client_billable_rate_regular_checkbox'))
                                            <td>{{$worker->client_billable_rate_regular}}</td>
                                            @endif
                                            @if(session('worker_search_hired.client_billable_rate_overtime_checkbox'))
                                            <td>{{$worker->client_billable_rate_overtime}}</td>
                                            @endif
                                            @if(session('worker_search_hired.client_billable_currency_type_checkbox'))
                                            <td>USD</td>
                                            @endif
                                            @if(session('worker_search_hired.worker_pay_houly_rate_regular_checkbox'))
                                            <td>{{$worker->worker_pay_houly_rate_regular}}</td>
                                            @endif
                                            @if(session('worker_search_hired.worker_pay_houly_rate_overtime_checkbox'))
                                            <td>{{$worker->worker_pay_houly_rate_overtime}}</td>
                                            @endif
                                            @if(session('worker_search_hired.worker_hourly_rate_currency_type_checkbox'))
                                            <td>{{strtoupper($worker->currency_type)}}</td>
                                            @endif
                                            @if(session('worker_search_hired.ptodays_full_calendar_checkbox'))
                                            <td>{{$worker->ptodays_full_calendar}}</td>
                                            @endif
                                            @if(session('worker_search_hired.ptodays_current_calendar_checkbox'))
                                            <td>{{$worker->ptodays_current_calendar}}</td>
                                            @endif
                                            @if(session('worker_search_hired.worker_pto_hourly_rate_checkbox'))
                                            <td>{{$worker->worker_pto_hourly_rate}}</td>
                                            @endif
                                            @if(session('worker_search_hired.worker_pto_currency_type_checkbox'))
                                            <td>{{strtoupper($worker->currency_type)}}</td>
                                            @endif
                                            @if(session('worker_search_hired.worker_holiday_hourly_rate_checkbox'))
                                            <td>{{$worker->worker_holiday_hourly_rate}}</td>
                                            @endif
                                            @if(session('worker_search_hired.worker_holiday_currency_type_checkbox'))
                                            <td>{{strtoupper($worker->currency_type)}}</td>
                                            @endif
                                            <td>
                                                <a href="/admin/profileWorker/{{$worker->id}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-close"  data-toggle="modal" data-target="#modal-delete-{{$worker->id}}"></i></a>
                                                @component('admin.worker.components.deletemodal')
                                                @slot('id') 
                                                    {{ $worker->id }}
                                                @endslot
                                                @endcomponent

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
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $('#list_table').DataTable({searching: false, "pageLength": 20, "lengthMenu": [ 10, 20, 50 ], "order": [1, 'asc']});
});
function changeStatus(){
    if ($('.changeStatus').val()=='2'){
        $('.Disqualifier').css('display','block');
    }else{
        $('.Disqualifier').css('display','none');
    }
}
function changeCountry(){
    if ($('.changeCountry').val()=='3'){
        $('.Philippines').css('display','block');
    }else{
        $('.Philippines').css('display','none');
    }
}
function outsideBrightDrop(){
    if ($('.outsideBrightDrop').val()=='1'){
        $('.nonBrightDrop').css('display','block');
    }else{
        $('.nonBrightDrop').css('display','none');
    }
}
function changePrimaryConnection(){
    if ($('.changePrimaryConnection').val()=='6'){
        $('.PrimaryConnection').css('display','block');
    }else{
        $('.PrimaryConnection').css('display','none');
    }
}
function changeBackupConnection(){
    if ($('.changeBackupConnection').val()=='6'){
        $('.BackupConnection').css('display','block');
    }else{
        $('.BackupConnection').css('display','none');
    }
}


function changeWorkerSource(){
    $('.WorkerSource2').css('display','none');
    $('.WorkerSource3').css('display','none');
    $('.WorkerSource4').css('display','none');
    $('.WorkerSource5').css('display','none');
    $('.WorkerSource7').css('display','none');

    if ($('.changeWorkerSource').val()=='2'){
        $('.WorkerSource2').css('display','block');
    }
    if ($('.changeWorkerSource').val()=='3'){
        $('.WorkerSource3').css('display','block');
    }
    if ($('.changeWorkerSource').val()=='4'){
        $('.WorkerSource4').css('display','block');
    }
    if ($('.changeWorkerSource').val()=='5'){
        $('.WorkerSource5').css('display','block');
    }
    if ($('.changeWorkerSource').val()=='7'){
        $('.WorkerSource7').css('display','block');
    }
}

$('.btn-advanced-search').click(function(){
    for (i=1;i<22;i++){
        if ($('.check_advanced_search'+i).is(':checked')){
            $('.advanced_field'+i).css('display','block');
            $('.advanced_field'+i).attr('disabled',false);
        }else{
            $('.advanced_field'+i).css('display','none');
            $('.advanced_field'+i).attr('disabled',true);
        }
    }
});

function createEmailList() {
    const workerEmails = "{{$worker_emails}}";
    navigator.clipboard.writeText(workerEmails);
}

function downloadData() {
    const workerEmails = "{{$worker_emails}}";
    const csvContent = "data:text/csv;charset=utf-8," + workerEmails;
    const encodedUri = encodeURI(csvContent);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "worker_emails.csv");
    document.body.appendChild(link);
    link.click();
}

$('.search_account').change(function() {
    const account_id=$('.search_account').val() || 0;
    $.get("{{route('admin.client.assignedClients')}}",{"account_id":account_id}).done(function( clients ) {
        let clientOptions = '<option value="">All Clients</option>';
        $('.search_client').empty();
        clients.forEach((client) => {
            clientOptions += '<option value="'+client.id+'">'+client.client_name+'</option>';
        });
        $('.search_client').html(clientOptions);
    });
});

</script>

@endsection
