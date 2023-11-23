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
</style>
@endsection

@section('content')


    <section id="NeedsApproval">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Past Reimbursements Table</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif

             
            <div class="col-md-12 col-lg-12 col-xs-12">
                     
                <div class="panel-body">
                    <div class="row">
                        <div class="box-body table_group"  style="overflow-x:scroll">
                            <table id="detail_table" class="table table-hover text-center table-bordered">
                            <thead>
                            <tr>
                                <th>Reference Number</th>
                                <th>Date</th>
                                <th>Worker</th>
                                <th>Client</th>
                                <th>Reimbursement Type</th>
                                <th>Amount</th>
                                <th>Currency Type</th>
                                <th>Bill To</th>
                                <th>Payment Method</th>
                                <th>Status</th>

                            </tr>
                            </thead>
                            <tbody>
                            
                             
                            @foreach($reimbursementss as $reimbursement)
                            <?php 
                            $client=\App\Clients::where('id',$reimbursement->clients_id)->first();
                            $worker=\App\Workers::where('id',$reimbursement->workers_id)->first();
                            ?>
                            <tr>
                                <td>{{$reimbursement->id}}</td>
                                <td>{{date('m/d/y',strtotime($reimbursement->date))}}</td>
                                <td>{{$worker->fullname()}}</td>
                                <td>{{$client->client_name}}</td>
                                <td>{{$reimbursement->type}}</td>
                                <td>{{$reimbursement->amount}}</td>
                                <td>{{strtoupper($reimbursement->currency_type)}}</td>
                                <td>{{$reimbursement->bill_to}}</td>
                                <td>{{$reimbursement->payment_method}}</td>
                                <td>{{$reimbursement->status}}</td>
                            </tr>
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
    $('#detail_table').dataTable();
     
});

 

</script>

@endsection
