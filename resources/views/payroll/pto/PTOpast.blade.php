@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important}   
</style>
@endsection

@section('content')


    <section id="PTO">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Past PTO Requests </h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">PTO Requests</div>
                        <div class="panel-body">
                            <div class="row" style="overflow-x: scroll;">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr class="">
                                        <th width="20%">Worker</th>
                                        <th width="20%">Client</th>
                                        <th width="20%">Date</th>
                                        <th width="10%">Hours</th>
                                        <th width="30%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($ptos as $pto_a)
                                    @foreach ($pto_a as $pto)
                                    @if (isset($clients_list[$pto->clients_id]))
                                    <tr>
                                        <td>{{$workers_list[$pto->workers_id]}}</td>
                                        <td>{{$clients_list[$pto->clients_id]}}</td>
                                        <td>{{date('m/d/y',strtotime($pto->date_pto))}}</td>
                                        <td>{{$pto->total_hours}}</td>
                                        <td>{{$pto->status}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endforeach

                                    </tbody>

                                    </table>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                 
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
});
</script>

@endsection
