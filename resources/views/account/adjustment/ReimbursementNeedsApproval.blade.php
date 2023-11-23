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
            @foreach($reimbursements as $reimbursement)
            {!! Form::open(['route' => 'account.submit_reimbursementApproval','autocomplete' => 'off']) !!}
            <div class="col-md-12 col-lg-12 col-xs-12">
                 
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading{{$reimbursement->id}}">
                        <div class="panel-title ">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$reimbursement->id}}" aria-expanded="true" aria-controls="collapse{{$reimbursement->id}}">
                            <h4><i class="fa fa-plus-square"></i> Worker: {{$reimbursement->worker()->fullname()}}, Client: {{$reimbursement->client()->client_name}}, Amount: {{$reimbursement->amount}} </h4>
                            <input type="hidden" name="client_id" value="{{$reimbursement->client()->id}}">
                            <input type="hidden" name="worker_id" value="{{$reimbursement->worker()->id}}">
                        </a>
                        </div>
                        <div id="collapse{{$reimbursement->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$reimbursement->id}}">    
                            <div class="row" style="overflow-x: scroll;">
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
                                        <th width="10%">Status</th>
                                        <th width="10%">Statement or Receipt</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <input type="hidden" name="reimbursement_id" value="{{$reimbursement->id}}">
                                    <tr>
                                        <td>{{$reimbursement->worker()->fullname()}}</td>
                                        <td>{{$reimbursement->client()->client_name}}</td>
                                        <td>{{date('m/d/y',strtotime($reimbursement->date))}}</td>
                                        <td>{{$reimbursement->amount}}</td>
                                        <td>{{strtoupper($reimbursement->currency_type)}}</td>
                                        <td>{{$reimbursement->type}}</td>
                                        <td>{{$reimbursement->status}}</td>
                                        <td>
                                            @if($reimbursement->copy_statement_file)
                                            <a href="{{route('account.downloadReimbursement')}}?id={{$reimbursement->id}}&filename={{$reimbursement->copy_statement_file}}"><i class="fa fa-download"></i> Download</a>
                                            @else
                                            No Uploaded
                                            @endif
                                        </td>
                                    </tr>
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
                                            <input type="text" class="form-control" name="additional_notes_account" value="{{$reimbursement->additional_notes}}">
                                        </div>
                                    </div>
                                    @if ($reimbursement->type=='Internet - Backup' || $reimbursement->type=='Internet - Primary')
                                    <div class="row">
                                        <div class="col-md-6 form-group Internet_tpye">
                                            <label>Internet Service Provider:</label>
                                            <input name="internet_service_provider" class="form-control" type="text" value="{{$reimbursement->internet_service_provider}}" >
                                        </div>
                                        
                                        <div class="col-md-6 form-group Internet_tpye">
                                            <label>Date of Statement:</label>
                                            <input type="date" name="statement_date" class="form-control" value="{{$reimbursement->statement_date}}">
                                        </div>
                                        
                                        <div class="col-md-6 form-group Internet_tpye">
                                            <label>Reimbursement for this statement is already included with my bi-weekly payment:</label>
                                            {!!  Form::select('statement_included',$statement_included,$reimbursement->statement_included, ['class' => 'form-control' ]) !!}
                                       </div>
                                     
                                        <div class="col-md-6 form-group filegroup" style="margin-top: 20px">
                                            <label>Upload Copy of Statement or Receipt:</label>
                                            <input name="copy_statement_file" type="file" id="files" value="" class="form-control file" placeholder="no">
                                            
                                        </div>
                                         
                                    </div>
                                    @endif

                                </div>
                                <div class="col-md-12">
                                    <a href="#" class="btn btn-danger pull-right" data-toggle="modal" data-target="#modal-decline-{{$reimbursement->id}}"><i class="fa fa-save"></i> Decline</a>
                                    <div class="modal fade" id="modal-decline-{{$reimbursement->id}}" tabindex="-1" role="dialog">
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
                                                <a href="{{route('account.decline_reimbursementApproval')}}?id={{$reimbursement->id}}" class="btn btn-warning"> Decline</a>&nbsp;&nbsp;
                                                <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                                          </div>
                                        </div><!-- /.modal-content -->
                                      </div><!-- /.modal-dialog -->
                                    </div>
                                    <a href="#" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-submit-{{$reimbursement->id}}"><i class="fa fa-save"></i> Approve</a>
                                    <div class="modal fade" id="modal-submit-{{$reimbursement->id}}" tabindex="-1" role="dialog">
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
            @endforeach
            
            
         
            
        </div>
    </section>

<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/vendor/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
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
$(":file").filestyle();
$("input[type='file']").attr('accept', '.pdf,.jpg,.jpeg,.tiff,.tif,.doc,.xls,.docx,.xlsx');
$(".bootstrap-filestyle input").attr('placeholder','No file chosen.');
$(".bootstrap-filestyle input").css('text-align','center');
</script>

@endsection
