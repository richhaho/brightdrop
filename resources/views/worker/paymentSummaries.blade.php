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


    <section id="PaymentSummary">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Payment Summaries</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="alert-modal">
                    <div class="modal">
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        
                                        <div class="row" style="padding: 10px 0px 40px 0px;">
                                            <div class="box-body table_group">
                                                <table id="detail_table" class="table table-hover text-center table-bordered">
                                                <thead>
                                                <tr>
                                                    <th width="20%">Payment Date</th>
                                                    <th width="30%">Client</th>
                                                    <th width="30%">Link</th>
                                                    <th width="20%">Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($payments as $payment)
                                                <tr>
                                                    <td>{{date('m/d/y',strtotime($payment->date_queue))}}</td>
                                                    <td>{{$payment->client() ? $payment->client()->client_name:'Brightdrop'}}</td>
                                                    <td><a href="{{route('worker.downloadpdf',$payment->id)}}">Download Payment Summary</a></td>
                                                    <td>{{$payment->status}}</td>
                                                </tr>
                                                @endforeach

                                                @foreach($worker->timecards()->where('status','needs_approval')->get() as $timecard)
                                                <tr>
                                                    <td>{{date('m/d/y',strtotime($timecard->handle_date))}}</td>
                                                    <td>{{$timecard->client()->client_name}}</td>
                                                    <td><a href="{{route('worker.downloadpdf',$timecard->id)}}?type='timecard'">Download Payment Summary</a></td>
                                                    <td>Pending</td>
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
                </div>
            </div>
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $('#detail_table').DataTable({"order": [0, 'desc']});
});

</script>

@endsection
