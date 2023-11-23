@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important}  
.Worker-status-inactive{display: none}
.PTO-year{display: none}
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
                        {!! Form::open(['route' => 'account.pto.setfilter', 'class'=>'form-inline'])!!}
                            <input type="hidden" name="page" value="PTOsummary">
                            <span>&nbsp; Client:&nbsp;&nbsp;&nbsp;</span>
                            {!!  Form::select('clients_id',$search_clients,$clients_id, ['class' => 'search_clients','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}<br>
                            <span>&nbsp; Worker:</span>
                            {!!  Form::select('workers_id',$search_workers,$workers_id, ['class' => 'search_workers','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}
                            <span>&nbsp; Year:</span>
                            {!!  Form::select('year', $years, $year, ['class' => 'search_year','style'=>'margin-left:2px;width:80px']) !!}
                            &nbsp;&nbsp;<button type="submit" class="btn btn-success btn-xs btn-search" > <i class="fa fa-search"></i> Search</button> 
                            &nbsp;&nbsp;<a href="{{ route('account.pto.resetfilter') }}?page=PTOsummary" class="btn btn-danger btn-xs"> <i class="fa fa-times"></i> Clear</a>
                        {!! Form::close() !!}
                        </div>
                    </div>
                </div><br>

                @foreach ($pto_summaries as $pto_summary)
                <div class="Worker-status Worker-status-{{$pto_summary['status']}}">
                <div class="PTO-year PTO-year-{{$pto_summary['year']}}">
                <div class="Client-{{$pto_summary['client']}} Clients">
                    <div class="panel panel-default Worker-{{$pto_summary['worker']}} Worker">
                        <div class="panel-heading center">{{$pto_summary['title']}}</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>PTO Remaining:</label>
                                </div>
                                <div class="col-xs-8 form-group">
                                    <label>{{$pto_summary['pto_remaining']}}</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>PTO Used:</label>
                                </div>
                                <div class="col-xs-8 form-group">
                                    @foreach ($pto_summary['pto_used'] as $pto_used)
                                    <div class="row">
                                        <div class="col-xs-6 form-group">
                                            <label>{{date('m/d/y',strtotime($pto_used['date']))}} </label>
                                        </div>
                                        <div class="col-xs-6 form-group">
                                            <label>{{$pto_used['hours']}} Hours</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
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

$('.btn-search').click(function(){
    var client=$('.search_client').val();
    var worker=$('.search_worker').val();
    var status=$('.search_status').val();
    var year=$('.search_year').val();

    if (client==0){
        $('.Clients').css('display','block');
    }else{
        $('.Clients').css('display','none');
        $('.Client-'+client).css('display','block');
    }
    if (worker==0){
        $('.Worker').css('display','block');
    }else{
        $('.Worker').css('display','none');
        $('.Worker-'+worker).css('display','block');
    }

    $('.Worker-status').css('display','none');    
    $('.PTO-year').css('display','none');
    $('.Worker-status-'+status).css('display','block');
    $('.PTO-year-'+year).css('display','block');
});

$('.search_clients').change(function () {
    const id = $('.search_clients').val();
    if (!id) {
        $('.search_workers').empty();
        return;
    }
    $.get("{{route('account.client.allWorkers')}}",{"clients_id":id}).done(function( workers ) {
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
