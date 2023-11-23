@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
 
</style>
@endsection

@section('content')


    <section id="CreateAdjustment">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Create Adjustment - One-Time</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                {!! Form::open(['route' => 'account.adjustment.submit_onetime','autocomplete' => 'off']) !!}
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Client:</label>
                            {!! Form::select('clients_id',$clients_id,old("clients_id"), ['class' => 'form-control ']) !!}
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label>Worker:</label>
                            {!! Form::select('workers_id',$workers_id,old("workers_id"), ['class' => 'form-control ']) !!}
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Type:</label>
                            
                            {!! Form::select('type',$type,old("type"), ['class' => 'form-control changeTYPE','onchange'=>'changeTYPE()']) !!}
                        </div>
                    </div>
                    <div class="row TYPE1">     
                        <div class="col-md-3 form-group ">
                            <label>Date:</label>
                            <input type="date" class="form-control date" name="adjustment_date">
                        </div>
                        <div class="col-md-3 form-group ">
                            <label>Total Hours:</label>
                            <input type="number" min="0" step="0.01" class="form-control total_hours" name="adjustment_total_hours">
                        </div>
                        <div class="col-md-3 form-group ">
                            <label>Rate:</label>
                            {!! Form::select('rate',$rate,old("rate"), ['class' => 'form-control changeRate','onchange'=>'changeRate()']) !!}
                        </div>
                        <div class="col-md-3 form-group Rate" style="display: none;">
                            <label>Percent - Other:</label>
                            <input type="number" min="0" class="form-control rate" name="percent_other">
                        </div>
                    </div>
                    <div class="row">    
                        
                        <div class="col-md-4 form-group TYPE2" style="display:none">
                            <label>Description:</label>
                            <input type="text" class="form-control description" name="other_description">
                        </div>
                        <div class="col-md-4 form-group TYPE2" style="display:none">
                            <label>Amount:</label>
                            <input type="number" min="0" class="form-control amount" name="other_amount">
                        </div>
                        <div class="col-md-4 form-group TYPE2" style="display:none">
                            <label>Currency Type:</label>
                            {!! Form::select('other_currency',$other_currency,old("other_currency"), ['class' => 'form-control ']) !!}
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Pay to:</label>
                            {!!  Form::select('payto',$payto,old("payto"), ['class' => 'form-control changePayTO','onchange'=>'changePayTO()']) !!}
                             
                        </div>
                        
                        <div class="col-md-6 form-group PayTO3" style="display:none">
                            <label>Payment Method:</label>
                            {!! Form::select('payment_method',$payment_method,old("payment_method"), ['class' => 'form-control ']) !!}
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Bill to:</label>
                            {!! Form::select('billto',$billto,old("billto"), ['class' => 'form-control changeBillTO','onchange'=>'changeBillTO()']) !!}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Internal Notes:</label>
                            <input type="text" class="form-control internal_note" name="internal_notes">
                        </div>
                    </div>
                    
                    <div class="row"> 
                        <div class="col-xs-12">
                            <div class="col-md-4 col-md-offset-4 form-group">
                            
                                <a href="#" class="btn btn-success pull-right form-control" data-toggle="modal" data-target="#modal-submit-adjustment"><i class="fa fa-save"></i> Submit</a>
                                <div class="modal fade" id="modal-submit-adjustment" tabindex="-1" role="dialog">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Submit Create Adjustment - One time</h4>
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
                {!! Form::close() !!}

                <div class="col-xs-12">
                    <div class="panel panel-default open_cash_child">
                        <div class="panel-heading"><center>Adjustments - One-Time</center></div>
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
                                        <th>Time Adjustment - Rate</th>
                                        <th>Percent-Other</th>
                                        <th>Other - Description</th>
                                        <th>Other - Amount</th>
                                        <th>Other - Currency Type</th>
                                        <th>Internal Notes</th>
                                    </tr>
                                    </thead>
                                    <tbody class="add_Reference">
                                    @foreach ($adjustments as $adjustment)
                                    <tr>
                                        <td>{{$adjustment->id}}</td>
                                        <td>{{date('m/d/y',strtotime($adjustment->date_submitted))}}</td>
                                        <td>{{$adjustment->payto}}</td>
                                        <td>
                                            @if($adjustment->paytoclient)
                                            {{$clients_id[$adjustment->paytoclient]}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($adjustment->paytoworker)
                                            {{$workers_id[$adjustment->paytoworker]}}
                                            @endif
                                        </td>
                                        <td>{{$adjustment->billto}}</td>
                                        <td>
                                            @if($adjustment->billtoclient)
                                            {{$clients_id[$adjustment->billtoclient]}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($adjustment->billtoworker)
                                            {{$workers_id[$adjustment->billtoworker]}}
                                            @endif
                                        </td>
                                        <td>{{$adjustment->type}}</td>
                                        <td>{{date('m/d/y',strtotime($adjustment->adjustment_date))}}</td>
                                        <td>{{$adjustment->adjustment_total_hours}}</td>
                                        <td>{{$adjustment->rate}}</td>
                                        <td>{{$adjustment->percent_other}}</td>
                                        <td>{{$adjustment->other_description}}</td>
                                        <td>{{$adjustment->other_amount}}</td>
                                        <td>{{$adjustment->other_currency}}</td>
                                        <td>{{$adjustment->internal_notes}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    </table>
                               
                        </div>
                    </div>
                     
                </div>

                <div class="col-xs-8 submited_advance">

                </div>
                
            </div>
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    //$('#detail_table').DataTable({"order": [1, 'desc']});
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
});
function changePayTO(){
    
    $('.PayTO2').css('display','none');
    $('.PayTO3').css('display','none');
    if ($('.changePayTO').val()=='Client'){
        $('.PayTO2').css('display','block');
    }
    if ($('.changePayTO').val()=='Worker'){
        $('.PayTO3').css('display','block');
    }
}
function changeBillTO(){
    
    $('.BillTO2').css('display','none');
    $('.BillTO3').css('display','none');
    if ($('.changeBillTO').val()=='Client'){
        $('.BillTO2').css('display','block');
    }
    if ($('.changeBillTO').val()=='Worker'){
        $('.BillTO3').css('display','block');
    }
}
function changeTYPE(){
    
    $('.TYPE1').css('display','none');
    $('.TYPE2').css('display','none');
    if ($('.changeTYPE').val()=='Time Adjustment'){
        $('.TYPE1').css('display','block');
    }
    if ($('.changeTYPE').val()=='Other'){
        $('.TYPE2').css('display','block');
    }
}

function changeRate(){
    
    
    if ($('.changeRate').val()=='Percent-Other'){
        $('.Rate').css('display','block');
    }else{
        $('.Rate').css('display','none');
    }
     
}


</script>

@endsection
