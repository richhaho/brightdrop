@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<link href="/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="/vendor/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css">
<style>
    /*.blue-field{display:none;}*/
    .panel-body{padding: 0px 15px 0px 15px !important;}
</style>
@endsection

@section('content')

{!! Form::open(['route' => 'admin.worker.store','autocomplete' => 'off','class'=>'submitform','files'=>true]) !!}
    <section id="createWorker">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12" >
                        <h3 class="bold">Create New Worker</h3>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
                        <button type="submit" class="btn btn-success pull right btn-save"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </div> 
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12 main_content">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1"><i class="fa fa-minus-square"></i> 
                            Worker Status</a>
                        </div>
                        <div class="panel-body panel-collapse collapse in" id="collapse1"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Worker Status:</label>
                                    {!!  Form::select('status',$status,old("status"), ['class' => 'form-control changeStatus','onchange'=>'changeStatus()']) !!}
                                </div>
                                <div class="col-md-6 form-group Disqualifier hidden" style="display:none">
                                    <label>Disqualifier Explanation:</label>
                                    <input name="disqualifier_explain" class="form-control " type="text" value="" >
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 yellow-field">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2"><i class="fa fa-plus-square"></i> Contact Information
                            </a>
                        </div>
                        <div class="panel-body panel-collapse collapse out" id="collapse2"><br>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>First Name:</label>
                                    <input name="first_name" class="form-control " type="text" value="" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Last Name:</label>
                                    <input name="last_name" class="form-control " type="text" value="" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Full Legal Name:</label>
                                    <input name="legal_name" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Email Address - Main:</label>
                                    <input name="email_main" class="form-control email" type="email" value="" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Email Address - Veem:</label>
                                    <input name="email_veem" class="form-control email_veem" type="email" value="" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Skype ID:</label>
                                    <input name="skype" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Phone Number:</label>
                                    <input name="phone" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Physical Address:</label>
                                    <input name="address" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Country:</label>
                                    {!!  Form::select('country',$country,old("country"), ['class' => 'form-control changeCountry','onchange'=>'changeCountry()']) !!}
                                </div>
                                <div class="col-md-3 form-group Philippines" style="display:none">
                                    <label>Philippines Region:</label>
                                    {!!  Form::select('philippines_region',$philippines_region,old("philippines_region"), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse13" aria-expanded="true" aria-controls="collapse13"><i class="fa fa-plus-square"></i> Worker Notes</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse13"><br>
                            <div class="row">
                                <div class="col-md-10 form-group">
                                    <input name="special_candiate_notes" class="form-control special_candiate_notes" type="text" >
                                </div>
                                <div class="col-md-2 form-group">
                                    <button type="button" class="btn btn-success btn-add-notes">Submit</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="20%">Date</th>
                                        <th width="20%">Input By</th>
                                        <th width="50%">Notes</th>
                                        <th width="10%"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="notes_table">
                                    
                                    
                                    </tbody>
                                    </table>
                                </div>           
                            </div><br>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse17" aria-expanded="true" aria-controls="collapse17"><i class="fa fa-plus-square"></i> Other Worker Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse17"><br>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Date of Birth:</label>
                                    <input name="birthday" class="form-control " type="date" value="">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Gender:</label>
                                    {!!  Form::select('gender',$gender,old("gender"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Currency Type:</label>
                                    {!!  Form::select('currency_type',$currency_type,old("currency_type"), ['class' => 'form-control', 'required'=>true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse21" aria-expanded="true" aria-controls="collapse21"><i class="fa fa-plus-square"></i> Documents – Other</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse21"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>ICA:</label>
                                    <input name="ica" class="form-control " type="file" >
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Typing Test:</label>
                                    <input name="typing_test_file" class="form-control " type="file" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <table id="other_file_table" class="table text-center table-bordered">
                                        <thead>
                                        <tr>
                                            <th width="75%">Label</th>
                                            <th width="20%">File</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody class="other_files_table">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 form-group">
                                    <button type="button" class="btn btn-success btn-add-other-file"> + New File Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="true" aria-controls="collapse3"><i class="fa fa-plus-square"></i> Login Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse3"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>UserName: (Login User Email)</label>
                                    <input name="user_email" class="form-control user_email" type="email" value="" required>
                                </div>
                                <div class="col-md-6 form-group" >
                                    <label>Password:</label>
                                    <input name="password" class="form-control " type="password" value="welcome123" required autocomplete="new-password">
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <h4>- Candidate Information</h4>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="true" aria-controls="collapse4"><i class="fa fa-plus-square"></i> General Candidate Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse4"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Candidate Account Manager:</label>
                                    {!!  Form::select('candidate_account_manager_id',$candidate_account_manager_id,old("candidate_account_manager_id"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Requested Pay:</label>
                                    <input name="fulltime_compensation_amount" class="form-control " type="text" value="" min="0" step="1">
                                </div>
                                <div class="col-md-6 form-group hidden">
                                    <label>Requested Pay – Currency Type:</label>
                                    {!!  Form::select('fulltime_compensation_currency',$fulltime_compensation_currency,old("fulltime_compensation_currency"), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row red-field">
                                <div class="col-md-6 form-group">
                                    <label>Hours Available:</label>
                                    {!!  Form::select('available_hours',$available_hours,old("available_hours"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Currently employed outside BrightDrop:</label>
                                    {!!  Form::select('outside_brightdrop',$outside_brightdrop,"no", ['class' => 'form-control outsideBrightDrop','onchange'=>'outsideBrightDrop()']) !!}

                                     
                                </div>
                                <div class="col-md-6 form-group nonBrightDrop" style="display:none">
                                    <label>Hours per week outside BrightDrop:</label>
                                    {!!  Form::select('hours_outside_perweek',$hours_outside_perweek,old("hours_outside_perweek"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group nonBrightDrop" style="display:none">
                                    <label> Current Non-BrightDrop Work Hours:</label>
                                    <input name="current_nonbrightdrop_hours" class="form-control " type="text" value=""> 
                                </div>
                            </div>
                            <div class="row hidden">
                                <div class="col-md-12 form-group">
                                    <label>Target Client:</label>
                                    <table width="100%">
                                    <thead>
                                    <tr>
                                        <th width="70%"></th>
                                        <th width="30%"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="target_client_table">
                                    <tr style="height:40px">
                                        <td>
                                            {!!  Form::select('target_client[1]',$target_client,old("target_client"), ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                             <button type="button" class=" btn btn-danger" onclick="delete_targetclient(this);" style="margin-left: 10px"><i class="fa fa-close"></i></button>
                                        </td>
                                    </tr>
                                                                          
                                    
                                    </tbody>
                                    </table>
                                </div>
                                <div class="col-md-9 form-group">
                                    <button type="button" class="pull-right btn btn-success btn-add-targetclient"><i class="fa fa-plus"></i> Add Client</button>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group red-field">
                                    <label>Available Start Date:</label>
                                    <input name="available_start_date" class="form-control" type="text" value="" data-toggle="tooltip" data-placement="top" title='This is a text field to allow you to input an exact date or a phrase, such as "in 30 days" or "ASAP".'> 
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-6 form-group red-field">
                                    <label>Typing Test – WPM:</label>
                                    <input type="text" name="typing_test_wpm" class="form-control"> 
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Typing Test – Number of Errors:</label>
                                    <input type="text" name="typing_test_number_of_errors" class="form-control">
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Speaks Spanish:</label>
                                    {!!  Form::select('speaks_spanish',[''=>'', 'yes'=>'Yes', 'no'=>'No'],'', ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Ok with US Business Hours:</label>
                                    {!!  Form::select('us_business_hours',[''=>'', 'yes'=>'Yes', 'no'=>'No'],'', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group red-field">
                                    <label>Home-Based Experience:</label>
                                    {!!  Form::select('home_based_experience',[''=>'', 'yes'=>'Yes', 'no'=>'No'],'', ['class' => 'form-control home_based_experience']) !!}
                                </div>
                                <div class="col-md-6 form-group red-field home_based_additional_info hidden">
                                    <label>Home-Based – Additional Info:</label>
                                    <textarea name="home_based_additional_info" rows="1" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group red-field">
                                    <label>Reliable Quiet Workspace:</label>
                                    {!!  Form::select('reliable_quiet_workspace',[''=>'', 'yes'=>'Yes', 'no'=>'No'],'', ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Long-term work issues:</label>
                                    <textarea name="long_term_work_issues" rows="1" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="true" aria-controls="collapse5"><i class="fa fa-plus-square"></i> Candidate Submissions - Level 1</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse5"><br>
                            <div class="row">
                                <div class="col-md-12 form-group red-field">
                                    <label>Writing Sample:</label>
                                    <textarea name="writing_sample" class="form-control" rows="3"></textarea> 
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Link Sub Field:</label>
                                    <input name="video_sub_link" class="form-control " type="text" value="" >
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Video Link:</label><br>
                                    <p>*This field will be automatically populated after a video is submitted and a unique link sub field is given.*</p>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group red-field">
                                    <label>Original Video File:</label>
                                    <input name="video_file" class="form-control " type="file" >
                                    <p class="video_file_error hidden text-danger">* Mp4 format required.</p>
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Resume:</label>
                                    <input name="resume_file" class="form-control " type="file" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group red-field">
                                    <label>Temp - Video Link:</label>
                                    <input name="temp_video_link" class="form-control" type="text" value="">
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="true" aria-controls="collapse6"><i class="fa fa-plus-square"></i> Candidate Submissions - Level 2</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse6"><br>
                            <div class="row">
                                <div class="col-md-6 form-group red-field">
                                    <label>Copy of Government-Issued ID:</label>
                                    <input name="goverment_id" class="form-control " type="file" >
                                </div>
                                <div class="col-md-6 form-group blue-field">
                                    <label>Copy of NBI Clearance:</label>
                                    <input name="NBI" class="form-control " type="file" >
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse7" aria-expanded="true" aria-controls="collapse7"><i class="fa fa-plus-square"></i> Worker Source</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse7"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Worker Source:</label>
                                    {!!  Form::select('worker_source',$worker_source,"", ['class' => 'form-control changeWorkerSource','onchange'=>'changeWorkerSource()']) !!}
                                    
                                </div>
                                <div class="col-md-6 form-group WorkerSource2" style="display:none">
                                    <label>Internal - Recruitment Manager:</label>
                                    {!!  Form::select('internal_recruitment_manager',$internal_recruitment_manager,old("internal_recruitment_manager"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group WorkerSource3" style="display:none">
                                    <label>Internal - Other Employee:</label>
                                    {!!  Form::select('internal_other_employee',$internal_other_employee,old("internal_other_employee"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group WorkerSource4" style="display:none">
                                    <label>Onlinelinejobs.ph - Profile Link:</label>
                                    <input name="Onlinelinejobs_profilelink" class="form-control " type="text" >
                                </div>
                                <div class="col-md-6 form-group WorkerSource5" style="display:none">
                                    <label>Worker Referral:</label>
                                    {!!  Form::select('worker_referral',$worker_referral,old("worker_referral"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group WorkerSource7" style="display:none">
                                    <label>Other:</label>
                                    <input name="worksource_other" class="form-control " type="text" >
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse8" aria-expanded="true" aria-controls="collapse8"><i class="fa fa-plus-square"></i> English Assessment</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse8"><br>
                            <div class="row">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table text-center">
                                    <thead>
                                    <tr>
                                        <th width="20%">Type</th>
                                        <th width="30%">Ranking (10 = Highest)</th>
                                        <th width="50%">Notes</th>
                                    </tr>
                                    </thead>
                                    <tbody> 
                                    <tr>
                                        <td>Verbal</td>
                                        <td>
                                        {!!  Form::select('english_verbal',$english_verbal,old("english_verbal"), ['class' => 'form-control']) !!}
                                        </td>
                                        <td><input class="form-control" type="text" name="english_verbal_note"></td>
                                    </tr>
                                    <tr>
                                        <td>Written</td>
                                        <td>
                                        {!!  Form::select('english_written',$english_written,old("english_written"), ['class' => 'form-control']) !!}
                                        </td>
                                        <td><input class="form-control" type="text" name="english_written_note"></td>
                                    </tr>
                                    </tbody>
                                    </table>
                                </div> 
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 red-field">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse9" aria-expanded="true" aria-controls="collapse9"><i class="fa fa-plus-square"></i> Skills Assessment</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse9"><br>
                            <div class="row">
                                <div class="box-body table_group" style="overflow-x: scroll;">
                                    <table id="detail_table" class="table text-center">
                                    <thead>
                                    <tr>
                                        <th width="30%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Position/Skill&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Industry&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th width="10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Years&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th width="10%">&nbsp;&nbsp;&nbsp;Months&nbsp;&nbsp;&nbsp;</th>
                                        <th width="20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Notes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th width="5%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody class="skill_table">
                                     
                                    <tr>
                                        <td>
                                            {!!  Form::select('skill_name[1]',$skill_name,old("skill_name"), ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                             
                                             {!!  Form::select('skill_industry[1]',$skill_industry,old("skill_industry"), ['class' => 'form-control']) !!}
                                        </td>

                                        <td>
                                            {!!  Form::select('skill_years[1]',$skill_years,old("skill_years"), ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                            {!!  Form::select('skill_months[1]',$skill_months,old("skill_months"), ['class' => 'form-control']) !!}
                                        </td>
                                        <td><input class="form-control" type="text" name="skill_note[1]"></td>
                                        <td><button type="button" class="btn btn-danger " onclick="skill_delete_list(this)"><i class="fa fa-close"></i></button></td>
                                    </tr>
                                     
                                    </tbody>
                                    </table>
                                </div> 
                                <div class="col-md-12" style="height: 25px">
                                    <button type="button" class="btn btn-success pull-right btn-add-skill"><i class="fa fa-plus"></i> Add more</button>
                                </div>  
                                <div class="col-md-12">
                                    <label>Additional Notes - Skills Assessment:</label>
                                    <input name="skill_other" class="form-control " type="text" >
                                </div>
                            
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 red-field">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse10" aria-expanded="true" aria-controls="collapse10"><i class="fa fa-plus-square"></i> Software Knowledge</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse10"><br>
                            <div class="row">
                                <div class="box-body table_group" style="overflow-x: scroll;">
                                    <table id="detail_table" class="table text-center">
                                    <thead>
                                    <tr>
                                        <th width="30%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Software&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Industry&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th width="10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Years&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th width="10%">&nbsp;&nbsp;&nbsp;Months&nbsp;&nbsp;&nbsp;</th>
                                        <th width="20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Notes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th width="5%"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="soft_table">
                                     
                                    <tr>
                                        <td>
                                            {!!  Form::select('software_name[1]',$software_name,old("software_name"), ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                            {!!  Form::select('software_industry[1]',$software_industry,old("software_industry"), ['class' => 'form-control']) !!}
                                        </td>

                                        <td>
                                            {!!  Form::select('software_years[1]',$software_years,old("software_years"), ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                            {!!  Form::select('software_months[1]',$software_months,old("software_months"), ['class' => 'form-control']) !!}
                                        </td>
                                        <td><input class="form-control" type="text" name="software_note[1]"></td>
                                        <td><button type="button" class="btn btn-danger" onclick="software_delete_list(this)"><i class="fa fa-close"></i></button></td></td>
                                    </tr>
                                     
                                    </tbody>
                                    </table>
                                </div> 
                                <div class="col-md-12" style="height: 25px">
                                    <button type="button" class="btn btn-success pull-right btn-add-software"><i class="fa fa-plus"></i> Add more</button>
                                </div> 
                                <div class="col-md-12">
                                    <label>Additional Notes - Software Knowledge:</label>
                                    <input name="software_other" class="form-control " type="text" >
                                </div>
                               
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse11" aria-expanded="true" aria-controls="collapse11"><i class="fa fa-plus-square"></i> Technical Information - Internet Connection:</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse11"><br>
                            <div class="row yellow-field">
                                <div class="col-md-6 form-group">
                                    <label>Primary Connection - ISP:</label>
                                    {!!  Form::select('internet_connection_primary',$internet_connection_primary,old("internet_connection_primary"), ['class' => 'form-control changePrimaryConnection','onchange'=>'changePrimaryConnection()']) !!}
                                     
                                </div>
                                <div class="col-md-6 form-group PrimaryConnection" style="display:none">
                                    <label>Other:</label>
                                    <input name="internet_connection_primary_other" class="form-control " type="text" >
                                </div>
                            </div>
                            <div class="row yellow-field">
                                <div class="col-md-6 form-group">
                                    <label>Primary Connection - Type:</label>
                                    {!!  Form::select('internet_connection_primary_type',$internet_connection_primary_type,old("internet_connection_primary_type"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group ">
                                    <label>Primary Connection - Speed:</label>
                                    <input name="internet_connection_primary_speed" class="form-control " type="number" >
                                </div> 
                                <div class="col-md-6 form-group ">
                                    <label>Speed Test – Screenshot:</label>
                                    <input name="internet_connection_primary_screenshot" class="form-control " type="file" >
                                </div>
                                <div class="col-md-6 form-group ">
                                    <label>Data Cap:</label>
                                    <input name="internet_connection_primary_data_cap" class="form-control " type="text" >
                                </div>      
                            </div>
                            <div class="row yellow-field">
                                <div class="col-md-6 form-group" style="display: none">
                                    <label>Backup Connection:</label>
                                    {!!  Form::select('backup_connection',$backup_connection,old("backup_connection"), ['class' => 'form-control backup_connection']) !!}

                                </div>
                                <div class="col-md-12 form-group">
                                    <button class="pull-right btn btn-success btn-add-backup-connection" type="button"> Add Backup Connection</button>
                                </div>
                            </div>
                            <div class="row yellow-field add-backup-connection" style="display:none">
                                <div class="col-md-6 form-group">
                                    <label>Backup Connection - ISP:</label>
                                    {!!  Form::select('backup_connection_isp',$backup_connection_isp,old("backup_connection_isp"), ['class' => 'form-control changeBackupConnection','onchange'=>'changeBackupConnection()']) !!}
                                     
                                </div>
                                <div class="col-md-6 form-group BackupConnection" style="display:none">
                                    <label>Other:</label>
                                    <input name="backup_connection_other" class="form-control " type="text" >
                                </div>
                            </div>
                            <div class="row yellow-field add-backup-connection" style="display:none">
                                <div class="col-md-6 form-group">
                                    <label>Backup Connection - Type:</label>
                                    {!!  Form::select('backup_connection_type',$backup_connection_type,old("backup_connection_type"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group ">
                                    <label>Backup Connection - Speed:</label>
                                    <input name="backup_connection_speed" class="form-control " type="number" >
                                </div> 
                                <div class="col-md-6 form-group ">
                                    <label>Speed Test – Screenshot:</label>
                                    <input name="backup_connection_screenshot" class="form-control " type="file" >
                                </div>
                                <div class="col-md-6 form-group ">
                                    <label>Data Cap:</label>
                                    <input name="backup_connection_data_cap" class="form-control " type="text" >
                                </div>      
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Additional Notes - Internet Connection:</label>
                                    <input name="internet_connection_note" class="form-control " type="text" >
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>                            
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse12" aria-expanded="true" aria-controls="collapse12"><i class="fa fa-plus-square"></i> Technical Information - Computer:</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse12"><br>
                            
                            <div class="row yellow-field">
                                <div class="col-md-4 form-group">
                                    <label>Primary Computer - Type:</label>
                                    {!!  Form::select('primary_computer_type',$primary_computer_type,old("primary_computer_type"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4 form-group ">
                                    <label>Primary Computer - Brand:</label>
                                    <input name="primary_computer_brand" class="form-control " type="text" >
                                </div> 
                                <div class="col-md-4 form-group ">
                                    <label>Primary Computer - Model:</label>
                                    <input name="primary_computer_model" class="form-control " type="text" >
                                </div> 
                                <div class="col-md-6 form-group">
                                    <label>Primary Computer - Age (Years):</label>
                                    {!!  Form::select('primary_computer_age',$primary_computer_age,old("primary_computer_age"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Primary Computer - Operating System:</label>
                                    {!!  Form::select('primary_computer_system',$primary_computer_system,old("primary_computer_system"), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="row yellow-field">
                                <div class="col-md-6 form-group" style="display: none" >
                                    <label>Backup Computer:</label>
                                    {!!  Form::select('backup_computer',$backup_computer,old("backup_computer"), ['class' => 'form-control backup_computer']) !!}
                                </div>
                                <div class="col-md-12 form-group">
                                    <button class="pull-right btn btn-success btn-add-backup-computer" type="button"> Add Backup Computer</button>
                                </div>
                            </div>
                            <div class="row yellow-field add-backup-computer" style="display: none">
                                <div class="col-md-4 form-group">
                                    <label>Backup Computer - Type:</label>
                                    {!!  Form::select('backup_computer_type',$backup_computer_type,old("backup_computer_type"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4 form-group ">
                                    <label>Backup Computer - Brand:</label>
                                    <input name="backup_computer_brand" class="form-control " type="text" >
                                </div> 
                                <div class="col-md-4 form-group ">
                                    <label>Backup Computer - Model:</label>
                                    <input name="backup_computer_model" class="form-control " type="text" >
                                </div> 
                                <div class="col-md-6 form-group">
                                    <label>Backup Computer - Age(Years):</label>
                                    {!!  Form::select('backup_computer_age',$backup_computer_age,old("backup_computer_age"), ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Backup Computer - Operating System:</label>
                                    {!!  Form::select('backup_computer_system',$backup_computer_system,old("backup_computer_system"), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Additional Notes - Computer:</label>
                                    <input name="technical_computer_note" class="form-control " type="text" >
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div> 
                                
                <div class="col-xs-12 blue-field">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse14" aria-expanded="true" aria-controls="collapse14"><i class="fa fa-plus-square"></i> Work Schedule</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse14"><br>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Current work schedule:</label>
                                    <textarea name="work_schedule" class="form-control" rows="6"></textarea>
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 yellow-field">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse15" aria-expanded="true" aria-controls="collapse15"><i class="fa fa-plus-square"></i> Backup Plan</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse15"><br>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Backup Plan:</label>
                                    <textarea name="backup_plan" class="form-control" rows="6"></textarea>
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 yellow-field">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse16" aria-expanded="true" aria-controls="collapse16"><i class="fa fa-plus-square"></i> Emergency Contact Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse16"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 1 - Full Name:</label>
                                    <input name="emergency_contact1_fullname" class="form-control " type="text" >
                                </div>
                            
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 1 - Relationship:</label>
                                    <input name="emergency_contact1_relationship" class="form-control " type="text" >
                                </div>
                           
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 1 - Email Address:</label>
                                    <input name="emergency_contact1_email" class="form-control " type="text" >
                                </div>
                            
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 1 - Phone Number:</label>
                                    <input name="emergency_contact1_phone" class="form-control " type="text" >
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Emergency Contact - 1 - Full Address:</label>
                                    <input name="emergency_contact1_address"   class="form-control " type="text" >
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 2 - Full Name:</label>
                                    <input name="emergency_contact2_fullname" class="form-control " type="text" >
                                </div>
                            
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 2 - Relationship:</label>
                                    <input name="emergency_contact2_relationship" class="form-control " type="text" >
                                </div>
                           
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 2 - Email Address:</label>
                                    <input name="emergency_contact2_email" class="form-control " type="text" >
                                </div>
                            
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 2 - Phone Number:</label>
                                    <input name="emergency_contact2_phone" class="form-control " type="text" >
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Emergency Contact - 2 - Full Address:</label>
                                    <input name="emergency_contact2_address"  class="form-control " type="text" >
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>
                                
                
            </div>
        </div>
    </section>
{!! Form::close() !!}
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/vendor/select2/js/select2.min.js" type="text/javascript"></script>
<script src="/vendor/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
<script>

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
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
function changeStatus(){
    if ($('.changeStatus').val()=='disqualfied'){
        $('.Disqualifier').css('display','block');
    }else{
        $('.Disqualifier').css('display','none');
    }
    return;
    if ($('.changeStatus').val()=='new_candidate'){
        $('.yellow-field').css('display','block');
        $('.red-field').css('display','block');
        $('.blue-field').css('display','none');
    }
    if ($('.changeStatus').val()=='disqualfied'){
        $('.yellow-field').css('display','none');
        $('.red-field').css('display','none');
        $('.blue-field').css('display','none');
        $('.yellow-field input').removeAttr('required');
    }
    if ($('.changeStatus').val()=='pre_candidate'){
        $('.yellow-field').css('display','block');
        $('.red-field').css('display','block');
        $('.blue-field').css('display','none');
    }
    if ($('.changeStatus').val()=='available_hired'){
        $('.yellow-field').css('display','block');
        $('.red-field').css('display','block');
        $('.blue-field').css('display','block');
    }
    if ($('.changeStatus').val()=='not_available_hired'){
        $('.yellow-field').css('display','block');
        $('.red-field').css('display','none');
        $('.blue-field').css('display','block');
    }
    
    
}
function changeCountry(){
    if ($('.changeCountry').val()=='Philippines'){
        $('.Philippines').css('display','block');
    }else{
        $('.Philippines').css('display','none');
    }

    var currency_type="usd";
    switch($('.changeCountry').val()) {
      case "Mexico":
        currency_type = "mxn";
        break;
      case "Nicaragua":
        currency_type = "usd";
        break;
      case "Philippines":
        currency_type = "php";
        break;
      case "USA":
        currency_type = "usd";
        break;  
      default:
        currency_type = "usd";
    }
    $('select[name="currency_type"]').val(currency_type);
}
function outsideBrightDrop(){
    if ($('.outsideBrightDrop').val()=='yes'){
        $('.nonBrightDrop').css('display','block');
    }else{
        $('.nonBrightDrop').css('display','none');
    }
}
function changePrimaryConnection(){
    if ($('.changePrimaryConnection').val()=='Other'){
        $('.PrimaryConnection').css('display','block');
    }else{
        $('.PrimaryConnection').css('display','none');
    }
}
function changeBackupConnection(){
    if ($('.changeBackupConnection').val()=='Other'){
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

    if ($('.changeWorkerSource').val()=='internal_recruitment_manager'){
        $('.WorkerSource2').css('display','block');
    }
    if ($('.changeWorkerSource').val()=='internal_other'){
        $('.WorkerSource3').css('display','block');
    }
    if ($('.changeWorkerSource').val()=='onlinejob.ph'){
        $('.WorkerSource4').css('display','block');
    }
    if ($('.changeWorkerSource').val()=='worker referral'){
        $('.WorkerSource5').css('display','block');
    }
    if ($('.changeWorkerSource').val()=='other'){
        $('.WorkerSource7').css('display','block');
    }
}
function delete_notes_list(e){
     
    $(e).parent().parent().remove();
}
function software_delete_list(e){
     
    $(e).parent().parent().remove();
}
function skill_delete_list(e){
     
    $(e).parent().parent().remove();
}
function delete_targetclient(e){
    $(e).parent().parent().remove();   
}
var note_id=0;
$('.btn-add-notes').click(function(){
    var note=$('.special_candiate_notes').val();
    if (!note) return;
    note_id++;
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; 
    var yyyy = today.getFullYear();
    if(dd<10) dd='0'+dd;
    if(mm<10) mm='0'+mm;
    today = mm+'/'+dd+'/'+yyyy;

    var adder="{{Auth::user()->admin()->fullname}}";

    var add='<tr>'
            +    '<td>'+today+'</td>'
            +    '<td>'+adder+'</td>'
            +    '<td><input value="'+note+'" type="text" name="candiate_notes['+note_id+']" class="form-control" style="text-align:center" readonly></td>'
            +    '<td><button type="button" class="btn btn-danger contact_delete_btn btn-xs" onclick="delete_notes_list(this)"> delete</button></td>'
            +'</tr>';

    $('.notes_table').append(add);
    $('.special_candiate_notes').val('');
   
    
});
var soft_id=1;
$('.btn-add-software').click(function(){
    soft_id++;
    var add='<tr>'
            +'<td>'
            +    '<select name="software_name['+soft_id+']"  class="form-control">'
            +        '<option value="Accounting - Quickbooks (Desktop)">Accounting - Quickbooks (Desktop)</option>'
            +        '<option value="Accounting - Quickbooks (Online)">Accounting - Quickbooks (Online)</option>'
            +        '<option value="Accounting - Xero">Accounting - Xero</option>'
            +        '<option value="CRM - Insightly">CRM - Insightly</option>'
            +        '<option value="CRM - Salesforce">CRM - Salesforce</option>'
            +        '<option value="CRM - Zoho">CRM - Zoho</option>'
            +        '<option value="Graphic Design - Adobe Illustrator">Graphic Design - Adobe Illustrator</option>'
            +        '<option value="Marketing - MailChimp">Marketing - MailChimp</option>'
            +        '<option value="Real Estate - Boomtown">Real Estate - Boomtown</option> '
            +    '</select>'
            +'</td>'
            +'<td>'
            +    '<select name="software_industry['+soft_id+']" class="form-control">'
            +        '<option value="Banking">Banking</option>'
            +        '<option value="High Tech">High Tech</option>'
            +        '<option value="Insurance">Insurance</option>'
            +        '<option value="Real Estate">Real Estate</option>'
            +        '<option value="Telecom">Telecom</option>'
            +        '<option value="Other">Other</option>'
            +    '</select>'
            +'</td>'
            +'<td>'
            +    '<select name="software_years['+soft_id+']" class="form-control">'
            +        '<option value="0" selected="selected">0</option>'
            +        '<option value="1">1</option>'
            +        '<option value="2">2</option>'
            +        '<option value="3">3</option>'
            +        '<option value="4">4</option>'
            +        '<option value="5">5</option>'
            +        '<option value="6">6</option>'
            +        '<option value="7">7</option>'
            +        '<option value="8">8</option>'
            +        '<option value="9">9</option>'
            +        '<option value="10">10</option>'
            +        '<option value="10+">10+</option>'
            +    '</select>'
            +'</td>'
            +'<td>'
            +    '<select name="software_months['+soft_id+']" class="form-control">'
            +        '<option value="0" selected="selected">0</option>'
            +        '<option value="1">1</option>'
            +        '<option value="2">2</option>'
            +        '<option value="3">3</option>'
            +        '<option value="4">4</option>'
            +        '<option value="5">5</option>'
            +        '<option value="6">6</option>'
            +        '<option value="7">7</option>'
            +        '<option value="8">8</option>'
            +        '<option value="9">9</option>'
            +        '<option value="10">10</option>'
            +        '<option value="11">11</option>'
            +        '<option value="12">12</option>'
            +    '</select>'
            +'</td>'
            +'<td><input name="software_note['+soft_id+']" class="form-control" type="text"></td>'
            +'<td><button type="button" class="btn btn-danger" onclick="software_delete_list(this)"><i class="fa fa-close"></i></button></td></td>'
        +'</tr>';
    $('.soft_table').append(add);
});
var skill_id=1;
$('.btn-add-skill').click(function(){
    skill_id++;
    var add='<tr>'
               +'<td>'
               +     '<select name="skill_name['+skill_id+']" class="form-control">'
               +         '<option value="Call Center - Cold Calling">Call Center - Cold Calling</option>'
               +         '<option value="Call Center - Customer Service (General/Other)">Call Center - Customer Service (General/Other)</option>'
               +         '<option value="Call Center - Escalations Desk" selected="selected">Call Center - Escalations Desk</option>'
               +         '<option value="Call Center - Tech Support">Call Center - Tech Support</option>'
               +         '<option value="Cold Calling (Non Call Center)">Cold Calling (Non Call Center)</option>'
               +         '<option value="Executive Assistant">Executive Assistant</option>'
               +         '<option value="Foreign Language - French">Foreign Language - French</option>'
               +         '<option value="Foreign Language - Spanish">Foreign Language - Spanish</option>'
               +         '<option value="Graphic Designer">Graphic Designer</option>'
               +         '<option value="Medical Record Collections">Medical Record Collections</option>'
               +         '<option value="Tech Support (Non Call Center)">Tech Support (Non Call Center)</option>'
               +         '<option value="Other/General">Other/General</option>'
               +     '</select>'
               + '</td>'
               + '<td>'
               +     '<select name="skill_industry['+skill_id+']" class="form-control">'
               +         '<option value="Banking">Banking</option>'
               +         '<option value="High Tech">High Tech</option>'
               +         '<option value="Insurance">Insurance</option>'
               +         '<option value="Real Estate" selected="selected">Real Estate</option>'
               +         '<option value="Telecom">Telecom</option>'
               +         '<option value="Other">Other</option>'
               +     '</select>'
               + '</td>'
               + '<td>'
               +     '<select name="skill_years['+skill_id+']" class="form-control">'
               +         '<option value="0">0</option>'
               +         '<option value="1">1</option>'
               +         '<option value="2">2</option>'
               +         '<option value="3">3</option>'
               +         '<option value="4">4</option>'
               +         '<option value="5">5</option>'
               +         '<option value="6">6</option>'
               +         '<option value="7">7</option>'
               +         '<option value="8">8</option>'
               +         '<option value="9">9</option>'
               +         '<option value="10">10</option>'
               +         '<option value="10+">10+</option>'
               +     '</select>'
               + '</td>'
               + '<td>'
               +     '<select name="skill_months['+skill_id+']" class="form-control">'
               +         '<option value="0">0</option>'
               +         '<option value="1">1</option>'
               +         '<option value="2">2</option>'
               +         '<option value="3">3</option>'
               +         '<option value="4">4</option>'
               +         '<option value="5">5</option>'
               +         '<option value="6">6</option>'
               +         '<option value="7">7</option>'
               +         '<option value="8">8</option>'
               +         '<option value="9">9</option>'
               +         '<option value="10">10</option>'
               +         '<option value="11">11</option>'
               +         '<option value="12">12</option>'
               +     '</select>'
               + '</td>'
               + '<td><input name="skill_note['+skill_id+']" class="form-control" type="text"></td>'
               + '<td><button type="button" class="btn btn-danger " onclick="skill_delete_list(this)"><i class="fa fa-close"></i></button></td>'
            +'</tr>';
    $('.skill_table').append(add);
});
var target_client_id=1000;
$('.btn-add-targetclient').click(function(){
    target_client_id++;
    var adder='<tr style="height:40px">'+
                '<td>'+
                    '<select name="target_client['+target_client_id+']" class="form-control">'+target_client_opt+
                    '</select>'+
                '</td>'+
                '<td>'+
                     '<button type="button" class=" btn btn-danger" onclick="delete_targetclient(this);" style="margin-left: 10px"><i class="fa fa-close"></i></button>'
                '</td>'+
            '</tr>';
    $('.target_client_table').append(adder);
});
$('.btn-add-backup-connection').click(function(){
    $('.backup_connection').val('yes');
    $('.add-backup-connection').css('display','block');
    $('.btn-add-backup-connection').css('display','none');
});
$('.btn-add-backup-computer').click(function(){
    $('.backup_computer').val('yes');
    $('.add-backup-computer').css('display','block');
    $('.btn-add-backup-computer').css('display','none');
});


$('.email').on('input',function(){
    $('.user_email').val($('.email').val());
    $('.email_veem').val($('.email').val());
});
$(":file").attr('accept', '.pdf,.jpg,.jpeg,.tiff,.tif,.doc,.xls,.docx,.xlsx');

$("input[name='video_file']").attr('accept', '.mp4');
$("input[name='video_file']").change(function() {
    if ($("input[name='video_file']").val().substr(-3)=='mp4') {
        $('.btn-save').removeAttr('disabled');
        $('.video_file_error').addClass('hidden');
    } else {
        $('.btn-save').attr('disabled', true);
        $('.video_file_error').removeClass('hidden');
    }
});

$(":file").filestyle();
$(".bootstrap-filestyle input").attr('placeholder','No file chosen.');
$(".bootstrap-filestyle input").css('text-align','center');

var target_client_opt='';
</script>
@foreach($target_client as $key=>$client)
<script type="text/javascript">
    target_client_opt+='<option value="{{$key}}">{{$client}}</option>';
</script>
@endforeach
<script type="text/javascript">
    $('button[type="submit"]').click(function(){
        if (!$('input[name="first_name"]').val() || !$('input[name="last_name"]').val() || !$('input[name="legal_name"]').val() || !$('input[name="email_main"]').val() || !$('input[name="email_veem"]').val()){
            $("#collapse2").collapse('show');
        }
        if (!$('input[name="user_email"]').val() || !$('input[name="password"]').val()){
            $("#collapse3").collapse('show');
        }
        if (!$('select[name="currency_type"]').val()){
            $("#collapse17").collapse('show');
        }
    });
    $('input[name="video_sub_link"]').keydown(function(e){
        if (e.keyCode>64 && e.keyCode<91) return;
        if (e.keyCode>96 && e.keyCode<123) return;
        if (e.keyCode>47 && e.keyCode<58) return;
        if (e.keyCode==8 || e.keyCode==46 || e.keyCode==20 || e.keyCode==16 || e.keyCode==17 || e.keyCode==46) return;
        e.preventDefault();
    });
    $('textarea[name="writing_sample"]').on('input', function(e){
        let lines=$(this).val().split(/\r\n|\r|\n/).length;
        $(this).attr('rows', lines);
    });
    $('textarea[name="work_schedule"]').on('input', function(e){
        let lines=$(this).val().split(/\r\n|\r|\n/).length;
        $(this).attr('rows', lines);
    });
    $('textarea[name="backup_plan"]').on('input', function(e){
        let lines=$(this).val().split(/\r\n|\r|\n/).length;
        $(this).attr('rows', lines);
    });
    $('.home_based_experience').change(function() {
        if ($(this).val() != 'yes') {
            $('.home_based_additional_info').addClass('hidden');
        } else {
            $('.home_based_additional_info').removeClass('hidden');
        }
    });
    $('.btn-add-other-file').click(function() {
        const id = new Date().getTime();
        const fileTable = 
            '<tr>' +
                '<td><input name="other_document_file_label['+id+']" type="text" class="form-control" value=""></td>' + 
                '<td><input name="other_document_file['+id+']" type="file"></td>' +
                '<td><button type="button" class="btn btn-danger other_file_delete_btn btn-xs" onclick="delete_other_file_list(this)"> <i class="fa fa-remove"></i></button></td>' +
            '</tr>';
        $('.other_files_table').append(fileTable);
    });
    function delete_other_file_list(e) {
        $(e).parent().parent().remove();
    }
</script>
@endsection
