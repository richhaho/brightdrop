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
                        <h3 class="bold">Payments - Bi-Weekly (Veem)</h3>
                    </div>
                </div>
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
                            <th>Payment Sent</th>
                            <th>Date In Queue</th>
                            <th>Client Name</th>
                            <th>Worker Name</th>
                            <th>Email Address</th>
                            <th>Total Payment</th>
                            <th>Currency Type</th>
                            <th>Payment Summary</th>
                            <th>Comments</th>
                            <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                        <?php 
                            $style=$payment->currency_type=='php' ? '':'background-color:#FFE4E1';
                        ?>
                        <tr class="payment_{{$payment->id}}" style="{{$style}}">
                            <td>
                                <button class="btn btn-success btn-xs btn-pay-{{$payment->id}}" data-toggle="modal" data-target="#modal-pay-{{$payment->id}}" ><i class="fa fa-money">&nbsp;&nbsp; Yes</i></button>
                                @component('payroll.payroll.components.paymodal')
                                @slot('id') 
                                    {{ $payment->id }}
                                @endslot
                                @slot('from') 
                                    {{ 'biWeeklyVeem' }}
                                @endslot
                                @endcomponent
                            </td>
                            <td>{{date('m/d/y',strtotime($payment->date_queue))}}</td>
                            <td>{{$payment->client()->client_name}}</td>
                            <td>{{$payment->worker()->fullname}}</td>
                            <td><a>{{$payment->worker()->email_veem ? $payment->worker()->email_veem : $payment->worker()->email_main}}</a></td>
                            <td>{{number_format($payment->amount_updated!==null ? $payment->amount_updated: $payment->amount,2)}}</td>
                            <td>{{strtoupper($payment->currency_type)}}</td>
                            <td><a href="{{route('payroll.payroll.viewReport',$payment->id)}}" target="_blank">View Report</a></td>
                            <td><input class="comments form-control" data="{{$payment->id}}" value="{{$payment->comments}}" style="margin-top:-5px"/></td>
                            <td>
                                <a href="/payrollManager/editPayment/{{$payment->id}}?from=biWeeklyVeem" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                <a href="#" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-change-{{$payment->id}}"><i class="fa fa-exchange"></i></a>
                                <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete-{{$payment->id}}"><i class="fa fa-close"></i></a>
                                 
                                @component('payroll.payroll.components.removepayment')
                                @slot('id') 
                                    {{ $payment->id }}
                                @endslot
                                @slot('from') 
                                    {{ 'biWeeklyVeem' }}
                                @endslot
                                @endcomponent
                                @component('payroll.payroll.components.changemodal')
                                @slot('id') 
                                    {{ $payment->id }}
                                @endslot
                                @slot('from') 
                                    {{ 'Veem' }}
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
    $('[data-toggle="tooltip"]').tooltip(); 
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
function markAsSent(e){
    var pid=$(e).attr('data');
    $('.btn-pay-'+pid).removeAttr('disabled');
    $('.btn-send-summary-'+pid).attr('disabled',true);
}
$('.comments').change(function(){
    var pid=$(this).attr('data');
    var comments=$(this).val();
    $.get("{{route('payroll.updateCommets')}}",{'pid':pid,'comments':comments,'type':'payment'}).done(function(data){
    });
});
$('.btn-pay-payment').click(function() {
    $(this).attr('disabled',true)
    $(this).parent().submit()
});
</script>

@endsection
