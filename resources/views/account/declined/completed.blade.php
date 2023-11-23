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
            <h3 class="bold">Declined - Completed</h3>
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
                            <th>Decline Reason</th>
                            <th>Special Notes</th>
                            <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody class="tbody-declined">
                        @foreach($declineds as $declined)
                        <tr>
                            <td>{{$accountManagers[$declined->account_managers_id]}}</td>
                            <td>{{$workers[$declined->workers_id]}}</td>
                            <td>{{$clients[$declined->clients_id]}}</td>
                            <td>{{$declined->email}}</td>
                            <td>{{$declined->decline_reason}}</td>
                            <td><a class="btn special_notes" onclick="showAddNoteModal(this, '{{$declined->special_notes}}')">View Notes</a></td>
                            <td>
                                <input type="hidden" class="declined_id" value="{{$declined->id}}">
                                <button type="button" class="btn btn-danger btn-xs" onclick="showDeleteDeclinedModal(this)"><i class="fa fa-close"></i></button>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
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
        url: "{{ url('/accountManager/declined/remove') }}",
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
        url: "{{ url('/accountManager/declined/update-one') }}",
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
