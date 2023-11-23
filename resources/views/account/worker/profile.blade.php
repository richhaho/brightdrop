@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<link href="/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="/vendor/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css">
<style>
 /*.blue-field{display:none;}*/
 .panel-body{padding: 0px 15px 0px 15px !important;}
 .clickable{pointer-events:initial;opacity: 1;}
 .none-clickable{pointer-events:none;opacity: 0.8;}
</style>
@endsection

@section('content')

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
    <h3 class="bold">Worker: {{$worker->fullname()}}'s  Profile <a class="btn btn-default hidden" data-toggle="modal" data-target="#modal-sendemail-{{$worker->id}}">Compile/Send Video Profile to Clients</a></h3>
    <?php 
        $client_emails=array();
        foreach (explode(',',$worker->target_client) as $client_id) {
            $client=\App\Clients::where('id',$client_id)->first();
            if (count($client)==0) continue;
            foreach ($client->contacts()->get() as $contact) {
                $client_emails[]=$contact->email;        
            }    
        }
        $client_emails=implode(',', $client_emails);
    ?> 
    @component('account.worker.components.emailmodal')
    @slot('id') 
        {{ $worker->id }}
    @endslot
    @slot('client_emails') 
        {{ $client_emails }}
    @endslot
    @endcomponent
</div>

{!! Form::open(['route' => 'account.worker.update','autocomplete' => 'off','class'=>'submitform','files'=>true]) !!}
<input type="hidden" name="worker_id" value="{{$worker->id}}">
    <section id="ProfileWorker">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
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
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1"><i class="fa fa-minus-square"></i> Worker Status</a></div>
                        <div class="panel-body panel-collapse collapse in" id="collapse1"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Worker Status:</label>
                                    {!!  Form::select('status',$status,$worker->status, ['class' => 'form-control changeStatus','onchange'=>'changeStatus()']) !!}
                                </div>
                                <?php 
                                $style=$worker->status=='disqualfied' ? '':'display:none';
                                ?>
                                <div class="col-md-6 form-group Disqualifier hidden" style="{{$style}}">
                                    <label>Disqualifier Explanation:</label>
                                    <input name="disqualifier_explain" class="form-control " type="text" value="{{$worker->disqualifier_explain}}" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 yellow-field">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2"><i class="fa fa-plus-square"></i> Contact Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse2"><br>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>First Name:</label>
                                    <input name="first_name" class="form-control " type="text" value="{{$worker->first_name}}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Last Name:</label>
                                    <input name="last_name" class="form-control " type="text" value="{{$worker->last_name}}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Full Legal Name:</label>
                                    <input name="legal_name" class="form-control " type="text" value="{{$worker->legal_name}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Email Address - Main:</label>
                                    <input name="email_main" class="form-control email" type="email" value="{{$worker->email_main}}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Email Address - Veem:</label>
                                    <input name="email_veem" class="form-control email_veem" type="email" value="{{$worker->email_veem}}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Skype ID:</label>
                                    <input name="skype" class="form-control " type="text" value="{{$worker->skype}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Phone Number:</label>
                                    <input name="phone" class="form-control " type="text" value="{{$worker->phone}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Physical Address:</label>
                                    <input name="address" class="form-control " type="text" value="{{$worker->address}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Country:</label>
                                    {!!  Form::select('country',$country,$worker->country, ['class' => 'form-control changeCountry','onchange'=>'changeCountry()']) !!}
                                </div>
                                <div class="col-md-3 form-group Philippines" style="display:none">
                                    <label>Philippines Region:</label>
                                    {!!  Form::select('philippines_region',$philippines_region,$worker->philippines_region, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                             
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
                                    @foreach ($special_notes as $note)
                                    <tr>
                                        <td>{{date('m/d/Y',strtotime($note->date))}}</td>
                                        <td>{{$note->inputbyname}}</td>
                                        <td><input value="{{$note->note}}" type="text" name="candiate_notes_origin[{{$note->id}}]" class="form-control" style="text-align:center" readonly></td>
                                        <td><button type="button" class="btn btn-danger contact_delete_btn btn-xs" onclick="delete_notes_list(this)"> delete</button></td>
                                    </tr>

                                    @endforeach                                    
                                    </tbody>
                                    </table>
                                </div>           
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse20" aria-expanded="true" aria-controls="collapse20"><i class="fa fa-plus-square"></i> Other Worker Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse20"><br>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Date of Birth:</label>
                                    <input name="birthday" class="form-control " type="date" value="{{$worker->birthday}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Gender:</label>
                                    {!!  Form::select('gender',$gender,$worker->gender, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Currency Type:</label>
                                    {!!  Form::select('currency_type',$currency_type,$worker->currency_type, ['class' => 'form-control', 'required'=>true]) !!}
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
                                    @if($worker->ica)
                                    <a style="pointer-events:initial;opacity: 1;" href="{{route('account.worker.download')}}?id={{$worker->id}}&filename={{$worker->ica}}"><i class="fa fa-download"></i> Download File</a>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Typing Test:</label>
                                    <input name="typing_test_file" class="form-control " type="file" >
                                    @if($worker->typing_test_file)
                                    <a style="pointer-events:initial;opacity: 1;" href="{{route('account.worker.download')}}?id={{$worker->id}}&filename={{$worker->typing_test_file}}"><i class="fa fa-download"></i> Download File</a>
                                    @endif
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
                                        @if (unserialize($worker->other_document_files))
                                        @foreach (unserialize($worker->other_document_files) as $odf)
                                        <tr>
                                            <td><input name="other_document_file_label[{{$odf['id']}}]" type="text" class="form-control" value="{{$odf['label']}}"></td>
                                            <td><a style="pointer-events:initial;opacity: 1;" href="{{route('account.worker.download')}}?id={{$worker->id}}&filename={{$odf['path'].$odf['filename']}}"><i class="fa fa-download"></i> {{$odf['filename']}}</a></td>
                                            <td><button type="button" class="btn btn-danger other_file_delete_btn btn-xs" onclick="delete_other_file_list(this)"> <i class="fa fa-remove"></i></button></td>
                                        </tr>
                                        @endforeach
                                        @endif
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
                                    <input name="user_email" class="form-control user_email" type="email" value="{{$worker->user()->email}}" required>
                                </div>
                                <div class="col-md-6 form-group" style="pointer-events:initial;opacity: 1;">
                                    <label>Password:</label><br>
                                    <a href="{{route('account.worker.resetpassword')}}?id={{$worker->id}}" class="btn btn-default"><i class="fa fa-lock"></i> Send Reset Link to Worker</a>
                                </div>
                            </div>
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
                                    {!!  Form::select('candidate_account_manager_id',$candidate_account_manager_id,$worker->candidate_account_manager_id, ['class' => 'form-control']) !!}
                                    
                                </div>
                            
                                <div class="col-md-6 form-group red-field">
                                    <label>Requested Pay:</label>
                                    <input name="fulltime_compensation_amount" class="form-control " type="text" value="{{$worker->fulltime_compensation_amount}}" min="0" step="1">
                                </div>
                                <div class="col-md-6 form-group hidden">
                                    <label>Requested Pay – Currency Type:</label>
                                    {!!  Form::select('fulltime_compensation_currency',$fulltime_compensation_currency,$worker->fulltime_compensation_currency, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row red-field">
                                <div class="col-md-6 form-group">
                                    <label>Hours Available:</label>
                                    {!!  Form::select('available_hours',$available_hours,$worker->available_hours, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Currently employed outside BrightDrop:</label>
                                    {!!  Form::select('outside_brightdrop',$outside_brightdrop,$worker->outside_brightdrop, ['class' => 'form-control outsideBrightDrop','onchange'=>'outsideBrightDrop()']) !!}
                                </div>
                                <?php $style='';if($worker->outside_brightdrop=='no') $style='display:none'; ?>
                                <div class="col-md-6 form-group nonBrightDrop" style="{{$style}}">
                                    <label>Hours per week outside BrightDrop:</label>
                                    {!!  Form::select('hours_outside_perweek',$hours_outside_perweek,$worker->hours_outside_perweek, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group nonBrightDrop" style="{{$style}}">
                                    <label> Current Non-BrightDrop Work Hours:</label>
                                    <input name="current_nonbrightdrop_hours" class="form-control " type="text" value="{{$worker->current_nonbrightdrop_hours}}"> 
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
                                    <?php $t=0; ?>
                                    @foreach(explode(",",$worker->target_client) as $target)
                                    @if ($target)
                                    <?php $t++;?>
                                    <tr style="height:40px">
                                        <td>
                                            {!!  Form::select('target_client['.$t.']',$target_client,$target, ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                             <button type="button" class=" btn btn-danger" onclick="delete_targetclient(this);" style="margin-left: 10px"><i class="fa fa-close"></i></button>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                                                          
                                    
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
                                    <input name="available_start_date" class="form-control" type="text" value="{{$worker->available_start_date}}" data-toggle="tooltip" data-placement="top" title='This is a text field to allow you to input an exact date or a phrase, such as "in 30 days" or "ASAP".'> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group red-field">
                                    <label>Typing Test – WPM:</label>
                                    <input type="text" name="typing_test_wpm" class="form-control" value="{{$worker->typing_test_wpm}}">
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Typing Test – Number of Errors:</label>
                                    <input type="text" name="typing_test_number_of_errors" class="form-control" value="{{$worker->typing_test_number_of_errors}}">
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Speaks Spanish:</label>
                                    {!!  Form::select('speaks_spanish',[''=>'', 'yes'=>'Yes', 'no'=>'No'],$worker->speaks_spanish, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Ok with US Business Hours:</label>
                                    {!!  Form::select('us_business_hours',[''=>'', 'yes'=>'Yes', 'no'=>'No'],$worker->us_business_hours, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group red-field">
                                    <label>Home-Based Experience:</label>
                                    {!!  Form::select('home_based_experience',[''=>'', 'yes'=>'Yes', 'no'=>'No'],$worker->home_based_experience, ['class' => 'form-control home_based_experience']) !!}
                                </div>
                                <div class="col-md-6 form-group red-field home_based_additional_info {{$worker->home_based_experience != 'yes' ? 'hidden' : ''}}">
                                    <label>Home-Based – Additional Info:</label>
                                    <textarea name="home_based_additional_info" rows="1" class="form-control">{{$worker->home_based_additional_info}}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group red-field">
                                    <label>Reliable Quiet Workspace:</label>
                                    {!!  Form::select('reliable_quiet_workspace',[''=>'', 'yes'=>'Yes', 'no'=>'No'],$worker->reliable_quiet_workspace, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Long-term work issues:</label>
                                    <textarea name="long_term_work_issues" rows="1" class="form-control">{{$worker->long_term_work_issues}}</textarea>
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
                                    <textarea name="writing_sample" class="form-control" rows='{{substr_count($worker->writing_sample,"\n")+1}}'>{{$worker->writing_sample}}</textarea> 
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Link Sub Field:</label>
                                    <input name="video_sub_link" class="form-control " type="text" value="{{$worker->video_sub_link}}" >
                                </div>
                                <div class="col-md-6 form-group clickable">
                                    <label>Video Link:</label><br>
                                    @if ($worker->video_link)
                                    <a style="pointer-events:initial;opacity: 1;" href="{{$worker->video_link}}" target="_blank">
                                        {{$worker->video_link}}
                                    </a>
                                    @else
                                    <p>*This field will be automatically populated after a video is submitted and a unique link sub field is given.*</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group red-field">
                                    <label>Original Video File:</label>
                                    <input name="video_file" class="form-control " type="file" >
                                    <p class="video_file_error hidden text-danger">* Mp4 format required.</p>
                                    @if($worker->video_file)
                                    <a style="pointer-events:initial;opacity: 1;" href="{{route('account.worker.download')}}?id={{$worker->id}}&filename={{$worker->video_file}}&type=workers_video" class="clickable"><i class="fa fa-download"></i> Download Video</a>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group red-field">
                                    <label>Resume:</label>
                                    <input name="resume_file" class="form-control " type="file" >
                                    @if($worker->resume_file)
                                    <a style="pointer-events:initial;opacity: 1;" href="{{route('account.worker.download')}}?id={{$worker->id}}&filename={{$worker->resume_file}}"><i class="fa fa-download"></i> Download File</a>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group red-field">
                                    <label>Temp - Video Link:</label>
                                    <input name="temp_video_link" class="form-control" type="text" value="{{$worker->temp_video_link}}">
                                </div>
                            </div>
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
                                    @if($worker->goverment_id)
                                    <a style="pointer-events:initial;opacity: 1;" href="{{route('account.worker.download')}}?id={{$worker->id}}&filename={{$worker->goverment_id}}"><i class="fa fa-download"></i> Download File</a>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group blue-field">
                                    <label>Copy of NBI Clearance:</label>
                                    <input name="NBI" class="form-control " type="file" >
                                    @if($worker->NBI)
                                    <a style="pointer-events:initial;opacity: 1;" href="{{route('account.worker.download')}}?id={{$worker->id}}&filename={{$worker->NBI}}"><i class="fa fa-download"></i> Download File</a>
                                    @endif

                                </div>
                            </div>
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
                                    {!!  Form::select('worker_source',$worker_source,$worker->worker_source, ['class' => 'form-control changeWorkerSource','onchange'=>'changeWorkerSource()']) !!}
                                    
                                </div>
                                <?php 
                                switch ($worker->worker_source) {
                                    case 'internal_recruitment_manager':
                                        $style_internal_recruitment='';
                                        $style_internal_other='display:none';
                                        $style_onlinejob='display:none';
                                        $style_referral='display:none';
                                        $style_other='display:none';
                                        break;
                                    case 'internal_other':
                                        $style_internal_recruitment='display:none';
                                        $style_internal_other='';
                                        $style_onlinejob='display:none';
                                        $style_referral='display:none';
                                        $style_other='display:none';
                                        break;
                                    case 'onlinejob.ph':
                                        $style_internal_recruitment='display:none';
                                        $style_internal_other='display:none';
                                        $style_onlinejob='';
                                        $style_referral='display:none';
                                        $style_other='display:none'; 
                                        break;
                                    case 'worker referral':
                                        $style_internal_recruitment='display:none';
                                        $style_internal_other='display:none';
                                        $style_onlinejob='display:none';
                                        $style_referral='';
                                        $style_other='display:none'; 
                                        break;
                                    case 'other':
                                        $style_internal_recruitment='display:none';
                                        $style_internal_other='display:none';
                                        $style_onlinejob='display:none';
                                        $style_referral='display:none';
                                        $style_other=''; 
                                        break;
                                    default:
                                        $style_internal_recruitment='display:none';
                                        $style_internal_other='display:none';
                                        $style_onlinejob='display:none';
                                        $style_referral='display:none';
                                        $style_other='display:none'; 
                                        break;
                                }
                                ?>
                                <div class="col-md-6 form-group WorkerSource2" style="{{$style_internal_recruitment}}">
                                    <label>Internal - Recruitment Manager:</label>
                                    {!!  Form::select('internal_recruitment_manager',$internal_recruitment_manager,$worker->internal_recruitment_manager, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group WorkerSource3" style="{{$style_internal_other}}">
                                    <label>Internal - Other Employee:</label>
                                    {!!  Form::select('internal_other_employee',$internal_other_employee,$worker->internal_other_employee, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group WorkerSource4" style="{{$style_onlinejob}}">
                                    <label>Onlinelinejobs.ph - Profile Link:</label>
                                    <input name="Onlinelinejobs_profilelink" class="form-control " type="text" value="{{$worker->Onlinelinejobs_profilelink}}">
                                </div>
                                <div class="col-md-6 form-group WorkerSource5" style="{{$style_referral}}">
                                    <label>Worker Referral:</label>
                                    {!!  Form::select('worker_referral',$worker_referral,$worker->worker_referral, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group WorkerSource7" style="{{$style_other}}">
                                    <label>Other:</label>
                                    <input name="worksource_other" class="form-control " type="text" value="{{$worker->worksource_other}}">
                                </div>
                            </div>
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
                                        {!!  Form::select('english_verbal',$english_verbal,$worker->english_verbal, ['class' => 'form-control']) !!}
                                        </td>
                                        <td><input class="form-control" type="text" name="english_verbal_note" value="{{$worker->english_verbal_note}}"></td>
                                    </tr>
                                    <tr>
                                        <td>Written</td>
                                        <td>
                                        {!!  Form::select('english_written',$english_written,$worker->english_written, ['class' => 'form-control']) !!}
                                        </td>
                                        <td><input class="form-control" type="text" name="english_written_note" value="{{$worker->english_written_note}}"></td>
                                    </tr>
                                    </tbody>
                                    </table>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 red-field">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse9" aria-expanded="true" aria-controls="collapse9"><i class="fa fa-plus-square"></i> Skills Assessment</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse9"><br>
                            <div class="row">
                                <div class="box-body table_group clickable" style="overflow-x: scroll;">
                                    <table id="detail_table" class="table text-center none-clickable">
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
                                     
                                    @if (unserialize($worker->skills))
                                    @foreach (unserialize($worker->skills) as $skill)
                                    <tr>
                                        <td>
                                            {!!  Form::select('skill_name['.$skill["id"].']',$skill_name,$skill["skill_name"], ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                             
                                             {!!  Form::select('skill_industry['.$skill["id"].']',$skill_industry,$skill["skill_industry"], ['class' => 'form-control']) !!}
                                        </td>

                                        <td>
                                            {!!  Form::select('skill_years['.$skill["id"].']',$skill_years,$skill["skill_years"], ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                            {!!  Form::select('skill_months['.$skill["id"].']',$skill_months,$skill["skill_months"], ['class' => 'form-control']) !!}
                                        </td>
                                        <td><input class="form-control" type="text" name="skill_note[{{$skill['id']}}]" value="{{$skill['skill_note']}}"></td>
                                        <td><button type="button" class="btn btn-danger " onclick="skill_delete_list(this)"><i class="fa fa-close"></i></button></td>
                                    </tr>
                                    @endforeach 
                                    @endif
                                    </tbody>
                                    </table>
                                </div> 
                                <div class="col-md-12" style="height: 25px">
                                    <button type="button" class="btn btn-success pull-right btn-add-skill"><i class="fa fa-plus"></i> Add more</button>
                                </div>  
                                <div class="col-md-12">
                                    <label>Additional Notes - Skills Assessment:</label>
                                    <input name="skill_other" class="form-control " type="text" value="{{$worker->skill_other}}">
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
                                <div class="box-body table_group clickable" style="overflow-x: scroll;">
                                    <table id="detail_table" class="table text-center none-clickable">
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
                                    @if (unserialize($worker->software_knowledge))
                                    @foreach (unserialize($worker->software_knowledge) as $software) 
                                    <tr>
                                        <td>
                                            {!!  Form::select('software_name['.$software["id"].']',$software_name,$software["software_name"], ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                            {!!  Form::select('software_industry['.$software["id"].']',$software_industry,$software["software_industry"], ['class' => 'form-control']) !!}
                                        </td>

                                        <td>
                                            {!!  Form::select('software_years['.$software["id"].']',$software_years,$software["software_years"], ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                            {!!  Form::select('software_months['.$software["id"].']',$software_months,$software["software_months"], ['class' => 'form-control']) !!}
                                        </td>
                                        <td><input class="form-control" type="text" name="software_note[{{$software['id']}}]" value="{{$software['software_note']}}"></td>
                                        <td><button type="button" class="btn btn-danger" onclick="software_delete_list(this)"><i class="fa fa-close"></i></button></td></td>
                                    </tr>
                                    @endforeach 
                                    @endif 
                                    </tbody>
                                    </table>
                                </div> 
                                <div class="col-md-12" style="height: 25px">
                                    <button type="button" class="btn btn-success pull-right btn-add-software"><i class="fa fa-plus"></i> Add more</button>
                                </div> 
                                <div class="col-md-12">
                                    <label>Additional Notes - Software Knowledge:</label>
                                    <input name="software_other" class="form-control " type="text" value="{{$worker->software_other}}">
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
                                    {!!  Form::select('internet_connection_primary',$internet_connection_primary,$worker->internet_connection_primary, ['class' => 'form-control changePrimaryConnection','onchange'=>'changePrimaryConnection()']) !!}
                                     
                                </div>
                                <div class="col-md-6 form-group PrimaryConnection" style="display:none">
                                    <label>Other:</label>
                                    <input name="internet_connection_primary_other" class="form-control " type="text" value="{{$worker->internet_connection_primary_other}}">
                                </div>
                            </div>
                            <div class="row yellow-field">
                                <div class="col-md-6 form-group">
                                    <label>Primary Connection - Type:</label>
                                    {!!  Form::select('internet_connection_primary_type',$internet_connection_primary_type,$worker->internet_connection_primary_type, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group ">
                                    <label>Primary Connection - Speed:</label>
                                    <input name="internet_connection_primary_speed" class="form-control " type="number" value="{{$worker->internet_connection_primary_speed}}">
                                </div> 
                                <div class="col-md-6 form-group ">
                                    <label>Speed Test – Screenshot:</label>
                                    <input name="internet_connection_primary_screenshot" class="form-control " type="file" >
                                    @if($worker->internet_connection_primary_screenshot)
                                    <a style="pointer-events:initial;opacity: 1;" href="{{route('account.worker.download')}}?id={{$worker->id}}&filename={{$worker->internet_connection_primary_screenshot}}"><i class="fa fa-download"></i> Download File</a>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group ">
                                    <label>Data Cap:</label>
                                    <input name="internet_connection_primary_data_cap" class="form-control " type="text" value="{{$worker->internet_connection_primary_data_cap}}">
                                </div>      
                            </div>
                            <div class="row yellow-field">
                                <div class="col-md-6 form-group" style="display:none">
                                    <label>Backup Connection:</label>
                                    {!!  Form::select('backup_connection',$backup_connection,$worker->backup_connection, ['class' => 'form-control backup_connection']) !!}
                                </div>
                                @if($worker->backup_connection!='yes')
                                <div class="col-md-12 form-group">
                                    <button class="pull-right btn btn-success btn-add-backup-connection" type="button"> Add Backup Connection</button>
                                </div>
                                @endif
                                <?php 
                                $style=$worker->backup_connection!='yes' ? 'display:none':'';
                                ?>
                            </div>
                            <div class="row yellow-field add-backup-connection" style="{{$style}}">
                                <div class="col-md-6 form-group">
                                    <label>Backup Connection - ISP:</label>
                                    {!!  Form::select('backup_connection_isp',$backup_connection_isp,$worker->backup_connection_isp, ['class' => 'form-control changeBackupConnection','onchange'=>'changeBackupConnection()']) !!}
                                     
                                </div>
                                <div class="col-md-6 form-group BackupConnection" style="display:none">
                                    <label>Other:</label>
                                    <input name="backup_connection_other" class="form-control " type="text" value="{{$worker->backup_connection_other}}">
                                </div>
                            </div>
                            <div class="row yellow-field add-backup-connection" style="{{$style}}">
                                <div class="col-md-6 form-group">
                                    <label>Backup Connection - Type:</label>
                                    {!!  Form::select('backup_connection_type',$backup_connection_type,$worker->backup_connection_type, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group ">
                                    <label>Backup Connection - Speed:</label>
                                    <input name="backup_connection_speed" class="form-control " type="number" value="{{$worker->backup_connection_speed}}">
                                </div> 
                                <div class="col-md-6 form-group ">
                                    <label>Speed Test – Screenshot:</label>
                                    <input name="backup_connection_screenshot" class="form-control " type="file" >
                                    @if($worker->backup_connection_screenshot)
                                    <a style="pointer-events:initial;opacity: 1;" href="{{route('account.worker.download')}}?id={{$worker->id}}&filename={{$worker->backup_connection_screenshot}}"><i class="fa fa-download"></i> Download File</a>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group ">
                                    <label>Data Cap:</label>
                                    <input name="backup_connection_data_cap" class="form-control " type="text" value="{{$worker->backup_connection_data_cap}}">
                                </div>      
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Additional Notes - Internet Connection:</label>
                                    <input name="internet_connection_note" class="form-control " type="text" value="{{$worker->internet_connection_note}}">
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
                                    {!!  Form::select('primary_computer_type',$primary_computer_type,$worker->primary_computer_type, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4 form-group ">
                                    <label>Primary Computer - Brand:</label>
                                    <input name="primary_computer_brand" class="form-control " type="text" value="{{$worker->primary_computer_brand}}">
                                </div> 
                                <div class="col-md-4 form-group ">
                                    <label>Primary Computer - Model:</label>
                                    <input name="primary_computer_model" class="form-control " type="text" value="{{$worker->primary_computer_model}}">
                                </div> 
                                <div class="col-md-6 form-group">
                                    <label>Primary Computer - Age (Years):</label>
                                    {!!  Form::select('primary_computer_age',$primary_computer_age,$worker->primary_computer_age, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Primary Computer - Operating System:</label>
                                    {!!  Form::select('primary_computer_system',$primary_computer_system,$worker->primary_computer_system, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="row yellow-field">
                                <div class="col-md-6 form-group" style="display: none;">
                                    <label>Backup Computer:</label>
                                    {!!  Form::select('backup_computer',$backup_computer,$worker->backup_computer, ['class' => 'form-control backup_computer']) !!}
                                </div>
                                @if($worker->backup_computer!='yes')
                                <div class="col-md-12 form-group">
                                    <button class="pull-right btn btn-success btn-add-backup-computer" type="button"> Add Backup Computer</button>
                                </div>
                                @endif
                                <?php 
                                $style=$worker->backup_computer!='yes' ? 'display:none':'';
                                ?>
                            </div>
                            <div class="row yellow-field add-backup-computer" style="{{$style}}">
                                <div class="col-md-4 form-group">
                                    <label>Backup Computer - Type:</label>
                                    {!!  Form::select('backup_computer_type',$backup_computer_type,$worker->backup_computer_type, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4 form-group ">
                                    <label>Backup Computer - Brand:</label>
                                    <input name="backup_computer_brand" class="form-control " type="text" value="{{$worker->backup_computer_brand}}">
                                </div> 
                                <div class="col-md-4 form-group ">
                                    <label>Backup Computer - Model:</label>
                                    <input name="backup_computer_model" class="form-control " type="text" value="{{$worker->backup_computer_model}}">
                                </div> 
                                <div class="col-md-6 form-group">
                                    <label>Backup Computer - Age(Years):</label>
                                    {!!  Form::select('backup_computer_age',$backup_computer_age,$worker->backup_computer_age, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Backup Computer - Operating System:</label>
                                    {!!  Form::select('backup_computer_system',$backup_computer_system,$worker->backup_computer_system, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Additional Notes - Computer:</label>
                                    <input name="technical_computer_note" class="form-control " type="text" value="{{$worker->technical_computer_note}}">
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div> 
                
                <div class="col-xs-12">
                    <h4>- Hired Information</h4>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse14" aria-expanded="true" aria-controls="collapse14"><i class="fa fa-plus-square"></i> Client Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse14"><br>
                            <div class="row">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="5%">Client Name</th>
                                        <th width="10%">Target Hours Per Week</th>
                                        <th width="10%">Client - Billable Rate - Regular</th>
                                        <th width="10%">Client - Billable Rate - Overtime</th>
                                        <th width="15%">Worker - Hourly Pay Rate - Regular</th>
                                        <th width="15%">Worker - Hourly Pay Rate - Overtime</th>    
                                        <th width="5%">Currency Type</th>
                                        <th width="15%">PTO Days - Full Calendar Year</th>
                                        <th width="15%">PTO Days - Current Calendar Year</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($clients as $client) 
                                    <tr>
                                        <?php 
                                        $clientinfo=\App\ClientInfoWorkers::where('clients_id',$client->id)->where('workers_id',$worker->id)->first();
                                        ?>
                                        <td>{{$client->client_name}}</td>
                                        <td>{{isset($clientinfo->target_hours_week) ? $clientinfo->target_hours_week:''}}</td>
                                        <td>{{isset($clientinfo->client_billable_rate_regular) ? $clientinfo->client_billable_rate_regular:''}}</td>
                                        <td>{{isset($clientinfo->client_billable_rate_overtime) ? $clientinfo->client_billable_rate_overtime:''}}</td>
                                        <td>{{isset($clientinfo->worker_pay_houly_rate_regular) ? $clientinfo->worker_pay_houly_rate_regular:''}}</td>
                                        <td>{{isset($clientinfo->worker_pay_houly_rate_overtime) ? $clientinfo->worker_pay_houly_rate_overtime:''}}</td>
                                        <td>{{isset($clientinfo->currency_type) ? $clientinfo->currency_type:''}}</td>
                                        <td>{{isset($clientinfo->ptodays_full_calendar) ? $clientinfo->ptodays_full_calendar:''}}</td>
                                        <td>{{isset($clientinfo->ptodays_current_calendar) ? $clientinfo->ptodays_current_calendar:''}}</td>
                                    </tr>
                                    @endforeach 
                                    

                                    </tbody>
                                    </table>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 blue-field">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse15" aria-expanded="true" aria-controls="collapse15"><i class="fa fa-plus-square"></i> Work Schedule</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse15"><br>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Current work schedule:</label>
                                    <textarea name="work_schedule" class="form-control" rows='{{substr_count($worker->work_schedule,"\n")+1}}'>{{$worker->work_schedule}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 yellow-field">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse16" aria-expanded="true" aria-controls="collapse16"><i class="fa fa-plus-square"></i> Backup Plan</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse16"><br>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Backup Plan:</label>
                                    <textarea name="backup_plan" class="form-control" rows='{{substr_count($worker->backup_plan,"\n")+1}}'>{{$worker->backup_plan}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 yellow-field">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse17" aria-expanded="true" aria-controls="collapse17"><i class="fa fa-plus-square"></i> Emergency Contact Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse17"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 1 - Full Name:</label>
                                    <input name="emergency_contact1_fullname" class="form-control " type="text" value="{{$worker->emergency_contact1_fullname}}">
                                </div>
                            
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 1 - Relationship:</label>
                                    <input name="emergency_contact1_relationship" class="form-control " type="text" value="{{$worker->emergency_contact1_relationship}}">
                                </div>
                           
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 1 - Email Address:</label>
                                    <input name="emergency_contact1_email" class="form-control " type="text" value="{{$worker->emergency_contact1_email}}">
                                </div>
                            
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 1 - Phone Number:</label>
                                    <input name="emergency_contact1_phone" class="form-control " type="text" value="{{$worker->emergency_contact1_phone}}">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Emergency Contact - 1 - Full Address:</label>
                                    <input name="emergency_contact1_address"   class="form-control " type="text" value="{{$worker->emergency_contact1_address}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 2 - Full Name:</label>
                                    <input name="emergency_contact2_fullname" class="form-control " type="text" value="{{$worker->emergency_contact2_fullname}}">
                                </div>
                            
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 2 - Relationship:</label>
                                    <input name="emergency_contact2_relationship" class="form-control " type="text" value="{{$worker->emergency_contact2_relationship}}">
                                </div>
                           
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 2 - Email Address:</label>
                                    <input name="emergency_contact2_email" class="form-control " type="text" value="{{$worker->emergency_contact2_email}}">
                                </div>
                            
                                <div class="col-md-6 form-group">
                                    <label>Emergency Contact - 2 - Phone Number:</label>
                                    <input name="emergency_contact2_phone" class="form-control " type="text" value="{{$worker->emergency_contact2_phone}}">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Emergency Contact - 2 - Full Address:</label>
                                    <input name="emergency_contact2_address"  class="form-control " type="text" value="{{$worker->emergency_contact2_address}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse18" aria-expanded="true" aria-controls="collapse18"><i class="fa fa-plus-square"></i> Past Payment Summaries</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse18"><br>
                            <div class="row">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="30%">Payment Cycle End Date</th>
                                        <th width="40%">Link</th>
                                        <th width="30%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($worker->payments()->orderBy('date_queue','desc')->get() as $payment)
                                    <tr>
                                        <td>{{date('m/d/y',strtotime($payment->date_queue))}}</td>
                                        <td><a style="pointer-events:initial;opacity: 1;" href="{{route('account.worker.downloadpdf',$payment->id)}}">Download Payment Summary</a></td>
                                        <td>{{$payment->status}}</td>
                                    </tr>
                                    @endforeach
                                    
                                    </tbody>

                                    </table>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                @if(count($pto_summaries)>0)
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse19" aria-expanded="true" aria-controls="collapse19"><i class="fa fa-plus-square"></i> PTO Summaries</a>
                        </div>
                        <div class="panel-body panel-collapse collapse out" id="collapse19"><br>
                            @foreach ($pto_summaries as $pto_summary)
                            <div class="col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading center" style="background-color: white">{{$pto_summary['title']}}</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-3 form-group">
                                                <label>PTO Remaining:</label>
                                            </div>
                                            <div class="col-md-9 form-group">
                                                <label>{{$pto_summary['pto_remaining']}}</label>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-3 form-group">
                                                <label>PTO Used:</label>
                                            </div>
                                            <div class="col-md-9 form-group">
                                                @foreach ($pto_summary['pto_used'] as $pto_used)
                                                <div class="row">
                                                <div class="col-md-6 form-group">
                                                        <label>{{date('m/d/y',strtotime($pto_used['date']))}}</label>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label>{{$pto_used['hours']}} Hours</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                
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
var note_id=100;
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

    var adder="{{Auth::user()->accountmanager()->fullname()}}";

    var add='<tr>'
            +    '<td>'+today+'</td>'
            +    '<td>'+adder+'</td>'
            +    '<td><input value="'+note+'" type="text" name="candiate_notes['+note_id+']" class="form-control" style="text-align:center" readonly></td>'
            +    '<td><button type="button" class="btn btn-danger contact_delete_btn btn-xs" onclick="delete_notes_list(this)"> delete</button></td>'
            +'</tr>';

    $('.notes_table').append(add);
    $('.special_candiate_notes').val('');
   
    
});
var soft_id=100;
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
var skill_id=100;
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

$('.panel-body').css('pointer-events','none');
$('.panel-body').css('opacity','0.8');
$('.btn-edit').click(function(){
    $('.panel-body').css('pointer-events','auto');
    $('.panel-body').css('opacity','1');
    $('.btn-save').removeAttr('disabled');
    $('.none-clickable').removeClass('none-clickable');
});
$('.btn-save').click(function(){
    //$('.btn-save').attr('disabled',true);
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
function close_client_email(id){
    $('.clients_emails .'+id).remove();
}
$('#new_client_email').keydown(function(){
    $('.validation_error_weekly').css('display','none');
});
$('.add_client_email').click(function(){
    var email=$('#new_client_email').val();
    if (!echeck(email)) {$('.validation_error_weekly').css('display','block');return};
    var nm=parseInt($('.clients_emails div').last().attr('id'));
    if ($('.clients_emails div').last().attr('id')==null) nm=0; 
    nm=nm+1;
    var new_element='<div class="client_email '+nm+'" id="'+nm+'">'+email+'<input type="hidden" value="'+email+'" name="client_email['+nm+']" />                                            &nbsp;&nbsp;<a class="close_client_email" onclick="close_client_email('+nm+')"><i class="fa fa-close"></i></a>  </div>'
    $('.clients_emails').append(new_element);
    $('#new_client_email').val("");
});


function echeck(str) {

        var at="@"
        var dot="."
        var lat=str.indexOf(at)
        var lstr=str.length
        var ldot=str.indexOf(dot)
        if (str.indexOf(at)==-1){
           //alert("Invalid E-mail ID")
           return false
        }

        if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
           //alert("Invalid E-mail ID")
           return false
        }

        if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
            //alert("Invalid E-mail ID")
            return false
        }

         if (str.indexOf(at,(lat+1))!=-1){
            //alert("Invalid E-mail ID")
            return false
         }

         if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
            //alert("Invalid E-mail ID")
            return false
         }

         if (str.indexOf(dot,(lat+2))==-1){
            //alert("Invalid E-mail ID")
            return false
         }
        
         if (str.indexOf(" ")!=-1){
            //alert("Invalid E-mail ID")
            return false
         }

         return true                    
    }   
</script>
<script type="text/javascript">
    $('button[type="submit"]').click(function(){
        if (!$('input[name="first_name"]').val() || !$('input[name="last_name"]').val() || !$('input[name="legal_name"]').val() || !$('input[name="email_main"]').val() || !$('input[name="email_veem"]').val()){
            $("#collapse2").collapse('show');
        }
        if (!$('input[name="user_email"]').val()){
            $("#collapse3").collapse('show');
        }
        if (!$('select[name="currency_type"]').val()){
            $("#collapse20").collapse('show');
        }
    });
    $('textarea').change(function(){
        var str=$(this).val();
        var first_char=str.substr(0,1);
        var mid_char=str.substr(1,str.length-2);
        var last_char=str.substr(str.length-1,1);
        if (first_char==" ") first_char="&nbsp;";
        if (last_char==" ") last_char="&nbsp;";
        str=first_char+mid_char+last_char;
        $(this).val(str);
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
