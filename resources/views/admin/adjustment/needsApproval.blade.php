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
                        <h3 class="bold">Needs Approval Adjustment - One - Time</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                <?php $h=0; ?>
                @foreach ($onetime_adjustments as $adjustment) 
                <?php $h++; ?>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading{{$h}}">
                        <div class="panel-title ">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$h}}" aria-expanded="true" aria-controls="collapse{{$h}}">
                            <h4><i class="fa fa-plus-square"></i> 
                                Pay To: {{$adjustment->payto}}
                                {{$adjustment->payto=='Client' ? '=>'.$paytoclient[$adjustment->clients_id]:''}}
                                {{$adjustment->payto=='Worker' ? '=>'.$paytoworker[$adjustment->workers_id]:''}}
                                  , 
                                Bill To: {{$adjustment->billto}}
                                {{$adjustment->billto=='Client' ? '=>'.$paytoclient[$adjustment->clients_id]:''}}
                                {{$adjustment->billto=='Worker' ? '=>'.$paytoworker[$adjustment->workers_id]:''}}
                                  , 
                                Hours/Amount: @if ($adjustment->type=='Time Adjustment'){{$adjustment->adjustment_total_hours}} @else
                                {{$adjustment->other_amount}} @endif
                                  ,
                                Type: {{$adjustment->type}}
                            </h4>
                        </a>
                        </div>
                        <div id="collapse{{$h}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$h}}">    
                            <div class="row">
                                <div class="panel-body" style="overflow-x:scroll">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr class="warning">
                                        <th>Reference Number</th>
                                        <th>Date Submitted</th>
                                        <th>Pay To</th>
                                        <th>Pay To - Client</th>
                                        <th>Pay To - Worker</th>
                                        <th>Bill To</th>
                                        <th>Bill To - Client</th>
                                        <th>Bill To - Worker</th>
                                        <th>Type</th>
                                        <th>Time Adjustment - Date</th>
                                        <th>Time Adjustment - Total Hours</th>
                                        <th>Other - Description</th>
                                        <th>Other - Amount</th>
                                        <th>Other - Currency Type</th>
                                        <th>Internal Notes</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody class="add_Reference">
                                    
                                    <tr>
                                        <td>{{$adjustment->id}}</td>
                                        <td>{{date('m/d/y',strtotime($adjustment->date_submitted))}}</td>
                                        <td>{{$adjustment->payto}}</td>
                                        <td>
                                            @if($adjustment->paytoclient && isset($paytoclient[$adjustment->paytoclient]))
                                            {{$paytoclient[$adjustment->paytoclient]}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($adjustment->paytoworkert && isset($paytoworker[$adjustment->paytoworker]))
                                            {{$paytoworker[$adjustment->paytoworker]}}
                                            @endif
                                        </td>
                                        <td>{{$adjustment->billto}}</td>
                                        <td>
                                            @if($adjustment->billtoclient && isset($billtoclient[$adjustment->billtoclient]))
                                            {{$billtoclient[$adjustment->billtoclient]}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($adjustment->billtoworker && isset($billtoworker[$adjustment->billtoworker]))
                                            {{$billtoworker[$adjustment->billtoworker]}}
                                            @endif
                                        </td>
                                        <td>{{$adjustment->type}}</td>
                                        <td>
                                            @if ($adjustment->adjustment_date) {{date('m/d/y',strtotime($adjustment->adjustment_date))}}
                                            @endif
                                        </td>
                                        <td>{{$adjustment->adjustment_total_hours}}</td>
                                        <td>{{$adjustment->other_description}}</td>
                                        <td>{{$adjustment->other_amount}}</td>
                                        <td>{{$adjustment->other_currency}}</td>
                                        <td>{{$adjustment->internal_notes}}</td>
                                        <td>{{$adjustment->status}}</td>
                                    </tr>
                                    
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                            @if ($adjustment->type=='Internet - Backup' || $adjustment->type=='Internet - Primary')
                            <div class="row">
                                <div class="col-md-6 form-group Internet_tpye">
                                    <label>Internet Service Provider:</label>
                                    <input name="internet_service_provider" class="form-control" type="text" value="{{$adjustment->reimbursement()->internet_service_provider}}" >
                                </div>
                                
                                <div class="col-md-6 form-group Internet_tpye">
                                    <label>Date of Statement:</label>
                                    <input type="date" name="statement_date" class="form-control" value="{{$adjustment->reimbursement()->statement_date}}">
                                </div>
                                <?php 
                                $statement_included=[
                                    'yes'=>'Yes',
                                    'no'=>'No',
                                ];
                                ?>
                                <div class="col-md-6 form-group Internet_tpye">
                                    <label>Reimbursement for this statement is already included with my bi-weekly payment:</label>
                                    {!!  Form::select('statement_included',$statement_included,$adjustment->reimbursement()->statement_included, ['class' => 'form-control' ]) !!}
                               </div>
                             
                                <div class="col-md-6 form-group filegroup" style="margin-top: 20px">
                                    <label>Upload Copy of Statement or Receipt:</label>
                                    <input name="copy_statement_file" type="file" id="files" value="" class="form-control file" placeholder="no">
                                    
                                </div>
                                 
                            </div>
                            @endif
                            <div class="row">
                                 
                                <div class="col-md-12">
                                    <a href="#" class="btn btn-danger pull-right" style="margin-top: 25px"><i class="" data-toggle="modal" data-target="#modal-decline-{{$adjustment->id}}">Decline</i></a>
                                    
                                    <a href="#" class="btn btn-success pull-right" style="margin-top: 25px"><i class="" data-toggle="modal" data-target="#modal-approve-{{$adjustment->id}}">Approve</i></a>
                                        @component('admin.adjustment.components_onetime.approvemodal')
                                        @slot('id') 
                                            {{ $adjustment->id }}
                                        @endslot
                                        @endcomponent
                                        @component('admin.adjustment.components_onetime.declinemodal')
                                        @slot('id') 
                                            {{ $adjustment->id }}
                                        @endslot
                                        @endcomponent
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Needs Approval Adjustment - Recurring</h3>
                    </div>
                </div>
            </div>
             
            <div class="col-md-12 col-lg-12 col-xs-12">
                @foreach ($recurring_adjustments as $adjustment) 
                <?php $h++; ?>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading{{$h}}">
                        <div class="panel-title ">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$h}}" aria-expanded="true" aria-controls="collapse{{$h}}">
                            <h4><i class="fa fa-plus-square"></i> 
                                Pay To: {{$adjustment->payto}}
                                {{$adjustment->payto=='Client' && isset($paytoclient[$adjustment->clients_id]) ? '=>'.$paytoclient[$adjustment->clients_id]:''}}
                                {{$adjustment->payto=='Worker' ? '=>'.$paytoworker[$adjustment->workers_id]:''}}
                                  , 
                                Bill To: {{$adjustment->billto}}
                                {{$adjustment->billto=='Client' && isset($paytoclient[$adjustment->clients_id]) ? '=>'.$paytoclient[$adjustment->clients_id]:''}}
                                {{$adjustment->billto=='Worker' ? '=>'.$paytoworker[$adjustment->workers_id]:''}}
                                  , 
                                Amount:  
                                {{$adjustment->amount}}  
                            </h4>
                        </a>
                        </div>
                        <div id="collapse{{$h}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$h}}">    
                            <div class="row">
                                <div class="panel-body" style="overflow-x:scroll">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr class="warning">
                                        <th>Reference Number</th>
                                        <th>Date Submitted</th>
                                        <th>Pay To</th>
                                        <th>Pay To - Client</th>
                                        <th>Pay To - Worker</th>
                                        <th>Bill To</th>
                                        <th>Bill To - Client</th>
                                        <th>Bill To - Worker</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Currency Type</th>
                                        <th>Internal Notes</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody class="add_Reference">
                                    
                                    <tr>
                                        <td>{{$adjustment->id}}</td>
                                        <td>{{date('m/d/y',strtotime($adjustment->date_submitted))}}</td>
                                        <td>{{$adjustment->payto}}</td>
                                        <td>
                                            @if($adjustment->paytoclient && isset($paytoclient[$adjustment->paytoclient]))
                                            {{$paytoclient[$adjustment->paytoclient]}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($adjustment->paytoworker && isset($paytoworker[$adjustment->paytoworker]))
                                            {{$paytoworker[$adjustment->paytoworker]}}
                                            @endif
                                        </td>
                                        <td>{{$adjustment->billto}}</td>
                                        <td>
                                            @if($adjustment->billtoclient && isset($billtoclient[$adjustment->billtoclient]))
                                            {{$billtoclient[$adjustment->billtoclient]}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($adjustment->billtoworker && isset($billtoworker[$adjustment->billtoworker]))
                                            {{$billtoworker[$adjustment->billtoworker]}}
                                            @endif
                                        </td>
                                        <td>{{$adjustment->description}}</td>
                                        <td>{{$adjustment->amount}}</td>
                                        <td>{{$adjustment->currency_type}}</td>
                                        <td>{{$adjustment->internal_notes}}</td>
                                        <td>{{$adjustment->status}}</td>
                                    </tr>
                                    
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                 
                                <div class="col-md-12">
                                    <a href="#" class="btn btn-danger pull-right" style="margin-top: 25px"><i class="" data-toggle="modal" data-target="#modal-decline-r{{$adjustment->id}}">Decline</i></a>
                                    
                                    <a href="#" class="btn btn-success pull-right" style="margin-top: 25px"><i class="" data-toggle="modal" data-target="#modal-approve-r{{$adjustment->id}}">Approve</i></a>
                                        @component('admin.adjustment.components_recurring.approvemodal')
                                        @slot('id') 
                                            {{ $adjustment->id }}
                                        @endslot
                                        @endcomponent
                                        @component('admin.adjustment.components_recurring.declinemodal')
                                        @slot('id') 
                                            {{ $adjustment->id }}
                                        @endslot
                                        @endcomponent
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
<script src="/vendor/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
<script>

$(function () {
    var anchor = window.location.hash;
    //console.log(anchor);
    if (anchor.length >0 ) {
        $(".collapse").collapse('hide');
        $(anchor).collapse('show'); 
    }
    $('.collapse').on('shown.bs.collapse', function(){
        $(this).parent().find("i.fa-plus-square").removeClass("fa-plus-square").addClass("fa-minus-square");
    }).on('hidden.bs.collapse', function(){
        $(this).parent().find(".fa-minus-square").removeClass("fa-minus-square").addClass("fa-plus-square");
    });

    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
    $('.btn-submit-approve').click(function() {
        $(this).css('pointer-events','none');
        $(this).css('opacity','0.5');
    });
});

$(":file").filestyle();
$("input[type='file']").attr('accept', '.pdf,.jpg,.jpeg,.tiff,.tif,.doc,.xls,.docx,.xlsx');
$(".bootstrap-filestyle input").attr('placeholder','No file chosen.');
$(".bootstrap-filestyle input").css('text-align','center');
</script>

@endsection
