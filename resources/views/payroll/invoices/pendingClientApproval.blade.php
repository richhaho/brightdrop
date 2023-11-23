@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important}  
</style>
@endsection

@section('content')


    <section id="Invoice">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Invoices - Pending Client Approval</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="col-xs-12" style="padding: 10px 0px 40px 0px">
                    <div class="box-body table_group" style="overflow-x:scroll">
                        <table id="detail_table" class="table table-hover text-center table-bordered">
                        <thead>
                        <tr>
                            <th>Invoice Approved by Client</th>
                            <th>Date In Queue</th>
                            <th>Review Time</th>
                            <th>Invoice #</th>
                            <th>Client Name</th>
                            <th>Billing Cycle - End Date</th>
                            <th>Amount</th>
                             
                            <th>Invoice</th>
                            <th>Comments</th>
                            <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th> 
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                        <tr class="line">
                            <td><button class="btn btn-success btn-xs btn-approve-{{$invoice->id}}" data-toggle="modal" data-target="#modal-approve-{{$invoice->id}}" ><i class="fa fa-check">&nbsp;&nbsp; Approve</i></button>
                                @component('payroll.invoices.components_pending.approvemodal')
                                @slot('id') 
                                    {{ $invoice->id }}
                                @endslot
                                @endcomponent</td>
                            <td>{{date('m/d/y',strtotime($invoice->date_queue))}}</td>
                            <td>
                                <?php 
                                    $review_time=[
                                        ''=>'',
                                        'auto'=>'Auto Approve',
                                        '1'=>'1 business day',
                                        '2'=>'2 business days',
                                        '3'=>'3 business days',
                                        '4'=>'4 business days',            
                                        '5'=>'5 business days'
                                    ];
                                ?>
                                {{$review_time[$invoice->client()->review_time]}}
                            </td>
                            <td>{{$invoice->invoice_number}}</td>
                            <td>{{$invoice->client()->client_name}}</td>
                            <td>{{date('m/d/y',strtotime($invoice->billing_cycle_end_date))}}</td>
                            <td>{{number_format($invoice->amount_updated!==null ? $invoice->amount_updated:$invoice->amount,2)}} </td>
                             
                            <td><a href="{{route('payroll.invoices.download',$invoice->id)}}">Download Invoice</a></td>
                            <td><input class="comments form-control" data="{{$invoice->id}}" style="margin-top: -5px" value="{{$invoice->comments}}"/></td>
                            <td>
                                <a href="/payrollManager/editInvoice/{{$invoice->id}}?from=pendingClientApproval" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-remove-invoice-{{$invoice->id}}"><i class="fa fa-close"></i></a>
                                @component('payroll.invoices.components.removeinvoice')
                                @slot('id') 
                                    {{ $invoice->id }}
                                @endslot
                                @slot('from') 
                                    {{ 'pendingClientApproval' }}
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
$('.comments').change(function(){
    var pid=$(this).attr('data');
    var comments=$(this).val();
    $.get("{{route('payroll.updateCommets')}}",{'pid':pid,'comments':comments,'type':'invoice'}).done(function(data){
    });
});
</script>

@endsection
