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
                        <h3 class="bold">Invoices - Needs Finalized (Sys Gen)</h3>
                    </div>
                    <a href="{{route('admin.adjustment.create')}}" class="btn btn-warning pull-right"><i class="fa fa-refresh">&nbsp;&nbsp; To Adjustment</i></a>
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
                            <th>Invoice Finalized</th>
                            <th>Date In Queue</th>
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
                            <td><button class="btn btn-success btn-xs btn-send-{{$invoice->id}}" data-toggle="modal" data-target="#modal-send-{{$invoice->id}}" ><i class="fa fa-check">&nbsp;&nbsp; Yes</i></button>
                                @component('admin.invoices.components.finalized_modal')
                                @slot('id') 
                                    {{ $invoice->id }}
                                @endslot
                                @slot('from') 
                                    {{ 'automatic' }}
                                @endslot
                                @endcomponent 
                            <!-- <button class="btn btn-warning btn-xs btn-mark-{{$invoice->id}}" data-toggle="modal" data-target="#modal-mark-{{$invoice->id}}" ><i class="fa fa-check-square-o" data-toggle="tooltip" title="Mark as Sent"></i></button> -->
                                @component('admin.invoices.components.mark_invoice_modal')
                                @slot('id') 
                                    {{ $invoice->id }}
                                @endslot
                                @endcomponent
                            </td>
                            <td>{{date('m/d/y',strtotime($invoice->date_queue))}}</td>
                            <td>{{$invoice->invoice_number}}</td>
                            <td>{{$invoice->client()->client_name}}</td>
                            <td>{{date('m/d/y',strtotime($invoice->billing_cycle_end_date))}}</td>
                            <td>{{number_format($invoice->amount_updated!==null ? $invoice->amount_updated:$invoice->amount ,2)}} </td>
                             
                            <td><a href="{{route('admin.invoices.download',$invoice->id)}}">Download Invoice</a></td>
                            <td><input class="comments form-control" data="{{$invoice->id}}" style="margin-top: -5px" value="{{$invoice->comments}}"/></td>
                            <td>
                                <a href="/admin/editInvoice/{{$invoice->id}}?from=automatic" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-remove-invoice-{{$invoice->id}}"><i class="fa fa-close"></i></a>
                                @component('admin.invoices.components.removeinvoice')
                                @slot('id') 
                                    {{ $invoice->id }}
                                @endslot
                                @slot('from') 
                                    {{ 'automatic' }}
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

            <div class="col-md-12 col-lg-12 col-xs-12">
                <?php
                $clients=\App\Clients::where('status','active')->where('invoice_method','automatically')->get();
                foreach ($clients as $client) {
                    $timecard_ids=$client->timecards()->where(function ($query){ $query->where('status','needs_approval')->orwhere('status','pending_worker');})->groupBy('end_date')->get()->pluck('id')->toArray();
                    if(count($timecard_ids)==0) continue;
                    $activeWorkerIds = $client->assignedWorkers()->pluck('id')->toArray();
                    foreach ($timecard_ids as $timecard_id) {
                        $timecard=\App\TimeCards::where('id',$timecard_id)->first();
                        $timecards_not=count($client->timecards()->whereIn('workers_id', $activeWorkerIds)->where(function ($query){ $query->where('status','needs_approval')->orwhere('status','pending_worker');})->where('end_date',$timecard->end_date)->get());
                        $timecards_Approved=count($client->timecards()->where(function ($query){ $query->where('status','approved')->orwhere('status','Paid');})->where('end_date',$timecard->end_date)->get());
                        if($timecards_not>0 && $timecards_Approved>0){
                ?>
                <p><a href="{{route('admin.timesheet.pastTimesheets')}}">{{$timecards_Approved}} timesheet(s)</a> approved and <a href="{{route('admin.timesheet.pendingWorkerApproval')}}">{{$timecards_not}} timesheet(s)</a> awaiting approval for client [{{$client->client_name}}] at {{date('m/d/Y',strtotime($timecard->end_date))}}.</p>
                <?php
                }}}
                ?>
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

$('.comments').change(function(){
    var pid=$(this).attr('data');
    var comments=$(this).val();
    $.get("{{route('admin.updateCommets')}}",{'pid':pid,'comments':comments,'type':'invoice'}).done(function(data){
    });
}); 
</script>

@endsection
