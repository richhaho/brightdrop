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
                        <h3 class="bold">Reimbursement Requests</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
<?php 
$i=0;
foreach ($clients as $client) {
foreach ($client->workers() as $worker) {
$i++;
$reimbursements=$worker->reimbursements()->where('clients_id',$client->id)->where('status','Pending')->get();
if (count($reimbursements)==0) continue;
$amounts=$worker->reimbursements()->where('clients_id',$client->id)->where('status','Pending')->sum('amount');
?>            
            {!! Form::open(['route' => 'account.submit_reimbursementApproval','autocomplete' => 'off']) !!}
            <div class="col-md-12 col-lg-12 col-xs-12">
                 
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading{{$i}}">
                        <div class="panel-title ">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}" aria-expanded="true" aria-controls="collapse{{$i}}">
                            <h4><i class="fa fa-plus-square"></i> Worker: {{$worker->fullname()}}, Client: {{$client->client_name}}, Amount: {{$amounts}} </h4>
                            <input type="hidden" name="client_id" value="{{$client->id}}">
                            <input type="hidden" name="worker_id" value="{{$worker->id}}">
                        </a>
                        </div>
                        <div id="collapse{{$i}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$i}}">    
                            <div class="row">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr class="warning">
                                        <th width="10%">Worker</th>
                                        <th width="10%">Client</th>
                                        <th width="15%">Date</th>
                                        <th width="15%">Amount</th>
                                        <th width="15%">Currency Type</th>
                                        <th width="15%">Reimbursement Type</th>
                                        <th width="20%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reimbursements as $reimbursement)
                                    <input type="hidden" name="reimbursement[{{$reimbursement->id}}]" value="{{$reimbursement->id}}">
                                    <tr>
                                        <td>{{$worker->fullname()}}</td>
                                        <td>{{$client->client_name}}</td>
                                        <td>{{date('m/d/y',strtotime($reimbursement->date))}}</td>
                                        <td>{{$reimbursement->amount}}</td>
                                        <td>{{strtoupper($reimbursement->currency_type)}}</td>
                                        <td>{{$reimbursement->type}}</td>
                                        <td>{{$reimbursement->status}}</td>
                                    </tr>
                                    @endforeach

                                    </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6 form-group ApproveDecline">
                                            <label>Bill To:</label>
                                            {!!  Form::select('bill_to',$bill_to,old("bill_to"), ['class' => 'form-control ' ]) !!}
                                        </div>
                                        <div class="col-md-6 form-group ApproveDecline">
                                            <label>Payment Method:</label>
                                            {!!  Form::select('payment_method',$payment_method,old("payment_method"), ['class' => 'form-control ' ]) !!}
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label>Additional Notes (Acct Manager):</label>
                                            <input type="text" class="form-control" name="additional_notes_account">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <a href="#" class="btn btn-danger pull-right" data-toggle="modal" data-target="#modal-decline-{{$i}}"><i class="fa fa-save"></i> Decline</a>
                                    <div class="modal fade" id="modal-decline-{{$i}}" tabindex="-1" role="dialog">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Decline Reimbursement Requests</h4>
                                          </div>
                                          <div class="modal-body">
                                              <p>Are you sure you want to decline?</p>
                                          </div>
                                          <div class="modal-footer">
                                                <a href="{{route('account.decline_reimbursementApproval')}}?id={{implode('-',$reimbursements->pluck('id')->toArray())}}" class="btn btn-warning"> Decline</a>&nbsp;&nbsp;
                                                <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                                          </div>
                                        </div><!-- /.modal-content -->
                                      </div><!-- /.modal-dialog -->
                                    </div>
                                    <a href="#" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-submit-{{$i}}"><i class="fa fa-save"></i> Approve</a>
                                    <div class="modal fade" id="modal-submit-{{$i}}" tabindex="-1" role="dialog">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Submit Reimbursement Requests</h4>
                                          </div>
                                          <div class="modal-body">
                                              <p>Are you sure you want to submit?</p>
                                          </div>
                                          <div class="modal-footer">
                                                <button type="submit" class="btn btn-success"> Submit</button>&nbsp;&nbsp;
                                                <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                                          </div>
                                        </div><!-- /.modal-content -->
                                      </div><!-- /.modal-dialog -->
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                  
            </div>
            {!! Form::close() !!}
<?php }} ?>
            
            
         
            
        </div>
    </section>

<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });

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
});

function changeApproveDecline(){
    if ($('.changeApproveDecline').val()=='Approved'){
        $('.ApproveDecline').css('display','block');
    }else{
        $('.ApproveDecline').css('display','none');
    }
}
function changeApproveDecline2(){
    if ($('.changeApproveDecline2').val()=='1'){
        $('.ApproveDecline2').css('display','block');
    }else{
        $('.ApproveDecline2').css('display','none');
    }
}

</script>

@endsection
