@extends('template.template')

@section('content-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important} 
.note_by{
    color: blue
}
</style>
@endsection
@section('content')
<section id="HiredCompleted">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3 class="bold">Hired - Completed</h3>
        </div>
        @if (Session::has('message'))
            <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            </div>
        @endif
        <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="row" style="padding: 10px 0px 40px 0px">
                <div class="box-body table_group" style="overflow-x:scroll">
                    <table id="detail_table" class="table table-hover text-center table-bordered">
                        <thead>
                        <tr>
                            <th>Account Manager</th>
                            <th>Worker Name</th>
                            <th>Client</th>
                            <th>Email Address</th>
                            <th>Rehire</th>
                            <th>Available for Additional Work</th>
                            <th>Client-Hourly Rate(USD)-Final</th>
                            <th>Worker-Monthly Rate-Final</th>
                            <th>Worker-Hourly Rate-Final</th>
                            <th>Worker-Currency Type</th>
                            <th>PTO</th>
                            <th>Paid Holidays</th>
                            <th>Expected Start Date</th>
                            <th>ICA</th>
                            <th>Special Notes</th>
                            <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody class="tbody-hired">
                        @foreach($hireds as $hired)
                        <tr>
                            <td>{{$accountManagers[$hired->account_managers_id]}}</td>
                            <td>{{$workers[$hired->workers_id]}}</td>
                            <td>{{$clients[$hired->clients_id]}}</td>
                            <td>{{$hired->email}}</td>
                            <td>{{ucfirst($hired->rehire)}}</td>
                            <td>{{ucfirst($hired->available_additional_work)}}</td>
                            <td>{{$hired->client_hourly_rate_usd}}</td>
                            <td>{{$hired->worker_monthly_rate}}</td>
                            <td>{{$hired->worker_hourly_rate}}</td>
                            <td>{{$hired->worker_currency_type}}</td>
                            <td>{{ucfirst($hired->pto)}}</td>
                            <td>{{ucfirst($hired->paid_holidays)}}</td>
                            <td>{{$hired->expected_start_date}}</td>
                            <td><a class="btn ica" onclick="showUploadModal(this, '{{$hired->ica}}')">Upload</a></td>
                            <td><a class="btn special_notes" onclick="showAddNoteModal(this, '{{$hired->special_notes}}')">View Notes</a></td>
                            <td>
                                <input type="hidden" class="hired_id" value="{{$hired->id}}">
                                <button type="button" class="btn btn-danger btn-xs" onclick="showDeleteHiredModal(this)"><i class="fa fa-close"></i></button>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal fade" id="modal-finalize" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Confirmation to finalize</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to finalize?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" onclick="finalize()"> Yes</button>&nbsp;&nbsp;
                            <button class="btn btn-danger" type="button" data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-delete-hired" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Remove Hired</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to remove?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" onclick="deleteHired()"> Remove</button>&nbsp;&nbsp;
                            <button class="btn btn-danger" type="button" data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-add-note" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">View Note</h4>
                        </div>
                        <div class="modal-body" style="height: 400px;">
                            <div class="row" style="height: 340px; overflow-y: scroll">
                                <div class="col-xs-12 form-group notes_list">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <input type="text" class="text-add-note form-control">
                                </div>
                                <div class="col-xs-2 form-group">
                                    <button class="pull-right btn btn-primary" onclick="addNote(this)"> + Add</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <button class="btn btn-danger pull-right" type="button"  data-dismiss="modal"> Close</button>
                                    <button class="btn btn-success pull-right hidden" type="button" onclick="saveNote()"> Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-upload-ica" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Upload ICA</h4>
                        </div>
                        <div class="modal-body" style="height: 90px;">
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <input name="ica" class="form-control upload_ica" type="file" >
                                    <a class="download_ica hidden" href=""><i class="fa fa-download"></i> Download File</a>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <button class="btn btn-danger pull-right" type="button"  data-dismiss="modal"> Cancel</button>
                                    <button class="btn btn-success pull-right" type="button" onclick="saveUpload()"> Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/vendor/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
<script>

$(function () {
    $('#detail_table').DataTable();
    $('[data-toggle="tooltip"]').tooltip(); 
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
});
// $(":file").filestyle();

// =========== Delete ==========
var deleteElement = null;
function showDeleteHiredModal(e) {
    deleteElement = $(e).parent().parent();
    $('#modal-delete-hired').modal('show');
}
function deleteHired() {
    if (!deleteElement) return;
    const hired_id = deleteElement.find('.hired_id').val();
    if (!hired_id) {
        deleteElement.remove();
        deleteElement=null;
        $('#modal-delete-hired').modal('hide');
        return;
    }
    $.ajax({
        url: "{{ url('/admin/hired/remove') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {hired_id: hired_id},
        success: function(response) {
            if (response=='OK') {
                deleteElement.remove();
                deleteElement=null;
                $('#modal-delete-hired').modal('hide');
            }
        }
    });
}
// =========== Finalize ==========
var finalizeElement = null;
function showFinalizeModal(e) {
    finalizeElement = $(e).parent().parent();
    $('#modal-finalize').modal('show');
}
function finalize() {
    if (!finalizeElement) return;
    const hired_id = finalizeElement.find('.hired_id').val();
    if (!hired_id) return;
    $.ajax({
        url: "{{ url('/admin/hired/update-status') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {hired_id: hired_id, status: 'Completed'},
        success: function(response) {
            if (response=='OK') {
                finalizeElement.remove();
                finalizeElement=null;
                $('#modal-finalize').modal('hide');
            }
        }
    });
}

// =========== View Note ===============
var noteElement = null;
function showAddNoteModal(e, value) {
    noteElement = $(e).parent().parent();
    $('.notes_list').empty();
    if (value) {
        const noteList = value.split('##@##');
        noteList.forEach((notes) => {
            const noteArr = notes.split('--note_by--');
            const note = noteArr[0];
            const note_by = noteArr.length>1 ? noteArr[1] : '';
            const item = '<div class="col-xs-12 form-group"><span class="note_text">'+note+'</span><button class="btn btn-xs btn-danger pull-right" onclick="removeNote(this)"><i class="fa fa-trash"></i></button><span class="pull-right note_by" style="font-size:12px; margin-right:5px">'+note_by+'</span></div>';
            $('.notes_list').append(item);
        });
    }
    $('#modal-add-note').modal('show');
}
function removeNote(e) {
    $(e).parent().remove();
    saveNote();
}
var loggedUser = '{{Auth::user()->name}}';
function addNote() {
    const note = $('.text-add-note').val();
    if (!note) return;
    const item = '<div class="col-xs-12 form-group"><span class="note_text">'+note+'</span><button class="btn btn-xs btn-danger pull-right" onclick="removeNote(this)"><i class="fa fa-trash"></i></button><span class="pull-right note_by" style="font-size:12px; margin-right:5px">'+loggedUser+'</span></div>';
    $('.notes_list').append(item);
    $('.text-add-note').val('');
    saveNote();
}
function saveNote() {
    let noteList = [];
    $('.notes_list .note_text').each(function( index ) {
        const note_by = $(this).parent().find('.note_by').text();
        noteList.push($(this).text()+'--note_by--'+note_by);
    });
    const notes = noteList.join('##@##');
    const hired_id = noteElement.find('.hired_id').val();
    $.ajax({
        url: "{{ url('/admin/hired/update-one') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {
            hired_id: hired_id,
            field: 'special_notes',
            value: notes
        },
        success: function(response) {
            if (response!='Error') {
                noteElement.find('.special_notes').attr('onclick', 'showAddNoteModal(this, "'+notes+'")');
                // $('#modal-add-note').modal('hide');
            }
        }
    });
}

// =========== Upload ICA ===============
var uploadElement = null;
function showUploadModal(e, value) {
    uploadElement = $(e).parent().parent();
    $('.upload_ica').val('');
    $('.bootstrap-filestyle input').val('');
    $('.download_ica').addClass('hidden');
    if (value) {
        $('.download_ica').removeClass('hidden');
        $('.download_ica').attr('href', '{{route("admin.hired.download_ica")}}?filename=' + value);
    }
    $('#modal-upload-ica').modal('show');
}
function saveUpload() {
    const hired_id = uploadElement.find('.hired_id').val();
    var formData = new FormData();
    const ica = $('.upload_ica').prop('files')[0];
    if (!ica) return;
    formData.append('hired_id', hired_id);
    formData.append('ica', ica);

    $.ajax({
        url: "{{ url('/admin/hired/upload-ica') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        processData: false,
        contentType: false,
        type: "post",
        data: formData,
        success: function(response) {
            if (response!='Error') {
                uploadElement.find('.ica').attr('onclick', 'showUploadModal(this, "'+response+'")');
                $('#modal-upload-ica').modal('hide');
            }
        }
    });
}
</script>

@endsection
