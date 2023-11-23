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
                        <h3 class="bold">Past Requests </h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                 

                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Past PTO Requests</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr class="">
                                        <th width="25%">Client</th>
                                        <th width="25%">Date</th>
                                        <th width="25%">Hours</th>
                                        <th width="25%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($ptos as $pto)
                                    <tr>
                                        <td>{{$clients_list[$pto->clients_id]}}</td>
                                        <td>{{date('m/d/y',strtotime($pto->date_pto))}}</td>
                                        <td>{{$pto->total_hours}}</td>
                                        <td>{{$pto->status}}</td>
                                    </tr>
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
    $('#detail_table').DataTable({"order": [1, 'desc']});
});

</script>

@endsection
