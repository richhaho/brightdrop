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
                        <h3 class="bold">Create Adjustment - Recurring</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                {!! Form::open(['route' => 'account.adjustment.submit_recurring','autocomplete' => 'off']) !!}
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Pay to:</label>
                            {!!  Form::select('payto',$payto,old("payto"), ['class' => 'form-control changePayTO','onchange'=>'changePayTO()']) !!}
                             
                        </div>
                        <div class="col-md-4 form-group PayTO2" style="display:none">
                            <label>Client:</label>
                            {!! Form::select('paytoclient',$paytoclient,old("paytoclient"), ['class' => 'form-control ']) !!}
                        </div>
                        
                        <div class="col-md-4 form-group PayTO3" style="display:none">
                            <label>Worker:</label>
                            {!! Form::select('paytoworker',$paytoworker,old("paytoworker"), ['class' => 'form-control ']) !!}
                        </div>
                        <div class="col-md-4 form-group PayTO3" style="display:none">
                            <label>Payment Method:</label>
                            {!! Form::select('payment_method',$payment_method,old("payment_method"), ['class' => 'form-control ']) !!}
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Bill to:</label>
                            {!! Form::select('billto',$billto,old("billto"), ['class' => 'form-control changeBillTO','onchange'=>'changeBillTO()']) !!}
                        </div>
                        <div class="col-md-4 form-group BillTO2" style="display:none">
                            <label>Client:</label>
                            {!! Form::select('billtoclient',$billtoclient,old("billtoclient"), ['class' => 'form-control ']) !!}
                        </div>
                        
                        <div class="col-md-4 form-group BillTO3" style="display:none">
                            <label>Worker:</label>
                            {!! Form::select('billtoworker',$billtoworker,old("billtoworker"), ['class' => 'form-control ']) !!}
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"  >
                            <label>Description:</label>
                            <input type="text" class="form-control description" name="description" required >
                        </div>
                        <div class="col-md-3 form-group"  >
                            <label>Amount:</label>
                            <input type="number" min="0" class="form-control amount" name="amount" required>
                        </div>
                        <div class="col-md-3 form-group"  >
                            <label>Currency Type:</label>
                            {!! Form::select('currency_type',$currency_type,old("currency_type"), ['class' => 'form-control ']) !!}
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
                                        <h4 class="modal-title">Submit Adjustments - Recurring</h4>
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
                        <div class="panel-heading"><center>Adjustments - Recurring</center></div>
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
                                <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
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
                                    {{$paytoclient[$adjustment->paytoclient]}}
                                    @endif
                                </td>
                                <td>
                                    @if($adjustment->paytoworker)
                                    {{$paytoworker[$adjustment->paytoworker]}}
                                    @endif
                                </td>
                                <td>{{$adjustment->billto}}</td>
                                <td>
                                    @if($adjustment->billtoclient)
                                    {{$billtoclient[$adjustment->billtoclient]}}
                                    @endif
                                </td>
                                <td>
                                    @if($adjustment->billtoworker)
                                    {{$billtoworker[$adjustment->billtoworker]}}
                                    @endif
                                </td>
                                <td>{{$adjustment->description}}</td>
                                <td>{{$adjustment->amount}}</td>
                                <td>{{$adjustment->currency_type}}</td>
                                <td>{{$adjustment->internal_notes}}</td>
                                <td>
                                    <a href="#" class="btn btn-success btn-xs"><i class="fa fa-edit" data-toggle="modal" data-target="#modal-edit-{{$adjustment->id}}"></i></a>
                                    @component('account.adjustment.components.editmodal')
                                    @slot('id') 
                                        {{ $adjustment->id }}
                                    @endslot
                                    @slot('amount') 
                                        {{ $adjustment->amount }}
                                    @endslot
                                    @slot('description') 
                                        {{ $adjustment->description }}
                                    @endslot
                                    @slot('currency_type') 
                                        {{ $adjustment->currency_type }}
                                    @endslot
                                    @slot('internal_notes') 
                                        {{ $adjustment->internal_notes }}
                                    @endslot
                                     
                                    @endcomponent
                                             
                                    <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-close"  data-toggle="modal" data-target="#modal-delete-{{$adjustment->id}}"></i></a>
                                     
                                    @component('account.adjustment.components.deletemodal')
                                    @slot('id') 
                                        {{ $adjustment->id }}
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


</script>

@endsection
