@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
th {
  background: white;
  position: sticky !important;
  top: 0 !important;
  box-shadow: 0 1px 1px -1px rgba(0, 0, 0, 0.2);
  border-top: 1px solid #F4F4F4 !important;
  min-width: 250px;
  z-index: 2;
}
td input {
    min-width: 300px;
}
td select {
    min-width: 200px;
}
table {
    border-collapse: separate;
}
.up-down-col {
    position: sticky !important;left: 0px; width: 50px; top: auto; padding-left: 15px; z-index: 2;
    background: #fff; border-left: 1px solid #F4F4F4 !important;
    min-width: 50px;
}
th.up-down-col {
    z-index: 10;
}
th.headcol {
    z-index: 10;
}
td.up-down-col {
    padding-top: 2px !important;
}

.headcol {
    position: sticky; width: 250px; left: 0px; top: auto; padding-left: 35px; z-index: 2; border-left: 1px solid #F4F4F4 !important;
    background: #fff; 
}
.note_by{
    color: blue
}
.worker_profile {
    width: 250px;
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>
@endsection

@section('content')
<section id="OpenPositions">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3 class="bold">Open Positions</h3>
            <br>
        </div>

        @if (Session::has('message'))
            <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            </div>
        @endif
        @if($client)
        <div class="col-md-12 col-lg-12 col-xs-12">
            @if ($client->visiblePositions()->where('status','active')->count('id')>0)
            <div class="Client-{{$client->id}} Clients">
                @foreach($client->visiblePositions()->where('status','active')->get() as $position)
                <div class="panel panel-default Position-{{$position->id}} Positions">
                    <div class="panel-heading" role="tab" id="heading{{$position->id}}">
                        <div class="panel-title ">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$position->id}}" aria-expanded="true" aria-controls="collapse{{$position->id}}">
                                <h4>
                                    <i class="fa fa-plus-square"></i> Client: {{$client->client_name}} | Position: {{$position->name}}
                                    <a href="{{route('contact.position.copy', $position->id)}}" class="btn btn-primary pull-right btn-xs hidden">Copy</a> &nbsp;
                                    <a href="{{route('contact.position.edit', $position->id)}}?from=edit" class="btn btn-warning pull-right btn-xs hidden">Edit</a>
                                </h4>
                            </a>
                        </div>
                        <div id="collapse{{$position->id}}" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading{{$position->id}}">    
                            <div class="row" style="margin-left: 10px; padding-top: 20px">
                                @foreach($position->groups()->where('visible_to_client', 1) as $group)
                                <div class="col-xs-12 group-{{$group->id}}">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><label>Group: {{$group->name}}</label></div>
                                        <div class="panel-body">
                                            <?php $count = count($group->candidates()); $height = $count>=5 ? 350: (100+51*$count); ?>
                                            <div class="box-body table_group scroll" style="overflow: scroll; height: {{$height}}px; padding:0px">
                                                <div>
                                                    <table id="detail_table" class="table text-center table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            @foreach($position->columns() as $column)
                                                            @if($column['visible_to_client'])
                                                            <th class="{{$column['field'] == 'name' ? 'headcol' : ''}}">{{$column['name']}}</th>
                                                            @endif
                                                            @endforeach
                                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="tbody-candidates" style="{{$group->editable_to_client ? '' : 'pointer-events: none; opacity: 0.8'}}">
                                                            @foreach($group->candidates()->toArray() as $candidate)
                                                            <tr>
                                                                <?php
                                                                    $columns = [];
                                                                    foreach ($position->columns() as $column) {
                                                                        if ($column['visible_to_client']) {
                                                                            $columns[] = $column['field'];
                                                                        }
                                                                    }
                                                                    $columns = implode(',', $columns);
                                                                ?>
                                                                @foreach($position->columns() as $column)
                                                                @if($column['visible_to_client'])
                                                                <?php
                                                                    if (!isset($candidate[$column['field']])) {
                                                                        $others=json_decode($candidate['other_columns'], true);
                                                                        $value=isset($others[$column['field']]) ? $others[$column['field']] : '';
                                                                    } else {
                                                                        $value=$candidate[$column['field']];
                                                                    }
                                                                ?>
                                                                <td style="{{$column['editable_to_client'] ? 'pointer-events: auto; opacity: 1' : 'pointer-events: none; opacity: 0.8'}}" class="{{$column['field'] == 'name' ? 'headcol' : ''}}">
                                                                    @if($column['field']=='name' && $column['field_type']=='dropdown')
                                                                    {!!  Form::select('candidate_name', $workerList, $value, ['class' => 'form-control name candidate_name', 'onchange'=>'updateCandidateValues(this, `'.$columns.'`)']) !!}
                                                                    @elseif($column['field_type']=='dropdown')
                                                                        @if(isset($column['drop_down_options']))
                                                                        <?php 
                                                                            $optionList=explode(',', $column['drop_down_options']);
                                                                            $options = ['' => ''];
                                                                            foreach($optionList as $opt) {
                                                                                $options["$opt"] = "$opt";
                                                                            }
                                                                        ?>
                                                                        {!!  Form::select($column['field'], $options, $value, ['class' => 'form-control '.$column['field'], 'onchange'=>'updateCandidateValues(this, `'.$columns.'`)']) !!}
                                                                        @endif
                                                                    @elseif(strpos($column['field'], 'notes'))
                                                                    <a style="pointer-events: auto; opacity: 1" class="btn {{$column['field']}}" onclick="showAddNoteModal(this, {{$candidate['id']}}, '{{$column['field']}}', '{{$value}}', {{$column['editable_to_client']}})">View Notes</a>
                                                                    @elseif($column['field_type']=='text')
                                                                    <input type="text" class="form-control {{$column['field']}}  {{$column['field']=='client_hourly_rate' ? 'numeric-field':''}}" value="{{$value}}" onfocusout="updateCandidateValues(this, '{{$columns}}')">
                                                                    @elseif($column['field_type']=='readonly')
                                                                        @if(@unserialize($value)===false)
                                                                        <input type="text" readonly class="form-control {{$column['field']}}" value="{{$value}}">
                                                                        @else
                                                                        <div class="{{$column['field']}} serialized-field">
                                                                            <table>
                                                                                @foreach (unserialize($value) as $vals)
                                                                                    <tr>
                                                                                    @foreach($vals as $key=>$val)
                                                                                        <td>{{$val}}&nbsp;</td>
                                                                                    @endforeach
                                                                                    </tr>
                                                                                @endforeach
                                                                            </table>
                                                                        </div>
                                                                        @endif
                                                                    @elseif($column['field_type']=='hyperlink')
                                                                    <a style="pointer-events: auto; opacity: 1" type="hyperlink" class="btn {{$column['field']}}" @if($value) href="{{strpos($value, 'http')===false ? ('http://'.$value) : $value}}" @endif target="_blank" oncontextmenu="editHyperlink(this, event, '{{$column['field']}}', '{{$columns}}')">{{$value ? $value : ''}}</a>
                                                                    @endif
                                                                </td>
                                                                @endif
                                                                @endforeach
                                                                <td class="action_column">
                                                                    <input type="hidden" class="candidate_name" value="{{$candidate['name']}}">
                                                                    <input type="hidden" class="position_id" value="{{$position->id}}">
                                                                    <input type="hidden" class="group_id" value="{{$group->id}}">
                                                                    <input type="hidden" class="candidate_id" value="{{$candidate['id']}}">
                                                                    <button class="btn btn-warning btn-xs" onclick="move_candidate(this, {{$position->id}})">M</button>
                                                                    <button class="btn btn-danger btn-xs" onclick="remove_candidate(this)"><i class="fa fa-close"></i></button>
                                                                    <button class="btn btn-primary btn-xs" onclick="final_candidate(this, {{$position->id}})">F</button>
                                                                </td>
                                                            <tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                <div class="modal fade" id="modal-move-group-{{$position->id}}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Move to New Group</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="col-xs-12 form-group">
                                                    {!!  Form::select('to_group_id', $position->groups()->pluck('name', 'id')->prepend('','')->toArray(), '', ['class' => 'form-control to_group_id']) !!}
                                                </div>
                                                <div class="col-xs-12 form-group">
                                                    <button class="btn btn-danger pull-right" type="button"  data-dismiss="modal"> Cancel</button>
                                                    <button class="btn btn-success pull-right" type="button" onclick="moveToNewGroup({{$position->id}})"> Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @else
        <div class="col-md-12 col-lg-12 col-xs-12">
            Contact was not linked to client.   
        </div>
        @endif

        <div class="modal fade" id="modal-final-decision" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Final Decision</h4>
                    </div>
                    <div class="modal-footer">
                        <div class="col-xs-12 form-group">
                            {!!  Form::select('final_decision_candidate', [''=>'', 'hired'=>'Hired', 'declined'=>'Declined'], '', ['class' => 'form-control final_decision_candidate']) !!}
                        </div>
                        <div class="col-xs-12 form-group">
                            <button class="btn btn-danger pull-right" type="button"  data-dismiss="modal"> Cancel</button>
                            <button class="btn btn-success pull-right" type="button" onclick="finalDecisionCandidate()"> Update</button>
                        </div>
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
                        <input type="hidden" class="note_candidate_id">
                        <input type="hidden" class="note_field">
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
</section>

<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/plugins/jQueryUI/jquery-ui.min.js"></script> 
<script>
$('.table-dragable').sortable({connectWith: ".table-dragable"});
$(function () {
    
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
    $('[data-toggle="tooltip"]').tooltip();
    inputFormatter.init('.numeric-field');
});

var workers={!! $workers !!};
function generateNameSelectBox(columns) {
    let el = '<select class="form-control name candidate_name" onchange="updateCandidateValues(this, `'+columns+'`)">';
    el += '<option value=""></option>';
    workers.forEach((worker) => {
        el += '<option value="'+worker.id+'">'+ worker.first_name + ' ' + worker.last_name + '</option>';
    });
    el += '</select>';
    return el;
}
function generateOtherSelectBox(options, field, columns) {
    let optionList = options.split(",");
    let el = '<select class="form-control '+field+'" onchange="updateCandidateValues(this, `'+columns+'`)">';
    el += '<option value=""></option>';
    optionList.forEach((option) => {
        el += '<option value="'+option.trim()+'">'+ option.trim() + '</option>';
    });
    el += '</select>';
    return el;
}

function updateCandidateValues(e, columns, video_profile=null) {
    const cols = columns.split(",");
    const el = $(e).parent().parent();
    if ($(e).hasClass('worker_monthly_rate')) {
        const worker_monthly_rate = parseFloat($(e).val()) || 0;
        const worker_hourly_rate = Math.floor(worker_monthly_rate * 12/2080 * 100)/100;
        el.find('.worker_hourly_rate').val(worker_hourly_rate);
    }
    if ($(e).hasClass('worker_hourly_rate')) {
        const worker_hourly_rate = parseFloat($(e).val()) || 0;
        const worker_monthly_rate = Math.floor(worker_hourly_rate * 2080 /12 * 100)/100;
        el.find('.worker_monthly_rate').val(worker_monthly_rate);
    }
    let data = {
        position_id: el.find('.position_id').val(),
        groups_id: el.find('.group_id').val(),
        candidate_id: el.find('.candidate_id').val()
    };
    cols.forEach((col) => {
        data[col] = el.find('.'+col).val();
    });
    if (video_profile) {
        data['video_profile'] = $(e).val();
        $(e).parent().find('.video_profile').removeClass('hidden');
        $(e).remove();
    }
    if (!el.find('.name').val()) {
        data = {
            ...data,
            name: el.find('.candidate_name').val()
        }
    }
    $.ajax({
        url: "{{ url('/contact/canditate/save') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: data,
        success: function(response) {
            let res = response;
            if (res.other_columns) {
                res = Object.assign(response, JSON.parse(res.other_columns));
            }
            el.find('.candidate_id').val(res.id);
            cols.forEach((col) => {
                if (col.indexOf('notes')>-1) {
                    el.find('.'+col).attr('onclick', 'showAddNoteModal(this, '+res.id+', `'+col+'`, ``)');
                } else if(el.find('.'+col).attr('type') == 'text' && el.find('.'+col).attr('readonly')) {
                    const array_value = unserialize(res[col]);
                    if (!array_value) {
                        el.find('.'+col).text(res[col]);
                        el.find('.'+col).val(res[col]);
                    } else {
                        let child = '<div class="'+col+' serialized-field"><table>';
                        Object.keys(array_value).forEach((key)=>{
                            const table_row = array_value[key];
                            child += '<tr>';
                            Object.keys(table_row).forEach((table_feild) => {
                                child += '<td>'+table_row[table_feild]+'&nbsp;</td>';
                            });
                            child += '</tr>';
                        });
                        child+='</table></div>';
                        el.find('.'+col).parent().html(child);
                    }
                } else if(el.find('.'+col).attr('type') == 'hyperlink') {
                    el.find('.'+col).attr('href', res[col]);
                    if (res[col]) {
                        el.find('.'+col).text(res[col]);
                    }
                } else {
                    el.find('.'+col).val(res[col]);
                }
            });
        }
    });
}

function add_candidate(e, columns, groupId, positionId) {
    let cols = JSON.parse(columns).filter((item) => item.visible_to_client>0);
    const colList = cols.map((item)=>item.field).join(',');
    let row = '<tr>'
    cols.forEach((col) => {
        let element = '';
        const numberic_field = (col.field =='client_hourly_rate') ? ' numeric-field':''
        if (col.field == 'name' && col.field_type=='dropdown') {
            element = generateNameSelectBox(colList);
        } else if (col.field.indexOf('notes')>-1) {
            element = '<a class="btn '+col.field+'" onclick="showAddNoteModal(this, null, `'+col.field+'`, ``)">View Notes</a>';
        } else if (col.field_type=='readonly') {
            element = '<input class="form-control '+col.field+'" type="text" readonly>';
        } else if (col.field_type=='text') {
            element = '<input class="form-control '+col.field + numberic_field +'" type="text" onfocusout="updateCandidateValues(this, `'+colList+'`)">';
        } else if (col.field_type=='hyperlink') {
            element = '<a type="hyperlink" class="btn '+col.field+'" href="" oncontextmenu="editHyperlink(this, event, `'+col.field+'`, `'+colList+'`)">Click Here</a>';
        } else if (col.field_type=='dropdown') {
            element = generateOtherSelectBox(col.drop_down_options, col.field, colList);
        }
        if (col.field == 'name') {
            row+='<td class="headcol">'+element+'</td>';
        } else {
            row+='<td>'+element+'</td>';
        }
    });
    let candidateId = "new_" + Date.now();
    row+='<td class="action_column"><input type="hidden" class="position_id" value="'+positionId+'">'
         +'<input type="hidden" class="group_id" value="'+groupId+'">'
         +'<input type="hidden" class="candidate_id" value="'+candidateId+'">'
         +'<button class="btn btn-danger btn-xs" onclick="remove_candidate(this)"><i class="fa fa-close"></i></button></td></tr>';
    $(e).parent().find('.tbody-candidates').append(row);
    let h = (parseInt($(e).parent().find('.table_group').css('height').replace('px', '')) || 0) + 51;
    if (h>350) h = 350;
    $(e).parent().find('.table_group').css('height', h + 'px');
}
function remove_candidate(e) {
    const el = $(e).parent().parent();
    const candidate_id = el.find('.candidate_id').val();
    if (!candidate_id) return;
    const h = (parseInt(el.parent().parent().parent().css('height').replace('px', '')) || 0) - 51;
    el.parent().parent().parent().css('height', h + 'px');
    if (candidate_id.substr(0,4)=='new_') {
        el.remove(); return;
    }
    $.ajax({
        url: "{{ url('/contact/canditate/remove') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {candidate_id: candidate_id},
        success: function(response) {
            if (response=='OK') {
                el.remove();
            }
        }
    });
}

var currentElement = null;
var positionId = null;
function final_candidate(e, position_id) {
    positionId = position_id;
    currentElement = $(e).parent().parent();
    $('.final_decision_candidate').val('');
    $('#modal-final-decision').modal('show');
}
function finalDecisionCandidate() {
    const decision = $('.final_decision_candidate').val();
    if (!decision) return;
    const candidate_id = currentElement.find('.candidate_id').val();
    if (!candidate_id) return;
    $.ajax({
        url: "{{ url('/contact/canditate/final-decision') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {candidate_id: candidate_id, decision: decision},
        success: function(response) {
            if (response=='OK') {
                currentElement.find('.action_column').remove();
                currentElement.find('select').attr('disabled', true);
                currentElement.find('input').attr('readonly', true);
                currentElement.appendTo($('.Position-'+positionId + ' .group-completed .tbody-candidates'));
            }
        }
    });
    $('#modal-final-decision').modal('hide');
}
function move_candidate(e, position_id) {
    currentElement = $(e).parent().parent();
    $('.to_group_id').val('');
    $('#modal-move-group-' + position_id).modal('show');
}
function moveToNewGroup(position_id) {
    const to_group_id = $('#modal-move-group-' + position_id + ' .to_group_id').val();
    if (!to_group_id) return;
    const candidate_id = currentElement.find('.candidate_id').val();
    if (!candidate_id) return;
    $.ajax({
        url: "{{ url('/contact/canditate/move-to-group') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {candidate_id: candidate_id, to_group_id: to_group_id},
        success: function(response) {
            if (response=='OK') {
                currentElement.appendTo($('.Position-'+position_id + ' .group-'+to_group_id + ' .tbody-candidates'));
            }
        }
    });
    $('#modal-move-group-' + position_id).modal('hide');
}

var noteElement = null;
function showAddNoteModal(e, candidate_id, field, value, editable = true) {
    noteElement = $(e).parent().parent();
    $('.note_candidate_id').val(candidate_id);
    $('.note_field').val(field);
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
    if (!editable) {
        $('#modal-add-note').find('.btn-primary').attr('disabled', true);
        $('#modal-add-note').find('.btn-success').attr('disabled', true);
        $('#modal-add-note').find('input').attr('disabled', true);
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
    const candidate_id = $('.note_candidate_id').val();
    const field = $('.note_field').val();
    $.ajax({
        url: "{{ url('/contact/canditate/update-note') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {
            candidate_id: candidate_id,
            field: field,
            notes: notes
        },
        success: function(response) {
            if (response=='OK') {
                noteElement.find('.' + field).attr('onclick', 'showAddNoteModal(this, '+candidate_id+', "'+field+'", "'+notes+'")')
                // $('#modal-add-note').modal('hide');
            }
        }
    });
}
function editHyperlink(e, event, field, columns) {
    return;
    if (field != 'video_profile') return;
    const href = $(e).attr('href');
    const value = href ? href : '';
    $(e).parent().append('<input class="form-control edit_video_profile" value="'+value+'" onchange="updateCandidateValues(this, `'+columns+'`, `video_profile`)" onkeypress="enterHyperlink(this, event, `'+columns+'`)">');
    $(e).addClass('hidden');
    event.preventDefault();
}
function enterHyperlink(e, event, columns) {
    if (event.keyCode == 13) {
        updateCandidateValues(e, columns, 'video_profile');
    }
}
function unserialize(data){  
    if (!data || !isNaN(data)) return null;
    var read_until = function (data, offset, stopchr){  
        var buf = [];  
        var chr = data.slice(offset, offset + 1);  
        var i = 2;  
        while(chr != stopchr){  
            if((i+offset) > data.length){  
                return null;
            }  
            buf.push(chr);  
            chr = data.slice(offset + (i - 1),offset + i);  
            i += 1;  
        }  
        return [buf.length, buf.join('')];  
    };  
    var read_chrs = function (data, offset, length){  
        buf = [];  
        for(var i = 0;i < length;i++){  
            var chr = data.slice(offset + (i - 1),offset + i);  
            buf.push(chr);  
        }  
        return [buf.length, buf.join('')];  
    };  
    var _unserialize = function (data, offset){  
        if(!offset) offset = 0;  
        var buf = [];  
        var dtype = (data.slice(offset, offset + 1)).toLowerCase();  
          
        var dataoffset = offset + 2;  
        var typeconvert = new Function('x', 'return x');  
        var chrs = 0;  
        var datalength = 0;  
          
        switch(dtype){  
            case "i":  
                typeconvert = new Function('x', 'return parseInt(x)');  
                var readData = read_until(data, dataoffset, ';');
                if (!readData || readData.length<2) return null;
                var chrs = readData[0];  
                var readdata = readData[1];  
                dataoffset += chrs + 1;  
            break;  
            case "b":  
                typeconvert = new Function('x', 'return (parseInt(x) == 1)');  
                var readData = read_until(data, dataoffset, ';');  
                if (!readData || readData.length<2) return null;
                var chrs = readData[0];  
                var readdata = readData[1];  
                dataoffset += chrs + 1;  
            break;  
            case "d":  
                typeconvert = new Function('x', 'return parseFloat(x)');  
                var readData = read_until(data, dataoffset, ';');  
                if (!readData || readData.length<2) return null;
                var chrs = readData[0];  
                var readdata = readData[1];  
                dataoffset += chrs + 1;  
            break;  
            case "n":  
                readdata = null;  
            break;  
            case "s":  
                var ccount = read_until(data, dataoffset, ':');  
                if (!ccount || ccount.length<2) return null;
                var chrs = ccount[0];  
                var stringlength = ccount[1];  
                dataoffset += chrs + 2;  
                  
                var readData = read_chrs(data, dataoffset+1, parseInt(stringlength));  
                if (!readData || readData.length<2) return null;
                var chrs = readData[0];  
                var readdata = readData[1];  
                dataoffset += chrs + 2;  
                if(chrs != parseInt(stringlength) && chrs != readdata.length){  
                    return null; 
                }  
            break;  
            case "a":  
                var readdata = {};  
                  
                var keyandchrs = read_until(data, dataoffset, ':');  
                if (!keyandchrs || keyandchrs.length<2) return null;
                var chrs = keyandchrs[0];  
                var keys = keyandchrs[1];  
                dataoffset += chrs + 2;  
                  
                for(var i = 0;i < parseInt(keys);i++){  
                    var kprops = _unserialize(data, dataoffset);  
                    if (!kprops || kprops.length<3) return null;
                    var kchrs = kprops[1];  
                    var key = kprops[2];  
                    dataoffset += kchrs;  
                      
                    var vprops = _unserialize(data, dataoffset);  
                    if (!vprops || vprops.length<3) return null;
                    var vchrs = vprops[1];  
                    var value = vprops[2];  
                    dataoffset += vchrs;  
                      
                    readdata[key] = value;  
                }  
                  
                dataoffset += 1;  
            break;  
            default:  
                return null;
            break;  
        }  
        return [dtype, dataoffset - offset, typeconvert(readdata)];  
    };
    const _unsz = _unserialize(data, 0);
    return _unsz && _unsz.length>2 ? _unsz[2] : null;
}
</script>

@endsection
