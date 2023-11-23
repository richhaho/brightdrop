@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important}  
.form-pto{padding: 2px !important}
.form-pto input{text-align: center;}
.Worker-status-inactive{display: none}
.PTO-year-{{date('Y')}}{display: block}
</style>
@endsection

@section('content')


    <section id="PTO">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">PTO Summaries </h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        {!! Form::open(['route' => 'admin.pto.setfilter', 'class'=>'form-inline'])!!}
                            <input type="hidden" name="page" value="PTOsummary">
                            <span>&nbsp; Client:&nbsp;&nbsp;&nbsp;</span>
                            {!!  Form::select('clients_id',$search_clients,$clients_id, ['class' => 'search_clients','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}<br>
                            <span>&nbsp; Worker:</span>
                            {!!  Form::select('workers_id',$search_workers,$workers_id, ['class' => 'search_workers','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}
                            <span>&nbsp; Year:</span>
                            {!!  Form::select('year', $years, $year, ['class' => 'search_year','style'=>'margin-left:2px;width:80px']) !!}
                            &nbsp;&nbsp;<button type="submit" class="btn btn-success btn-xs btn-search" > <i class="fa fa-search"></i> Search</button> 
                            &nbsp;&nbsp;<a href="{{ route('admin.pto.resetfilter') }}?page=PTOsummary" class="btn btn-danger btn-xs"> <i class="fa fa-times"></i> Clear</a>
                        {!! Form::close() !!}
                        </div>
                    </div>
                </div><br>

                @foreach ($pto_summaries as $key => $pto_summary)
                <div class="Worker-status Worker-status-{{$pto_summary['status']}}">
                <div class="PTO-year PTO-year-{{$pto_summary['year']}}">
                <div class="Client-{{$pto_summary['client']}} Clients">
                    <div class="panel panel-default Worker-{{$pto_summary['worker']}} Worker">
                        {!! Form::open(['route' => 'admin.updatePTOsummary','autocomplete' => 'off']) !!}
                        <div class="panel-heading center header-{{$pto_summary['client']}}_{{$pto_summary['worker']}}">
                            {{$pto_summary['title']}}
                            <a href="#" class="btn btn-success pull-right btn-xs btn-save" disabled data-toggle="modal" data-target="#modal-edit-pto-{{$key}}"><i class="fa fa-save"></i> Save</a>
                            <div class="modal fade" id="modal-edit-pto-{{$key}}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">PTO Edit Message</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Editing PTO in this area does not trigger a payment to be sent or removed. If money should be transferred as a result of this edit, please do so by creating an adjustment.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success"> &nbsp;&nbsp;&nbsp;&nbsp;Ok&nbsp;&nbsp;&nbsp;&nbsp;</button>&nbsp;&nbsp;
                                        <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-warning pull-right btn-xs" onclick="edit_used_pto(this);" data="{{$pto_summary['client']}}_{{$pto_summary['worker']}}"><i class="fa fa-edit"></i> Edit</button>
                            <input type="hidden" name="client_id" value="{{$pto_summary['client']}}" />
                            <input type="hidden" name="worker_id" value="{{$pto_summary['worker']}}" />
                        </div>
                        <div class="panel-body body-{{$pto_summary['client']}}_{{$pto_summary['worker']}}">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>PTO Remaining:</label>
                                </div>
                                <div class="col-xs-8 form-group">
                                    <input type="hidden" class="pto_all_hours" value="{{$pto_summary['pto_all_hours']}}">
                                    <label class="pto_remaining">{{$pto_summary['pto_remaining']}}</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>PTO Used:</label>
                                </div>
                                <div class="col-xs-8 form-group {{$pto_summary['client']}}_{{$pto_summary['worker']}}">
                                    @foreach ($pto_summary['pto_used'] as $index => $pto_used)
                                    <input name="origin_pto_date[{{$index}}]" type="hidden" class="form-control" value="{{$pto_used['date']}}">
                                    <div class="row">
                                        <div class="col-xs-5 form-pto">
                                            <input name="pto_date[{{$index}}]" type="date" class="form-control pto_date" value="{{$pto_used['date']}}" readonly required oninput="edit_pto_date(this)" min="{{date('Y')}}-01-01" max="{{date('Y')}}-12-31">
                                            <p style="color: red; display: none">* Date was duplicated.</p>
                                        </div>
                                        <div class="col-xs-5 form-pto">
                                            <input name="pto_hours[{{$index}}]" type="number" class="form-control pto_hours" value="{{$pto_used['hours']}}" readonly min="0" step="1" required oninput="edit_pto_hours(this)">
                                            <p style="color: red; display: none">* PTO hours was limited.</p>
                                        </div>
                                        <div class="col-xs-2 form-pto">
                                            <button type="button" class="btn btn-danger " onclick="delete_used_pto(this)" disabled><i class="fa fa-close"></i></button>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="row">
                                        <br>
                                        <button type="button" class="btn btn-warning btn-add" disabled onclick="add_used_pto(this)"><i class="fa fa-plus"></i> Add New</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
    $('#detail_table').DataTable({"order": [2, 'desc']});
});

function edit_used_pto(e) {
    $('.'+$(e).attr('data')+' input').removeAttr('readonly');
    $('.'+$(e).attr('data')+' button').removeAttr('disabled');
    $('.'+$(e).attr('data')+' .btn-add').removeAttr('disabled');
    $('.header-'+$(e).attr('data')+' .btn-save').removeAttr('disabled');
}
function delete_used_pto(e) {
    $(e).parent().parent().parent().parent().parent().parent().find('.btn-save').removeAttr('disabled');
    $(e).parent().parent().remove();
}
function add_used_pto(e) {
    let d = Date.now();
    let today = new Date();
    let year = today.getFullYear();
    let add = '<div class="row">'
            +    '<div class="col-xs-5 form-pto">'
            +        '<input name="pto_date['+d+']" type="date" class="form-control pto_date" required oninput="edit_pto_date(this)" min="'+year+'-01-01" max="'+year+'-12-31">'
            +        '<p style="color: red; display: none">* Date was duplicated.</p>'
            +    '</div>'
            +    '<div class="col-xs-5 form-pto">'
            +        '<input name="pto_hours['+d+']" type="number" class="form-control pto_hours" min="0" step="1" required oninput="edit_pto_hours(this)">'
            +        '<p style="color: red; display: none">* PTO hours was limited.</p>'
            +    '</div>'
            +    '<div class="col-xs-2 form-pto">'
            +        '<button type="button" class="btn btn-danger " onclick="delete_used_pto(this)"><i class="fa fa-close"></i></button>'
            +    '</div>'
            + '</div>';

    $(e).parent().before(add);
}
function edit_pto_date(e) {
    let duplicated = 0;
    $(e).parent().parent().parent().find('.pto_date').each(function(index){
        if ($(this).val()==$(e).val()){
            duplicated++;
        };
    });
    if(duplicated>1) {
        $(e).parent().find('p').css('display', 'block');
        $(e).parent().parent().parent().parent().parent().parent().find('.btn-save').attr('disabled', true);
    } else {
        $(e).parent().find('p').css('display', 'none');
        $(e).parent().parent().parent().parent().parent().parent().find('.btn-save').removeAttr('disabled');
    }
}
function edit_pto_hours(e) {
    let hours = 0;
    let remain = 0;
    let all_hours = parseInt($(e).parent().parent().parent().parent().parent().find('.pto_all_hours').val());
    $(e).parent().parent().parent().find('.pto_hours').each(function(index){
        hours+=parseInt($(this).val()) || 0;
    });
    if (hours>all_hours) {
        $(e).parent().find('p').css('display', 'block');
        $(e).parent().parent().parent().parent().parent().parent().find('.btn-save').attr('disabled', true);
    } else {
        $(e).parent().find('p').css('display', 'none');
        $(e).parent().parent().parent().parent().parent().parent().find('.btn-save').removeAttr('disabled');
        remain = all_hours - hours;
        let remain_date = Math.floor(remain/8);
        let remain_hours = remain - remain_date * 8;
        $(e).parent().parent().parent().parent().parent().find('.pto_remaining').text(remain_date+' Days, '+ remain_hours+' Hours');
    }
}

$('.search_clients').change(function () {
    const id = $('.search_clients').val();
    if (!id) {
        $('.search_workers').empty();
        return;
    }
    $.get("{{route('admin.client.allWorkers')}}",{"clients_id":id}).done(function( workers ) {
        let workerOptions = '<option value=""></option>';
        $('.search_workers').empty();
        workers.filter((worker)=>worker.id != 'all').forEach((worker) => {
            workerOptions += '<option value="'+worker.id+'">'+worker.fullname+'</option>';
        });
        $('.search_workers').html(workerOptions);
    });
});

</script>

@endsection
