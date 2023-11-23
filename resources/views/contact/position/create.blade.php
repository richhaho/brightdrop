@extends('template.template')
@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.table-dragable tr td{
    cursor: move;
}
</style>
@endsection

@section('content')

{!! Form::open(['route' => 'contact.position.store','autocomplete' => 'off']) !!}
<section id="CreatePosition">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12" >
                <h3 class="bold">Create New Position</h3>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
                <button type="submit" class="btn btn-success pull right"><i class="fa fa-save"></i> Save Position</button>
            </div>
        </div>
        @if (Session::has('message'))
            <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            </div>
        @endif
        <div class="col-md-12 col-lg-12 col-xs-12 ">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Client Name:</label>
                            {!!  Form::select('clients_id', $client_list, '', ['class' => 'form-control', 'required'=>true]) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Position Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Status:</label>
                            {!!  Form::select('status', $status, 'active', ['class' => 'form-control position-status']) !!}
                            @component('contact.position.components.inactivemodal')
                            @endcomponent
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Visible to Client:</label>
                            {!!  Form::select('visible_to_client', $visible_to_client, '', ['class' => 'form-control', 'required'=>true]) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Editable to Client:</label>
                            {!!  Form::select('editable_to_client', $editable_to_client, '', ['class' => 'form-control', 'required'=>true]) !!}
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xs-12 ">
            <div class="panel panel-default">
                <div class="panel-heading"><label>Groups</label></div>
                <div class="panel-body">
                    <div class="box-body table_group">
                        <table id="position_groups_table" class="table table-hover text-center table-bordered">
                        <thead>
                        <tr class="warning">
                            <th width="60%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="10%">Visible to BrightDrop</th>
                            <th width="10%">Visible to Client</th>
                            <th width="10%">Editable to Client</th>
                            <th width="10%">Action</th>
                        </tr>
                        </thead>
                        <tbody class="tbody-groups table-dragable">
                        @foreach ($groups as $group)
                        <tr>
                            <td><input name="group_name[{{$group['id']}}]" type="text" class="form-control" value="{{$group['name']}}" required></td>
                            <td><input name="group_visible_to_brightdrop[{{$group['id']}}]" type="checkbox" class="btn" {{$group['visible_to_brightdrop'] ? 'checked' : ''}}></td>
                            <td><input name="group_visible_to_client[{{$group['id']}}]" type="checkbox" class="btn" {{$group['visible_to_client'] ? 'checked' : ''}}></td>
                            <td><input name="group_editable_to_client[{{$group['id']}}]" type="checkbox" class="btn" {{$group['editable_to_client'] ? 'checked' : ''}}></td>
                            <td><input type="hidden" name="group_can_delete[{{$group['id']}}]" value="{{$group['can_delete']}}"><button type="button" class="btn btn-danger btn-xs" onclick="delete_group(this)" {{$group['can_delete'] ? '' : 'disabled'}}><i class="fa fa-close"></i></button></td>
                        </tr>
                        @endforeach
                        </tbody>
                        </table>
                        <button type="button" class="btn btn-warning btn-add" onclick="add_group(this)" style="margin-top: 10px"><i class="fa fa-plus"></i> Add Group</button>
                    </div>
                </div>    
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xs-12 ">
            <div class="panel panel-default">
                <div class="panel-heading"><label>Columns</label></div>
                <div class="panel-body">
                    <div class="box-body table_group">
                        <table id="position_columns_table" class="table table-hover text-center table-bordered">
                        <thead>
                        <tr class="warning">
                            <th width="60%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Column&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="10%">Visible to BrightDrop</th>
                            <th width="10%">Visible to Client</th>
                            <th width="10%">Editable to Client</th>
                            <th width="10%">Action</th>
                        </tr>
                        </thead>
                        <tbody class="tbody-columns table-dragable">
                        @foreach ($columns as $column)
                        <tr>
                            <td><input {{$column['can_delete'] ? '' : 'readonly'}} name="column_name[{{$column['id']}}]" type="text" class="form-control column_name" value="{{$column['name']}}" required></td>
                            <td><input name="column_visible_to_brightdrop[{{$column['id']}}]" type="checkbox" class="btn" {{$column['visible_to_brightdrop'] ? 'checked' : ''}} style="{{$column['can_visible_to_brightdrop'] ? '' : 'pointer-events:none;opacity: 0.6'}}"></td>
                            <td><input name="column_visible_to_client[{{$column['id']}}]" type="checkbox" class="btn" {{$column['visible_to_client'] ? 'checked' : ''}} style="{{$column['can_visible_to_client'] ? '' : 'pointer-events:none;opacity: 0.6'}}"></td>
                            <td><input name="column_editable_to_client[{{$column['id']}}]" type="checkbox" class="btn" {{$column['editable_to_client'] ? 'checked' : ''}} style="{{$column['can_editable_to_client'] ? '' : 'pointer-events:none;opacity: 0.6'}}"></td>
                            <td><input type="hidden" name="can_visible_to_brightdrop[{{$column['id']}}]" value="{{$column['can_visible_to_brightdrop']}}">
                            <input type="hidden" name="can_visible_to_client[{{$column['id']}}]" value="{{$column['can_visible_to_client']}}">
                            <input type="hidden" name="can_editable_to_client[{{$column['id']}}]" value="{{$column['can_editable_to_client']}}">
                            <input type="hidden" name="can_delete[{{$column['id']}}]" value="{{$column['can_delete']}}">
                            <input type="hidden" name="field_type[{{$column['id']}}]" value="{{$column['field_type']}}" class="field_type">
                            <input type="hidden" name="drop_down_options[{{$column['id']}}]" value="{{$column['drop_down_options']}}" class="drop_down_options">
                            <input type="hidden" name="field[{{$column['id']}}]" value="{{$column['field']}}">
                            <button type="button" class="btn btn-warning btn-xs {{$column['can_delete'] ? '' : 'hidden'}}" onclick="edit_column(this)"><i class="fa fa-edit"></i></button>&nbsp;<button type="button" class="btn btn-danger btn-xs" onclick="delete_column(this)" {{$column['can_delete'] ? '' : 'disabled'}}><i class="fa fa-close"></i></button></td>
                        </tr>
                        @endforeach
                        </tbody>
                        </table>
                        <button type="button" class="btn btn-warning btn-add" onclick="add_column(this)" style="margin-top: 10px"><i class="fa fa-plus"></i> Add Column</button>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-edit-column" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                                <div class="col-xs-12 form-group">
                                    <label>Field Type</label>
                                    {!!  Form::select('update_field_types', $field_types, 'dropdown', ['class' => 'form-control update_field_type']) !!}
                                </div>
                                <div class="col-xs-12 form-group field-type-text-group">
                                    <label>Drop-Down Options</label>
                                    <input type="text" class="form-control update_dropdown_options">
                                    <?php
                                    $worker_fields = [
                                        '' => '',
                                        'status' => 'Status',
                                        'first_name' => 'First Name',
                                        'last_name' => 'Last Name',
                                        'legal_name' => 'Legal Name',
                                        'email_main' => 'Email Main',
                                        'email_veem' => 'Email Veem',
                                        'skype'=>'Skype',
                                        'phone'=>'Phone',
                                        'country'=>'Country',
                                        'philippines_region'=>'Philippines Region',
                                        'address'=>'Address',
                                        'birthday'=>'Birthday',
                                        'gender'=>'Gender',
                                        'currency_type'=>'Currency Type',                                        
                                        'fulltime_compensation_amount'=>'Fulltime Compensation Amount',
                                        'fulltime_compensation_currency'=>'Fulltime Compensation Currency',
                                        'available_hours'=>'Available Hours',
                                        'outside_brightdrop'=>'Outside Brightdrop',
                                        'hours_outside_perweek'=>'Hours Outside Per Week',
                                        'current_nonbrightdrop_hours'=>'Current Non-Brightdrop Hours',
                                        'available_start_date'=>'Available Start Date',
                                        'video_link'=>'Video Link',
                                        'video_sub_link'=>'Video Sub Link',
                                        'writing_sample'=>'Writing Sample',
                                        'worker_source'=>'Worker Source',
                                        'english_skills'=>'English Skills',
                                        'skills'=>'Skills',
                                        'skill_other'=>'Skill Other',
                                        'software_knowledge'=>'Software Knowledge',
                                        'software_other'=>'Software Other',
                                        'internet_connection'=>'Internet Connection',
                                        'computer'=>'Computer',
                                        'work_schedule'=>'Work Schedule',
                                        'backup_plan'=>'Backup Plan',
                                        'emergency_contacts'=>'Emergency Contacts',
                                        'payments'=>'Payments',
                                        'pto_summary'=>'PTO Summary',
                                        'disqualifier_explain'=>'Disqualifier Explain',
                                        'video_file'=>'Video File',
                                        'resume_file'=>'Resume File',
                                        'internal_recruitment_manager'=>'Internal Recruitment Manager',
                                        'internal_other_employee'=>'Internal Other Employee',
                                        'Onlinelinejobs_profilelink'=>'Online Jobs Profile Link',
                                        'worker_referral'=>'Worker Referral',
                                        'worksource_other'=>'Work Source Other',
                                        'english_verbal'=>'English Verbal',
                                        'english_verbal_note'=>'English Verbal Note',
                                        'english_written'=>'English Written',
                                        'english_written_note'=>'English Written Note',
                                        'emergency_contact1_fullname'=>'Emergency Contact1 FullName',
                                        'emergency_contact1_relationship'=>'Emergency Contact1 Relationship',
                                        'emergency_contact1_email'=>'Emergency Contact1 Email',
                                        'emergency_contact1_phone'=>'Emergency Contact1 Phone',
                                        'emergency_contact1_address'=>'Emergency Contact1 Address',
                                        'emergency_contact2_fullname'=>'Emergency Contact2 Fullname',
                                        'emergency_contact2_relationship'=>'Emergency Contact2 Relationship',
                                        'emergency_contact2_email'=>'Emergency Contact2 Email',
                                        'emergency_contact2_phone'=>'Emergency Contact2 Phone',
                                        'emergency_contact2_address'=>'Emergency Contact2 Address',
                                        'internet_connection_primary'=>'Internet Connection Primary',
                                        'internet_connection_primary_other'=>'Internet Connection Primary Other',
                                        'internet_connection_primary_type'=>'Internet Connection Primary Type',
                                        'internet_connection_primary_speed'=>'Internet Connection Primary Speed',
                                        'internet_connection_primary_screenshot'=>'Internet Connection Primary Screenshot',
                                        'internet_connection_primary_data_cap'=>'Internet Connection Primary Data Cap',
                                        'backup_connection'=>'Backup Connection',
                                        'backup_connection_isp'=>'Backup Connection ISP',
                                        'backup_connection_other'=>'Backup Connection Other',
                                        'backup_connection_type'=>'Backup Connection Type',
                                        'backup_connection_speed'=>'Backup Connection Speed',
                                        'backup_connection_screenshot'=>'Backup Connection Screenshot',
                                        'backup_connection_data_cap'=>'Backup Connection Data Cap',
                                        'internet_connection_note'=>'Internet Connection Note',
                                        'primary_computer_type'=>'Primary Computer Type',
                                        'primary_computer_brand'=>'Primary Computer Brand',
                                        'primary_computer_model'=>'Primary Computer Model',
                                        'primary_computer_age'=>'Primary Computer Age',
                                        'primary_computer_system'=>'Primary Computer System',
                                        'backup_computer'=>'Backup Computer',
                                        'backup_computer_type'=>'Backup Computer Type',
                                        'backup_computer_brand'=>'Backup Computer Brand',
                                        'backup_computer_model'=>'Backup Computer Model',
                                        'backup_computer_age'=>'Backup Computer Age',
                                        'backup_computer_system'=>'Backup Computer System',
                                        'technical_computer_note'=>'Technical Computer Note',
                                        'typing_test_wpm'=>'Typing Test WPM',
                                        'typing_test_number_of_errors'=>'Typing Test Number Of Errors',
                                        'speaks_spanish'=>'Speaks Spanish',
                                        'us_business_hours'=>'US Business Hours',
                                        'home_based_experience'=>'Home Based Experience',
                                        'home_based_additional_info'=>'Home Based Additional Info',
                                        'reliable_quiet_workspace'=>'Reliable Quiet Workspace',
                                        'long_term_work_issues'=>'Long Term Work Issues',
                                        'ica'=>'ICA',
                                        'typing_test_file'=>'Typing Test File'
                                    ];
                                    ?>
                                    {!!  Form::select('update_readonly_field', $worker_fields, '', ['class' => 'form-control update_readonly_field hidden']) !!}
                                </div>
                                <div class="col-xs-12 form-group hidden">
                                    <label>Description</label>
                                    <input type="text" class="form-control update_column_name">
                                </div>
                                <div class="col-xs-12 form-group">
                                    <button class="btn btn-danger pull-right" type="button"  data-dismiss="modal"> Cancel</button>
                                    <button class="btn btn-success pull-right" type="button" data-dismiss="modal" onclick="updateColumn()"> Update</button>
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
<script src="/plugins/jQueryUI/jquery-ui.min.js"></script> 
<script>
$('.table-dragable').sortable();
$(function () {
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
    $('.position-status').change(function() {
        if ($(this).val() == 'inactive') {
            $('#modal-inactive').modal('show');
        }
    });
    $('.btn-reject-inactive').click(function() {
        $('.position-status').val('active');
    });
});
function add_group() {
    let d = Date.now();
    let newgroup= '<tr>'
                +    '<td><input name="group_name['+d+']" type="text" class="form-control" required></td>'
                +    '<td><input name="group_visible_to_brightdrop['+d+']" type="checkbox" class="btn"></td>'
                +    '<td><input name="group_visible_to_client['+d+']" type="checkbox" class="btn"></td>'
                +    '<td><input name="group_editable_to_client['+d+']" type="checkbox" class="btn"></td>'
                +    '<td><input type="hidden" name="group_can_delete['+d+']" value="1"><button type="button" class="btn btn-danger btn-xs" onclick="delete_group(this)"><i class="fa fa-close"></i></button></td>'
                +'</tr>';
    $('.tbody-groups').append(newgroup);
}

function delete_group(e) {
    $(e).parent().parent().remove();
}

function add_column() {
    let d = Date.now();
    let newcolumn='<tr>'
                +    '<td><input name="column_name['+d+']" type="text" class="form-control column_name" value="" required></td>'
                +    '<td><input name="column_visible_to_brightdrop['+d+']" type="checkbox" class="btn"></td>'
                +    '<td><input name="column_visible_to_client['+d+']" type="checkbox" class="btn"></td>'
                +    '<td><input name="column_editable_to_client['+d+']" type="checkbox" class="btn"></td>'
                +    '<td><input type="hidden" name="can_visible_to_brightdrop['+d+']" value="1">'
                +    '<input type="hidden" name="can_visible_to_client['+d+']" value="1">'
                +    '<input type="hidden" name="can_editable_to_client['+d+']" value="1">'
                +    '<input type="hidden" name="can_delete['+d+']" value="1">'
                +    '<input type="hidden" name="field_type['+d+']" value="" class="field_type">'
                +    '<input type="hidden" name="drop_down_options['+d+']" value="" class="drop_down_options">'
                +    '<input type="hidden" name="field['+d+']" value="">'
                +    '<button type="button" class="btn btn-warning btn-xs" onclick="edit_column(this)"><i class="fa fa-edit"></i></button>&nbsp;<button type="button" class="btn btn-danger btn-xs" onclick="delete_column(this)"><i class="fa fa-close"></i></button></td>'
                +'</tr>';
    $('.tbody-columns').append(newcolumn);
}
var columnElement = null;
function edit_column(e) {
    columnElement = $(e).parent().parent();
    const field_type = columnElement.find('.field_type').val();
    const dropdown_options = columnElement.find('.drop_down_options').val();
    const column_name = columnElement.find('.column_name').val();    

    $('.update_field_type').val(field_type);
    changeFieldType();
    if (field_type == 'readonly') {
        $('.update_readonly_field').val(dropdown_options);
    } else {
        $('.update_dropdown_options').val(dropdown_options);
    }
    $('.update_column_name').val(column_name);
    $('#modal-edit-column').modal('show');
}
function updateColumn() {
    const field_type = $('.update_field_type').val();
    const dropdown_options = field_type == 'readonly' ?  $('.update_readonly_field').val() : $('.update_dropdown_options').val();
    const column_name = $('.update_column_name').val();
    columnElement.find('.field_type').val(field_type);
    columnElement.find('.drop_down_options').val(dropdown_options);
    columnElement.find('.column_name').val(column_name);
}
function delete_column(e) {
    $(e).parent().parent().remove();
}
$('.update_field_type').change(function() {
    changeFieldType();
});
function changeFieldType() {
    const update_field_type = $('.update_field_type').val();
    $('.update_dropdown_options').addClass('hidden');
    $('.update_readonly_field').addClass('hidden');
    $('.field-type-text-group').removeClass('hidden');
    if (update_field_type == 'dropdown') {
        $('.field-type-text-group label').text('Drop-Down Options');
        $('.update_dropdown_options').removeClass('hidden');
    } else if (update_field_type == 'text') {
        $('.field-type-text-group label').text('Text');
        $('.update_dropdown_options').removeClass('hidden');
        $('.field-type-text-group').addClass('hidden');
    } else if (update_field_type == 'hyperlink') {
        $('.field-type-text-group label').text('Hyperlink');
        $('.update_dropdown_options').removeClass('hidden');
    } else if (update_field_type == 'readonly') {
        $('.field-type-text-group label').text('Worker Profile Field');
        $('.update_readonly_field').removeClass('hidden');
    } else {
        $('.field-type-text-group label').text('*Please choice field type above select box.');
    }
}
</script>
@endsection
