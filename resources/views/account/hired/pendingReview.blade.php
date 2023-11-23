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
<section id="HiredPendingReview">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3 class="bold">Hired - Pending Review</h3>
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
                            <th>Worker Finalized</th>
                            <th>Account Manager</th>
                            <th>Worker Name</th>
                            <th>Client</th>
                            <th>Email Address</th>
                            <th>Rehire</th>
                            <th>Available for Additional Work</th>
                            <th>Client-Hourly Rate(USD)-Final</th>
                            <th>Worker&nbsp;&nbsp;Requested&nbsp;Pay</th>
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
                            <td>
                                <input type="hidden" class="hired_id" value="{{$hired->id}}">
                                @if (
                                    $hired->available_additional_work &&
                                    $hired->client_hourly_rate_usd &&
                                    $hired->worker_monthly_rate &&
                                    $hired->worker_hourly_rate &&
                                    $hired->pto &&
                                    $hired->paid_holidays &&
                                    $hired->expected_start_date && $hired->ica
                                )
                                <button class="btn btn-success btn-xs btn-finalize" onclick="showFinalizeModal(this)"><i class="fa fa-check">&nbsp;&nbsp; Yes</i></button>
                                @else
                                <button disabled class="btn btn-success btn-xs btn-finalize" onclick="showFinalizeModal(this)"><i class="fa fa-check">&nbsp;&nbsp; Yes</i></button>
                                @endif
                            </td>
                            <td>{{$accountManagers[$hired->account_managers_id]}}</td>
                            <td>{{$workers[$hired->workers_id]}}</td>
                            <td>{{$clients[$hired->clients_id]}}</td>
                            <td>{{$hired->email}}</td>
                            <td>{!!  Form::select('rehire', ['yes'=>'Yes', 'no'=>'No'], $hired->rehire, ['disabled'=>true,'class' => 'form-control rehire', 'onchange'=>'updateOneField(this, "rehire")']) !!}</td>
                            <td>{!!  Form::select('available_additional_work', [''=>'', 'yes'=>'Yes', 'no'=>'No'], $hired->available_additional_work, ['disabled'=>true,'class' => 'form-control available_additional_work', 'onchange'=>'updateOneField(this, "available_additional_work")']) !!}</td>
                            <td><input readonly class="form-control client_hourly_rate_usd numeric-field" type="text" min="0" step="0.01" value="{{$hired->client_hourly_rate_usd}}" onchange="updateOneField(this, 'client_hourly_rate_usd')"></td>
                            <td>{{$hired->requested_pay}}</td>
                            <td><input readonly class="form-control worker_monthly_rate numeric-field" type="text" min="0" step="0.01" value="{{$hired->worker_monthly_rate}}" onchange="updateOneField(this, 'worker_monthly_rate')"></td>
                            <td><input readonly class="form-control worker_hourly_rate numeric-field" type="text" min="0" step="0.01" value="{{$hired->worker_hourly_rate}}" onchange="updateOneField(this, 'worker_hourly_rate')"></td>
                            <td>{{$hired->worker_currency_type}}</td>
                            <td>{!!  Form::select('pto', [''=>'', 'yes'=>'Yes', 'no'=>'No'], $hired->pto, ['disabled'=>true,'class' => 'form-control pto', 'onchange'=>'updateOneField(this, "pto")']) !!}</td>
                            <td>{!!  Form::select('paid_holidays', [''=>'', 'yes'=>'Yes', 'no'=>'No'], $hired->paid_holidays, ['disabled'=>true,'class' => 'form-control paid_holidays', 'onchange'=>'updateOneField(this, "paid_holidays")']) !!}</td>
                            <td><input readonly class="form-control expected_start_date" type="date" value="{{$hired->expected_start_date}}" onchange="updateOneField(this, 'expected_start_date')"></td>
                            <td><a class="btn ica" onclick="showUploadModal(this, '{{$hired->ica}}')">Upload</a></td>
                            <td><a class="btn special_notes" onclick="showAddNoteModal(this, '{{$hired->special_notes}}')">View Notes</a></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs" onclick="showDeleteHiredModal(this)"><i class="fa fa-close"></i></button>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-warning btn-add-hired" onclick="addHired()" style="margin-top: 10px; margin-left: 10px"><i class="fa fa-plus"></i> Add Row</button>
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

var accountManagers = {!! json_encode($accountManagers) !!}
var clients = {!! json_encode($clients) !!}
var candidates = {!! json_encode($candidates) !!}
function addHired() {
    const row = `<tr>
                    <td>
                        <input type="hidden" class="hired_id" value="">
                        <button disabled class="btn btn-success btn-xs btn-finalize" onclick="showFinalizeModal(this)"><i class="fa fa-check">&nbsp;&nbsp; Yes</i></button>
                    </td>
                    <td>`+generateSelectBox(accountManagers, 'account_managers_id')+`</td>
                    <td>`+generateSelectBox(candidates, 'workers_id')+`</td>
                    <td>`+generateSelectBox(clients, 'clients_id')+`</td>
                    <td><input class="form-control email" type="email"></td>
                    <td>`+generateSelectBox({'no': 'no', 'yes': 'yes'}, 'rehire')+`</td>
                    <td>`+generateSelectBox({'no': 'no', 'yes': 'yes'}, 'available_additional_work')+`</td>
                    <td><input class="form-control client_hourly_rate_usd numeric-field" type="text" min="0" step="0.01" value="" onchange="updateOneField(this, 'client_hourly_rate_usd')"></td>
                    <td><input class="form-control requested_pay" readonly type="text"></td>
                    <td><input class="form-control worker_monthly_rate numeric-field" type="text" min="0" step="0.01" value="" onchange="updateOneField(this, 'worker_monthly_rate')"></td>
                    <td><input class="form-control worker_hourly_rate numeric-field" type="text" min="0" step="0.01" value="" onchange="updateOneField(this, 'worker_hourly_rate')"></td>
                    <td>`+generateSelectBox({'USD': 'USD', 'PHP': 'PHP', 'MXN': 'MXN'}, 'worker_currency_type')+`</td>
                    <td>`+generateSelectBox({'yes': 'Yes', 'no': 'No'}, 'pto')+`</td>
                    <td>`+generateSelectBox({'yes': 'Yes', 'no': 'No'}, 'paid_holidays')+`</td>
                    <td><input class="form-control expected_start_date" type="date" value="" onchange="updateOneField(this, 'expected_start_date')"></td>
                    <td><a class="btn ica hidden" onclick="showUploadModal(this, '')">Upload</a></td>
                    <td><a class="btn special_notes hidden" onclick="showAddNoteModal(this, '')">View Notes</a></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-xs" onclick="showDeleteHiredModal(this)"><i class="fa fa-close"></i></button>
                    </td>
                </tr>`;
    $('.tbody-hired').append(row);
}
function generateSelectBox(list, field) {
    let elem = '<select class="form-control '+ field +'" onchange="updateOneField(this, `'+ field +'`)">';
    if (field!='rehire') {
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

function createHired(el) {
    const candidates_id = el.find('.workers_id').val();
    const account_managers_id = el.find('.account_managers_id').val();
    const clients_id = el.find('.clients_id').val();
    if (!account_managers_id || !clients_id || !candidates_id) return;
    const email = el.find('.email').val();
    const available_additional_work = el.find('.available_additional_work').val();
    const client_hourly_rate_usd = el.find('.client_hourly_rate_usd').val();
    const worker_monthly_rate = el.find('.worker_monthly_rate').val();
    const worker_hourly_rate = el.find('.worker_hourly_rate').val();
    const worker_currency_type = el.find('.worker_currency_type').val();
    const pto = el.find('.pto').val();
    const paid_holidays = el.find('.paid_holidays').val();
    const expected_start_date = el.find('.expected_start_date').val();
    $.ajax({
        url: "{{ url('/accountManager/hired/save') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {
            hired_id: 0,
            status: 'Pending Review',
            candidates_id: candidates_id,
            account_managers_id: account_managers_id,
            clients_id: clients_id,
            email: email,
            available_additional_work: available_additional_work,
            client_hourly_rate_usd: client_hourly_rate_usd,
            worker_monthly_rate: worker_monthly_rate,
            worker_hourly_rate: worker_hourly_rate,
            worker_currency_type: worker_currency_type,
            pto: pto,
            paid_holidays: paid_holidays,
            expected_start_date: expected_start_date
        },
        success: function(response) {
            el.find('.hired_id').val(response.id);
            el.find('.email').val(response.email);
            el.find('.available_additional_work').val(response.available_additional_work);
            el.find('.client_hourly_rate_usd').val(response.client_hourly_rate_usd);
            el.find('.requested_pay').val(response.requested_pay);
            el.find('.worker_monthly_rate').val(response.worker_monthly_rate);
            el.find('.worker_hourly_rate').val(response.worker_hourly_rate);
            el.find('.worker_currency_type').val(response.worker_currency_type);
            el.find('.pto').val(response.pto);
            el.find('.paid_holidays').val(response.paid_holidays);
            el.find('.expected_start_date').val(response.expected_start_date);
            el.find('.ica').removeClass('hidden');
            el.find('.special_notes').removeClass('hidden');
        }
    });

}

function updateOneField(e, field) {
    const el = $(e).parent().parent();
    const hired_id = el.find('.hired_id').val();
    let value = $(e).val();
    if (!hired_id) {
        createHired(el);
        return;
    }
    saveUpdated(el, hired_id, field, value);
    if (field=='worker_monthly_rate') {
        value = value.replaceAll(',', '');
        const worker_monthly_rate = parseFloat(value) || 0;
        const worker_hourly_rate = new Intl.NumberFormat().format(Math.round(worker_monthly_rate * 12/2080 * 100)/100);
        el.find('.worker_hourly_rate').val(worker_hourly_rate);
        saveUpdated(el, hired_id, 'worker_hourly_rate', worker_hourly_rate);
    } else if (field=='worker_hourly_rate') {
        value = value.replaceAll(',', '');
        const worker_hourly_rate = parseFloat(value) || 0;
        const worker_monthly_rate = new Intl.NumberFormat().format(Math.round(worker_hourly_rate * 2080 /12 * 100)/100);
        el.find('.worker_monthly_rate').val(worker_monthly_rate);
        saveUpdated(el, hired_id, 'worker_monthly_rate', worker_monthly_rate);
    }
   
}
function saveUpdated(el, hired_id, field, value) {
    $.ajax({
        url: "{{ url('/accountManager/hired/update-one') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {
            hired_id,
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
                    available_additional_work,
                    client_hourly_rate_usd,
                    worker_monthly_rate,
                    worker_hourly_rate,
                    worker_currency_type,
                    pto,
                    paid_holidays,
                    expected_start_date,
                    ica
                } = response;
            if (candidates_id &&
                account_managers_id &&
                clients_id &&
                email &&
                available_additional_work &&
                client_hourly_rate_usd &&
                worker_monthly_rate &&
                worker_hourly_rate &&
                worker_currency_type &&
                pto &&
                paid_holidays &&
                expected_start_date && ica
                ) {
                    el.find('.btn-finalize').removeAttr('disabled');
                } else {
                    el.find('.btn-finalize').attr('disabled', true);
                }

        }
    });
}

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
        url: "{{ url('/accountManager/hired/remove') }}",
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
        url: "{{ url('/accountManager/hired/update-status') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {hired_id: hired_id, status: 'Needs Setup'},
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
        url: "{{ url('/accountManager/hired/update-one') }}",
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
        $('.download_ica').attr('href', '{{route("account.hired.download_ica")}}?filename=' + value);
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
        url: "{{ url('/accountManager/hired/upload-ica') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        processData: false,
        contentType: false,
        type: "post",
        data: formData,
        success: function(response) {
            if (response!='Error') {
                uploadElement.find('.ica').attr('onclick', 'showUploadModal(this, "'+response.ica+'")');
                $('#modal-upload-ica').modal('hide');
                const {
                    candidates_id,
                    account_managers_id,
                    clients_id,
                    email,
                    available_additional_work,
                    client_hourly_rate_usd,
                    worker_monthly_rate,
                    worker_hourly_rate,
                    worker_currency_type,
                    pto,
                    paid_holidays,
                    expected_start_date,
                    ica
                } = response;
                if (candidates_id &&
                    account_managers_id &&
                    clients_id &&
                    email &&
                    available_additional_work &&
                    client_hourly_rate_usd &&
                    worker_monthly_rate &&
                    worker_hourly_rate &&
                    worker_currency_type &&
                    pto &&
                    paid_holidays &&
                    expected_start_date &&
                    ica
                    ) {
                        uploadElement.find('.btn-finalize').removeAttr('disabled');
                    } else {
                        uploadElement.find('.btn-finalize').attr('disabled', true);
                    }
            }
        }
    });
}
</script>

@endsection
