@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
 

<style>

.canvasjs-chart-credit{
    display:none !important;
}
.btn-real{
    background-color:#058dc7 !important;
    border:none;
}
td,th{
    border:1px lightgrey solid !important}
.worktime{border:none;margin:0px;background-color:#fcf8e3;text-align:center}
.lunchtime{border:none;margin:0px;background-color:#d9edf7;text-align:center}
.breaktime{border:none;margin:0px;background-color:#dff0d8;text-align:center}
.notes{border:none;margin:0px;background-color:#f2dede;text-align:center}
.input-sm{width: 100px !important} 
</style>
@endsection

@section('content')


    <section id="LogTime">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">PTO - Needs Approval</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif


            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <div class="row" style="padding: 10px 0px 40px 0px;overflow-x: scroll;">
                        <div class="box-body table_group">
                            <table id="detail_table" class="table table-hover text-center table-bordered">
                            <thead>
                            <tr>
                                <th width="10%">Worker</th>
                                <th width="20%">Date</th>
                                <th width="10%">Client</th>
                                <th width="10%">Hours</th>
                                <th width="30%">Reason</th>
                                <th width="20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($workers as $worker)
                            @foreach($worker->ptos()->where('status','Pending Approval - BrightDrop')->get() as $pto)
                            <tr>
                                <td>{{$worker->fullname()}}</td>
                                <td>{{date('m/d/y',strtotime($pto->date_pto))}}</td>
                                <td>{{\App\Clients::where('id',
                                    $pto->clients_id)->first()->client_name}}</td>
                                <td>{{$pto->total_hours}}</td>
                                <td>{{$pto->reason}}</td>
                                <td>
                                    @component('admin.pto.components.approvemodal')
                                    @slot('id') 
                                        {{ $pto->id }}
                                    @endslot
                                    @endcomponent

                                    @component('admin.pto.components.declinemodal')
                                    @slot('id') 
                                        {{ $pto->id }}
                                    @endslot
                                    @endcomponent
                                    
                                    
                                </td>
                            </tr>
                           
                            @endforeach
                            @endforeach
                            
                            </tbody>

                            </table>
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
