@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important}  
</style>
@endsection

@section('content')


    <section id="PayrollImmediatePay">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Payments - View Closed</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    {!! Form::open(['route' => 'payroll.payroll.setfilter', 'class'=>'form-inline'])!!}
                        <input type="hidden" name="page" value="viewClosed">
                        <span>&nbsp; Client:&nbsp;&nbsp;&nbsp;</span>
                        {!!  Form::select('clients_id',$search_clients,$clients_id, ['class' => 'search_clients','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}<br>
                        <span>&nbsp; Worker:</span>
                        {!!  Form::select('workers_id',$search_workers,$workers_id, ['class' => 'search_workers','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}
                        &nbsp;&nbsp;<button type="submit" class="btn btn-success btn-xs btn-search" > <i class="fa fa-search"></i> Search</button> 
                        &nbsp;&nbsp;<a href="{{ route('payroll.payroll.resetfilter') }}?page=viewClosed" class="btn btn-danger btn-xs"> <i class="fa fa-times"></i> Clear</a>
                    {!! Form::close() !!}
                    </div>
                </div>
            </div><br>

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
                            <th>Date Paid</th>
                            <th>Client Name</th>
                            <th>Worker Name</th>
                            <th>Email Address</th>
                            <th>Amount</th>
                            <th>Currency Type</th>
                            <th>Payment Summary</th>
                            <th>Comments</th>
                            <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment) 
                        <tr class="payment_{{$payment->id}}">
                            <td>{{date('m/d/y',strtotime($payment->date_paid))}}</td>
                            <td>{{isset($clients[$payment->clients_id]) ? $clients[$payment->clients_id] : ''}}</td>
                            <td>{{isset($workersNameList[$payment->workers_id]) ? $workersNameList[$payment->workers_id] : ''}}</td>
                            <td>
                                @if (isset($workersMainEmailList[$payment->workers_id]) || isset($workersVeemEmailList[$payment->workers_id]))
                                <a>{{isset($workersVeemEmailList[$payment->workers_id]) ? $workersVeemEmailList[$payment->workers_id] : $workersMainEmailList[$payment->workers_id]}}</a>
                                @endif
                            </td>
                            <td>{{number_format($payment->amount_updated!==null ? $payment->amount_updated: $payment->amount,2)}}</td>
                            <td>{{strtoupper($payment->currency_type)}}</td>
                            <td><a href="{{route('payroll.payroll.viewReport',$payment->id)}}" target="_blank">View Report</a></td>
                            <td>{{$payment->comments}}</td>
                            <td>
                                <a href="/payrollManager/editPayment/{{$payment->id}}?from=viewClosed" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete-{{$payment->id}}"><i class="fa fa-close"></i></a>
                                 
                                @component('payroll.payroll.components.removepayment')
                                @slot('id') 
                                    {{ $payment->id }}
                                @endslot
                                @slot('from') 
                                    {{ 'viewClosed' }}
                                @endslot
                                @endcomponent
                            </td>
                        </tr>
                        @endforeach
                         
                        </tbody>

                        </table>
                    </div> 
                </div>

                                    
            </div>
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $('#detail_table').DataTable();
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
});

function sendSummary(e){
    var pid=$(e).attr('data');
    $.get("{{route('payroll.payroll.sendSummary')}}",{'id':pid}).done(function(data){
        if(data=="success"){
            $('.btn-pay-'+pid).removeAttr('disabled');
            $('.btn-send-summary-'+pid).attr('disabled',true);
            
        };
    });
}
$('.search_clients').change(function () {
    const id = $('.search_clients').val();
    if (!id) {
        $('.search_workers').empty();
        return;
    }
    $.get("{{route('payroll.client.activeWorkers')}}",{"clients_id":id}).done(function( workers ) {
        let workerOptions = '<option value=""></option>';
        $('.search_workers').empty();
        workers.forEach((worker) => {
            workerOptions += '<option value="'+worker.id+'">'+worker.fullname+'</option>';
        });
        $('.search_workers').html(workerOptions);
    });
});
</script>

@endsection
