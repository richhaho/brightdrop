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
                        <h3 class="bold">Search Worker - Candidates</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12">
                {!! Form::open(['route' => 'account.worker.setfilter','autocomplete' => 'off']) !!}
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
                                            @if(session('worker_search.currency_type_checkbox'))
                                            <input type="checkbox" name="currency_type_checkbox" class="check_advanced_search1" checked><span> Currency Type</span>
                                            @else
                                            <input type="checkbox" name="currency_type_checkbox" class="check_advanced_search1" ><span> Currency Type</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.birthday_checkbox'))
                                            <input type="checkbox" name="birthday_checkbox" class="check_advanced_search2" checked><span> Date of Birth</span>
                                            @else
                                            <input type="checkbox" name="birthday_checkbox" class="check_advanced_search2" ><span> Date of Birth</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.email_veem_checkbox'))
                                            <input type="checkbox" name="email_veem_checkbox" class="check_advanced_search3" checked><span> Email address - Veem</span>
                                            @else
                                            <input type="checkbox" name="email_veem_checkbox" class="check_advanced_search3" ><span> Email address - Veem</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.legal_name_checkbox'))
                                            <input type="checkbox" name="legal_name_checkbox" class="check_advanced_search4" checked><span> Full Legal Name</span>
                                            @else
                                            <input type="checkbox" name="legal_name_checkbox" class="check_advanced_search4" ><span> Full Legal Name</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.gender_checkbox'))
                                            <input type="checkbox" name="gender_checkbox" class="check_advanced_search5" checked><span> Gender</span>
                                            @else
                                            <input type="checkbox" name="gender_checkbox" class="check_advanced_search5" ><span> Gender</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.phone_checkbox'))
                                            <input type="checkbox" name="phone_checkbox" class="check_advanced_search6" checked><span> Phone Number</span>
                                            @else
                                            <input type="checkbox" name="phone_checkbox" class="check_advanced_search6" ><span> Phone Number</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.address_checkbox'))
                                            <input type="checkbox" name="address_checkbox" class="check_advanced_search7" checked><span> Physical Address</span>
                                            @else
                                            <input type="checkbox" name="address_checkbox" class="check_advanced_search7" ><span> Physical Address</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.skype_checkbox'))
                                            <input type="checkbox" name="skype_checkbox" class="check_advanced_search8" checked><span> Skype Id</span>
                                            @else
                                            <input type="checkbox" name="skype_checkbox" class="check_advanced_search8" ><span> Skype Id</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            <br>
                                            <label>- Candidate-Specific Fields:</label>
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.available_start_date_checkbox'))
                                            <input type="checkbox" name="available_start_date_checkbox" class="check_advanced_search9" checked><span> Available Start Date</span>
                                            @else
                                            <input type="checkbox" name="available_start_date_checkbox" class="check_advanced_search9" ><span> Available Start Date</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.outside_brightdrop_checkbox'))
                                            <input type="checkbox" name="outside_brightdrop_checkbox" class="check_advanced_search10" checked><span> Currently employed outside BrightDrop</span>
                                            @else
                                            <input type="checkbox" name="outside_brightdrop_checkbox" class="check_advanced_search10" ><span> Currently employed outside BrightDrop</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.home_based_experience_checkbox'))
                                            <input type="checkbox" name="home_based_experience_checkbox" class="check_advanced_search11" checked><span> Home-Based Experience</span>
                                            @else
                                            <input type="checkbox" name="home_based_experience_checkbox" class="check_advanced_search11" ><span> Home-Based Experience</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.available_hours_checkbox'))
                                            <input type="checkbox" name="available_hours_checkbox" class="check_advanced_search12" checked><span> Hours Available</span>
                                            @else
                                            <input type="checkbox" name="available_hours_checkbox" class="check_advanced_search12" ><span> Hours Available</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.long_term_work_issues_checkbox'))
                                            <input type="checkbox" name="long_term_work_issues_checkbox" class="check_advanced_search13" checked><span> Long-term work issues</span>
                                            @else
                                            <input type="checkbox" name="long_term_work_issues_checkbox" class="check_advanced_search13" ><span> Long-term work issues</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.us_business_hours_checkbox'))
                                            <input type="checkbox" name="us_business_hours_checkbox" class="check_advanced_search14" checked><span> Ok with US Business Hours</span>
                                            @else
                                            <input type="checkbox" name="us_business_hours_checkbox" class="check_advanced_search14" ><span> Ok with US Business Hours</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.reliable_quiet_workspace_checkbox'))
                                            <input type="checkbox" name="reliable_quiet_workspace_checkbox" class="check_advanced_search15" checked><span> Reliable Quiet Workspace</span>
                                            @else
                                            <input type="checkbox" name="reliable_quiet_workspace_checkbox" class="check_advanced_search15" ><span> Reliable Quiet Workspace</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.fulltime_compensation_amount_checkbox'))
                                            <input type="checkbox" name="fulltime_compensation_amount_checkbox" class="check_advanced_search16" checked><span> Requested Pay</span>
                                            @else
                                            <input type="checkbox" name="fulltime_compensation_amount_checkbox" class="check_advanced_search16" ><span> Requested Pay</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.speaks_spanish_checkbox'))
                                            <input type="checkbox" name="speaks_spanish_checkbox" class="check_advanced_search17" checked><span> Speaks Spanish</span>
                                            @else
                                            <input type="checkbox" name="speaks_spanish_checkbox" class="check_advanced_search17" ><span> Speaks Spanish</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.temp_video_link_checkbox'))
                                            <input type="checkbox" name="temp_video_link_checkbox" class="check_advanced_search18" checked><span> Temp - Video Link</span>
                                            @else
                                            <input type="checkbox" name="temp_video_link_checkbox" class="check_advanced_search18" ><span> Temp - Video Link</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.typing_test_number_of_errors_checkbox'))
                                            <input type="checkbox" name="typing_test_number_of_errors_checkbox" class="check_advanced_search19" checked><span> Typing Test - Number of Errors</span>
                                            @else
                                            <input type="checkbox" name="typing_test_number_of_errors_checkbox" class="check_advanced_search19" ><span> Typing Test - Number of Errors</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.typing_test_wpm_checkbox'))
                                            <input type="checkbox" name="typing_test_wpm_checkbox" class="check_advanced_search20" checked><span> Typing Test - WPM</span>
                                            @else
                                            <input type="checkbox" name="typing_test_wpm_checkbox" class="check_advanced_search20" ><span> Typing Test - WPM</span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if(session('worker_search.worker_source_checkbox'))
                                            <input type="checkbox" name="worker_source_checkbox" class="check_advanced_search21" checked><span> Worker Source</span>
                                            @else
                                            <input type="checkbox" name="worker_source_checkbox" class="check_advanced_search21" ><span> Worker Source</span>
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
                                <div class="col-md-6 form-group">
                                    <label>Worker Status:</label>
                                    {!!  Form::select('status',$status_list,session('worker_search.status'), ['class' => 'form-control changeStatus','onchange'=>'changeStatus()']) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Candidate Account Manager:</label>
                                    {!!  Form::select('candidate_account_manager_id',$candidate_account_manager_id,session('worker_search.candidate_account_manager_id'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>First Name:</label>
                                    <input name="first_name" class="form-control " type="text" value="{{session('worker_search.first_name')}}"  >
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Last Name:</label>
                                    <input name="last_name" class="form-control " type="text" value="{{session('worker_search.last_name')}}"  >
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Email - Main:</label>
                                    <input name="email_main" class="form-control " type="text" value="{{session('worker_search.email_main')}}"  >
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Country:</label>
                                    {!!  Form::select('country',$country,session('worker_search.country'), ['class' => 'form-control  ' ]) !!}
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group advanced_field1" style="{{session('worker_search.currency_type_checkbox') ? '':'display:none'}}">
                                    <label>Currency Type:</label>
                                    <input name="currency_type" class="form-control advanced_field1" type="text" value="{{session('worker_search.currency_type')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field2" style="{{session('worker_search.birthday_checkbox') ? '':'display:none'}}">
                                    <label>Date of Birth:</label>
                                    <input name="birthday" class="form-control advanced_field2" type="date" value="{{session('worker_search.birthday')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field3" style="{{session('worker_search.email_veem_checkbox') ? '':'display:none'}}">
                                    <label>Email address - Veem:</label>
                                    <input name="email_veem" class="form-control advanced_field3" type="text" value="{{session('worker_search.email_veem')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field4" style="{{session('worker_search.legal_name_checkbox') ? '':'display:none'}}">
                                    <label>Full Legal Name:</label>
                                    <input name="legal_name" class="form-control advanced_field4" type="text" value="{{session('worker_search.legal_name')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field5" style="{{session('worker_search.gender_checkbox') ? '':'display:none'}}">
                                    <label>Gender:</label>
                                    {!!  Form::select('gender',$gender,session('worker_search.gender'), ['class' => 'form-control advanced_field5']) !!}
                                </div>
                                <div class="col-md-3 form-group advanced_field6" style="{{session('worker_search.phone_checkbox') ? '':'display:none'}}">
                                    <label>Phone Number:</label>
                                    <input name="phone" class="form-control advanced_field6" type="text" value="{{session('worker_search.phone')}}">
                                </div>
                                <div class="col-md-6 form-group advanced_field7" style="{{session('worker_search.address_checkbox') ? '':'display:none'}}">
                                    <label>Physical Address:</label>
                                    <input name="address" class="form-control advanced_field7" type="text" value="{{session('worker_search.address')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field8" style="{{session('worker_search.skype_checkbox') ? '':'display:none'}}">
                                    <label>Skype Id:</label>
                                    <input name="skype" class="form-control advanced_field8" type="text" value="{{session('worker_search.skype')}}">
                                </div>

                                <div class="col-md-6 form-group advanced_field9" style="{{session('worker_search.available_start_date_checkbox') ? '':'display:none'}}">
                                    <label>Available Start Date:</label>
                                    <input name="available_start_date" class="form-control advanced_field9" type="text" value="{{session('worker_search.available_start_date')}}"> 
                                </div>
                                <div class="col-md-6 form-group advanced_field10" style="{{session('worker_search.outside_brightdrop_checkbox') ? '':'display:none'}}">
                                    <label>Currently employed outside BrightDrop:</label>
                                    {!!  Form::select('outside_brightdrop',[''=>'', 'yes'=>'Yes', 'no'=>'No'],session('worker_search.outside_brightdrop'), ['class' => 'form-control advanced_field10']) !!}
                                </div>
                                <div class="col-md-6 form-group advanced_field11" style="{{session('worker_search.outside_brightdrop_checkbox') ? '':'display:none'}}">
                                    <label>Home-Based Experience:</label>
                                    {!!  Form::select('home_based_experience',[''=>'', 'yes'=>'Yes', 'no'=>'No'],session('worker_search.home_based_experience'), ['class' => 'form-control advanced_field11']) !!}
                                </div>
                                <div class="col-md-6 form-group advanced_field12" style="{{session('worker_search.available_hours_checkbox') ? '':'display:none'}}">
                                    <label>Hours Available:</label>
                                    {!!  Form::select('available_hours',$available_hours,session('worker_search.available_hours'), ['class' => 'form-control advanced_field12']) !!}
                                </div>
                                <div class="col-md-6 form-group advanced_field13" style="{{session('worker_search.long_term_work_issues_checkbox') ? '':'display:none'}}">
                                    <label>Long-term work issues:</label>
                                    <input name="long_term_work_issues" class="form-control advanced_field13" type="text" value="{{session('worker_search.long_term_work_issues')}}"> 
                                </div>
                                <div class="col-md-3 form-group advanced_field14" style="{{session('worker_search.us_business_hours_checkbox') ? '':'display:none'}}">
                                    <label>Ok with US Business Hours:</label>
                                    {!!  Form::select('us_business_hours',[''=>'', 'yes'=>'Yes', 'no'=>'No'],session('worker_search.us_business_hours'), ['class' => 'form-control advanced_field14']) !!}
                                </div>
                                <div class="col-md-3 form-group advanced_field15" style="{{session('worker_search.reliable_quiet_workspace_checkbox') ? '':'display:none'}}">
                                    <label>Reliable Quiet Workspace:</label>
                                    {!!  Form::select('reliable_quiet_workspace',[''=>'', 'yes'=>'Yes', 'no'=>'No'],session('worker_search.reliable_quiet_workspace'), ['class' => 'form-control advanced_field15']) !!}
                                </div>
                                <div class="col-md-6 form-group advanced_field16" style="{{session('worker_search.fulltime_compensation_amount_checkbox') ? '':'display:none'}}">
                                    <label>Requested Pay:</label>
                                    <input name="fulltime_compensation_amount" class="form-control advanced_field16" type="number" value="{{session('worker_search.fulltime_compensation_amount')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field17" style="{{session('worker_search.speaks_spanish_checkbox') ? '':'display:none'}}">
                                    <label>Speaks Spanish:</label>
                                    {!!  Form::select('speaks_spanish',[''=>'', 'yes'=>'Yes', 'no'=>'No'],session('worker_search.speaks_spanish'), ['class' => 'form-control advanced_field17']) !!}
                                </div>
                                <div class="col-md-6 form-group advanced_field18" style="{{session('worker_search.temp_video_link_checkbox') ? '':'display:none'}}">
                                    <label>Temp - Video Link:</label>
                                    <input name="temp_video_link" class="form-control advanced_field18" type="text" value="{{session('worker_search.temp_video_link')}}"> 
                                </div>
                                <div class="col-md-6 form-group advanced_field19" style="{{session('worker_search.typing_test_number_of_errors_checkbox') ? '':'display:none'}}">
                                    <label>Typing Test - Number of Errors:</label>
                                    <input name="typing_test_number_of_errors" class="form-control advanced_field19" type="text" value="{{session('worker_search.typing_test_number_of_errors')}}"> 
                                </div>
                                <div class="col-md-6 form-group advanced_field20" style="{{session('worker_search.typing_test_wpm_checkbox') ? '':'display:none'}}">
                                    <label>Typing Test - WPM:</label>
                                    <input name="typing_test_wpm" class="form-control advanced_field20" type="text" value="{{session('worker_search.typing_test_wpm')}}"> 
                                </div>
                                <div class="col-md-6 form-group advanced_field21" style="{{session('worker_search.worker_source_checkbox') ? '':'display:none'}}">
                                    <label>Worker Source:</label>
                                    {!!  Form::select('worker_source',$worker_source,session('worker_search.worker_source'), ['class' => 'form-control  advanced_field21' ]) !!}
                                </div>
                                
                                <div class="col-md-12 form-group" style="margin-top:30px">
                                    <button type="submit" class="btn btn-success pull-right" style="margin-left: 10px"><i class="fa fa-search"></i> Search</button> 
                                    <a href="{{route('account.worker.resetfilter')}}" class="btn btn-warning pull-right"><i class="fa fa-remove"></i> Clear</a>

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
                            <a href="{{route('account.worker.downloadcsv')}}?worker_ids={{$worker_ids}}" class="btn btn-default pull-right btn-xs"><i class="fa fa-download"></i> Download Data</a>
                            <button class="btn btn-default pull-right btn-xs" onclick="createEmailList()" style="margin-right: 5px">Copy Email List</button>
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
                                            <th>Last&nbsp;Name</th>
                                            <th>First&nbsp;Name</th>
                                            <th>Email&nbsp;Main</th>
                                            <th>Country</th>
                                            <th>Candidate Account Manager</th>
                                            @if(session('worker_search.currency_type_checkbox'))
                                            <th>Currency&nbsp;Type</th>
                                            @endif
                                            @if(session('worker_search.birthday_checkbox'))
                                            <th>Date&nbsp;of&nbsp;Birth</th>
                                            @endif
                                            @if(session('worker_search.email_veem_checkbox'))
                                            <th>Email&nbsp;address-Veem</th>
                                            @endif
                                            @if(session('worker_search.legal_name_checkbox'))
                                            <th>Full Legal Name</th>
                                            @endif
                                            @if(session('worker_search.gender_checkbox'))
                                            <th>Gender</th>
                                            @endif
                                            @if(session('worker_search.phone_checkbox'))
                                            <th>Phone&nbsp;Number</th>
                                            @endif
                                            @if(session('worker_search.address_checkbox'))
                                            <th>Physical&nbsp;&nbsp;Address</th>
                                            @endif
                                            @if(session('worker_search.skype_checkbox'))
                                            <th>Skype&nbsp;&nbsp;Id</th>
                                            @endif
                                            @if(session('worker_search.available_start_date_checkbox'))
                                            <th>Available&nbsp;Star&nbsp;Date</th>
                                            @endif
                                            @if(session('worker_search.outside_brightdrop_checkbox'))
                                            <th>Currently employed outside BrightDrop</th>
                                            @endif
                                            @if(session('worker_search.home_based_experience_checkbox'))
                                            <th>Home-Based&nbsp;Experience</th>
                                            @endif
                                            @if(session('worker_search.available_hours_checkbox'))
                                            <th>Hours Available</th>
                                            @endif
                                            @if(session('worker_search.long_term_work_issues_checkbox'))
                                            <th>Long-term work issues</th>
                                            @endif
                                            @if(session('worker_search.us_business_hours_checkbox'))
                                            <th>Ok with US Business Hours</th>
                                            @endif
                                            @if(session('worker_search.reliable_quiet_workspace_checkbox'))
                                            <th>Reliable&nbsp;Quiet&nbsp;Workspace</th>
                                            @endif
                                            @if(session('worker_search.fulltime_compensation_amount_checkbox'))
                                            <th>Requested&nbsp;Pay</th>
                                            @endif
                                            @if(session('worker_search.speaks_spanish_checkbox'))
                                            <th>Speaks&nbsp;Spanish</th>
                                            @endif
                                            @if(session('worker_search.temp_video_link_checkbox'))
                                            <th>Temp-Video&nbsp;Link</th>
                                            @endif
                                            @if(session('worker_search.typing_test_number_of_errors_checkbox'))
                                            <th>Typing&nbsp;Test-Number&nbsp;of&nbsp;Errors</th>
                                            @endif
                                            @if(session('worker_search.typing_test_wpm_checkbox'))
                                            <th>Typing&nbsp;Test-WPM</th>
                                            @endif
                                            @if(session('worker_search.worker_source_checkbox'))
                                            <th>Worker&nbsp;&nbsp;Source</th>
                                            @endif
                                            <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($workers as $worker)
                                        <tr>
                                            <td>{{$status[$worker->status]}}</td>
                                            <td>{{$worker->last_name}}</td>
                                            <td>{{$worker->first_name}}</td>
                                            <td>{{$worker->email_main}}</td>
                                            <td>{{$worker->country}}</td>
                                            <td>{{isset($candidate_account_manager_id[$worker->candidate_account_manager_id]) ? $candidate_account_manager_id[$worker->candidate_account_manager_id] : ''}}</td>
                                            @if(session('worker_search.currency_type_checkbox'))
                                            <td>{{strtoupper($worker->currency_type)}}</td>
                                            @endif
                                            @if(session('worker_search.birthday_checkbox'))
                                            <td>{{date('m/d/y',strtotime($worker->birthday))}}</td>
                                            @endif
                                            @if(session('worker_search.email_veem_checkbox'))
                                            <td>{{$worker->email_veem}}</td>
                                            @endif
                                            @if(session('worker_search.legal_name_checkbox'))
                                            <td>{{$worker->legal_name}}</td>
                                            @endif
                                            @if(session('worker_search.gender_checkbox'))
                                            <td>{{ucfirst($worker->gender)}}</td>
                                            @endif
                                            @if(session('worker_search.phone_checkbox'))
                                            <td>{{$worker->phone}}</td>
                                            @endif
                                            @if(session('worker_search.address_checkbox'))
                                            <td>{{$worker->address}}</td>
                                            @endif
                                            @if(session('worker_search.skype_checkbox'))
                                            <td>{{$worker->skype}}</td>
                                            @endif
                                            @if(session('worker_search.available_start_date_checkbox'))
                                            <td>{{$worker->available_start_date}}</td>
                                            @endif
                                            @if(session('worker_search.outside_brightdrop_checkbox'))
                                            <td>{{$worker->outside_brightdrop}}</td>
                                            @endif
                                            @if(session('worker_search.home_based_experience_checkbox'))
                                            <td>{{$worker->home_based_experience}}</td>
                                            @endif
                                            @if(session('worker_search.available_hours_checkbox'))
                                            <td>{{$worker->available_hours}}</td>
                                            @endif
                                            @if(session('worker_search.long_term_work_issues_checkbox'))
                                            <td>{{$worker->long_term_work_issues}}</td>
                                            @endif
                                            @if(session('worker_search.us_business_hours_checkbox'))
                                            <td>{{$worker->us_business_hours}}</td>
                                            @endif
                                            @if(session('worker_search.reliable_quiet_workspace_checkbox'))
                                            <td>{{$worker->reliable_quiet_workspace}}</td>
                                            @endif
                                            @if(session('worker_search.fulltime_compensation_amount_checkbox'))
                                            <td>{{$worker->fulltime_compensation_amount}}</td>
                                            @endif
                                            @if(session('worker_search.speaks_spanish_checkbox'))
                                            <td>{{$worker->speaks_spanish}}</td>
                                            @endif
                                            @if(session('worker_search.temp_video_link_checkbox'))
                                            <td>{{$worker->temp_video_link}}</td>
                                            @endif
                                            @if(session('worker_search.typing_test_number_of_errors_checkbox'))
                                            <td>{{$worker->typing_test_number_of_errors}}</td>
                                            @endif
                                            @if(session('worker_search.typing_test_wpm_checkbox'))
                                            <td>{{$worker->typing_test_wpm}}</td>
                                            @endif
                                            @if(session('worker_search.worker_source_checkbox'))
                                            <td>
                                                {{$worker_source[$worker->worker_source]}}
                                                @if($worker->worker_source == 'internal_recruitment_manager' && $worker->internal_recruitment_manager)
                                                : {{$internal_recruitment_manager[$worker->internal_recruitment_manager]}}
                                                @endif
                                                @if($worker->worker_source == 'internal_other' && $worker->internal_other_employee)
                                                : {{$worker->internal_other_employee}}
                                                @endif
                                                @if($worker->worker_source == 'onlinejob.ph' && $worker->Onlinelinejobs_profilelink)
                                                : {{$worker->Onlinelinejobs_profilelink}}
                                                @endif
                                                @if($worker->worker_source == 'worker referral' && $worker->worker_referral)
                                                <?php $worker_referral = \App\Workers::where('id', $worker->worker_referral)->first(); ?>
                                                : {{$worker->worker_referral}}
                                                @endif
                                                @if($worker->worker_source == 'other' && $worker->worksource_other)
                                                : {{$worker_referral ? $worker_referral->full_name : ''}}
                                                @endif
                                            </td>
                                            @endif
                                            <td>
                                                <a href="/accountManager/profileWorker/{{$worker->id}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-close"  data-toggle="modal" data-target="#modal-delete-{{$worker->id}}"></i></a>
                                                @component('account.worker.components.deletemodal')
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
</script>

@endsection
