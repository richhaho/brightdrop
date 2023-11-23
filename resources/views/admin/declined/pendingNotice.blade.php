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
<section id="DeclinedNeedsFinalized">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3 class="bold">Declined - Pending Notice</h3>
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
                            <th>Notice Sent</th>
                            <th>Account Manager</th>
                            <th>Worker Name</th>
                            <th>Client</th>
                            <th>Email Address</th>
                            <th>Decline Reason</th>
                            <th>Special Notes</th>
                            <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody class="tbody-declined">
                        @foreach($declineds as $declined)
                        <tr>
                            <td>
                                <input type="hidden" class="declined_id" value="{{$declined->id}}">
                                @if ($declined->decline_reason)
                                <button class="btn btn-success btn-xs btn-finalize" onclick="showFinalizeModal(this)"><i class="fa fa-check">&nbsp;&nbsp; Yes</i></button>
                                @else
                                <button disabled class="btn btn-success btn-xs btn-finalize" onclick="showFinalizeModal(this)"><i class="fa fa-check">&nbsp;&nbsp; Yes</i></button>
                                @endif
                            </td>
                            <td>{{$accountManagers[$declined->account_managers_id]}}</td>
                            <td>{{$workers[$declined->workers_id]}}</td>
                            <td>{{$clients[$declined->clients_id]}}</td>
                            <td>{{$declined->email}}</td>
                            <td>{!!  Form::select('decline_reason', $decline_reason, $declined->decline_reason, ['class' => 'form-control decline_reason', 'onchange'=>'updateOneField(this, "decline_reason")']) !!}</td>
                            <td><a class="btn special_notes" onclick="showAddNoteModal(this, '{{$declined->special_notes}}')">View Notes</a></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs" onclick="showDeleteDeclinedModal(this)"><i class="fa fa-close"></i></button>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-warning btn-add-declined" onclick="addDeclined()" style="margin-top: 10px; margin-left: 10px"><i class="fa fa-plus"></i> Add Row</button>
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
            <div class="modal fade" id="modal-delete-declined" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Remove Declined</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to remove?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" onclick="deleteDeclined()"> Remove</button>&nbsp;&nbsp;
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

var accountManagers = {!! json_encode($accountManagers) !!}
var clients = {!! json_encode($clients) !!}
var candidates = {!! json_encode($candidates) !!}
function addDeclined() {
    const row = `<tr>
                    <td>
                        <input type="hidden" class="declined_id" value="">
                        <button disabled class="btn btn-success btn-xs btn-finalize" onclick="showFinalizeModal(this)"><i class="fa fa-check">&nbsp;&nbsp; Yes</i></button>
                    </td>
                    <td>`+generateSelectBox(accountManagers, 'account_managers_id')+`</td>
                    <td>`+generateSelectBox(candidates, 'workers_id')+`</td>
                    <td>`+generateSelectBox(clients, 'clients_id')+`</td>
                    <td><input class="form-control email" type="email"></td>
                    <td>`+generateSelectBox({'': '', 'Applicant Withdrew': 'Applicant Withdrew', 'Client Declined':'Client Declined', 'Not Qualified':'Not Qualified', 'Other (See Notes)':'Other (See Notes)'}, 'decline_reason')+`</td>
                    <td><a class="btn special_notes hidden" onclick="showAddNoteModal(this, '')">View Notes</a></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-xs" onclick="showDeleteDeclinedModal(this)"><i class="fa fa-close"></i></button>
                    </td>
                </tr>`;
    $('.tbody-declined').append(row);
}
function generateSelectBox(list, field) {
    let elem = '<select class="form-control '+ field +'" onchange="updateOneField(this, `'+ field +'`)">';
    if (field!='available_additional_work') {
        elem+='<option value=""></option>';
    }
    Object.keys(list).forEach((key)=>{
        if (key) {
            elem+='<option value="'+key+'">'+list[key]+'</option>';
        }
    })
    elem += '</select>'
    return elem;
}

function createDeclined(el) {
    const candidates_id = el.find('.workers_id').val();
    const account_managers_id = el.find('.account_managers_id').val();
    const clients_id = el.find('.clients_id').val();
    if (!account_managers_id || !clients_id || !candidates_id) return;
    const email = el.find('.email').val();
    const decline_reason = el.find('.decline_reason').val();
    $.ajax({
        url: "{{ url('/admin/declined/save') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {
            declined_id: 0,
            status: 'Pending Notice',
            candidates_id: candidates_id,
            account_managers_id: account_managers_id,
            clients_id: clients_id,
            email: email,
            decline_reason: decline_reason,
        },
        success: function(response) {
            el.find('.declined_id').val(response.id);
            el.find('.email').val(response.email);
            el.find('.decline_reason').val(response.decline_reason);
            el.find('.special_notes').removeClass('hidden');
        }
    });

}

function updateOneField(e, field) {
    const el = $(e).parent().parent();
    const declined_id = el.find('.declined_id').val();
    const value = $(e).val();
    if (!declined_id) {
        createDeclined(el);
        return;
    }
    $.ajax({
        url: "{{ url('/admin/declined/update-one') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {
            declined_id,
            field,
            value
        },
        success: function(response) {
            if (response == "Error" ) return;
            const {
                candidates_id,
                account_managers_id,
                clients_id,
                email,
                decline_reason,
            } = response;
            if (candidates_id &&
                account_managers_id &&
                clients_id &&
                email &&
                decline_reason) {
                el.find('.btn-finalize').removeAttr('disabled');
            } else {
                el.find('.btn-finalize').attr('disabled', true);
            }

        }
    });
}

// =========== Delete ==========
var deleteElement = null;
function showDeleteDeclinedModal(e) {
    deleteElement = $(e).parent().parent();
    $('#modal-delete-declined').modal('show');
}
function deleteDeclined() {
    if (!deleteElement) return;
    const declined_id = deleteElement.find('.declined_id').val();
    if (!declined_id) {
        deleteElement.remove();
        deleteElement=null;
        $('#modal-delete-declined').modal('hide');
        return;
    }
    $.ajax({
        url: "{{ url('/admin/declined/remove') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {declined_id: declined_id},
        success: function(response) {
            if (response=='OK') {
                deleteElement.remove();
                deleteElement=null;
                $('#modal-delete-declined').modal('hide');
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
    const declined_id = finalizeElement.find('.declined_id').val();
    if (!declined_id) return;
    $.ajax({
        url: "{{ url('/admin/declined/update-status') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {declined_id: declined_id, status: 'Completed'},
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
    const declined_id = noteElement.find('.declined_id').val();
    $.ajax({
        url: "{{ url('/admin/declined/update-one') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {
            declined_id: declined_id,
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

</script>

@endsection
