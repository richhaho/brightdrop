@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
 
</style>
@endsection

@section('content')


    <section id="CreateAdjustment">
        <div class="row">
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" >
                        <h3 class=" pull-right">Select Adjustment: </h3>
                         
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" >
                        <h3 class="bold"> 
                        {!!  Form::select('onetime_recurring',$onetime_recurring,$onetime_recurring_val, ['class' => 'form-control selectAdjustment','onchange'=>'selectAdjustment()']) !!}</h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 One-Time-Adjustment">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Create Adjustment - One-Time</h3>
                    </div>
                </div>
             
                {!! Form::open(['route' => 'payroll.adjustment.submit_onetime','autocomplete' => 'off']) !!}
                <div class="col-xs-12 onetime-create-group">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Client:</label>
                            {!! Form::select('clients_id',$clients_id,session("adjustment.filled_data")['clients_id'], ['class' => 'form-control onetime_clients_id','required'=>'true']) !!}
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label>Worker:</label>
                            {!! Form::select('workers_id',$onetime_workers[session("adjustment.filled_data")['clients_id']],session("adjustment.filled_data")['workers_id'], ['class' => 'form-control onetime_workers_id','required'=>'true','onchange'=>'changeWorker()']) !!}
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Type:</label>
                            
                            {!! Form::select('type',$type,session("adjustment.filled_data")['type'] , ['class' => 'form-control changeTYPE','onchange'=>'changeTYPE()','required'=>'true']) !!}
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                    </div>
                    <?php
                    $TYPE1='display:none';$TYPE2='display:none';
                    if (session("adjustment.filled_data")['type']=='Other'){$TYPE1='display:none';$TYPE2='display:block';}
                    if (session("adjustment.filled_data")['type']=='Time Adjustment'){ $TYPE1='display:block'; $TYPE2='display:none';}
                    ?>
                    <div class="row TYPE1" style="{{$TYPE1}}">     
                        <div class="col-md-3 form-group ">
                            <label>Date:</label>
                            <input type="date" class="form-control date" name="adjustment_date" value="{{session('adjustment.filled_data')['adjustment_date']}}">
                        </div>
                        <div class="col-md-3 form-group ">
                            <label>Total Hours:</label>
                            <input type="number" min="0" step="0.01" class="form-control total_hours" name="adjustment_total_hours" value="{{session('adjustment.filled_data')['adjustment_total_hours']}}">
                        </div>
                        <div class="col-md-3 form-group ">
                            <label>Rate:</label>
                            {!! Form::select('rate',$rate,session('adjustment.filled_data')['rate'], ['class' => 'form-control changeRate','onchange'=>'changeRate()']) !!}
                        </div>
                        <?php 
                        if (session("adjustment.filled_data")['rate']=='Percent-Other'){$styleRate='display:block';}else{ $styleRate='display:none';}
                        ?>
                        <div class="col-md-3 form-group Rate" style="{{$styleRate}}">
                            <label>Percent - Other:</label>
                            <input type="number" min="0" class="form-control rate" name="percent_other" value="{{session('adjustment.filled_data')['percent_other']}}">
                        </div>
                    </div>
                    <div class="row">    
                        
                        <div class="col-md-4 form-group TYPE2" style="{{$TYPE2}}">
                            <label>Description:</label>
                            <input type="text" class="form-control description" name="other_description" value="{{session('adjustment.filled_data')['other_description']}}">
                        </div>
                        <div class="col-md-4 form-group TYPE2" style="{{$TYPE2}}">
                            <label>Amount:</label>
                            <input type="number" min="0" step="0.01" class="form-control amount" name="other_amount" value="{{session('adjustment.filled_data')['other_amount']}}">
                        </div>
                        <div class="col-md-4 form-group TYPE2" style="{{$TYPE2}}">
                            <label>Currency Type:</label>
                            {!! Form::select('other_currency',$other_currency,session('adjustment.filled_data')['other_currency'] , ['class' => 'form-control other_currency', 'required' => 'true']) !!}
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Pay to:</label>
                            {!!  Form::select('payto',$payto,session('adjustment.filled_data')['payto'] , ['class' => 'form-control changePayTO','onchange'=>'changePayTO()','required'=>'true']) !!}
                            <p class="text-danger hidden">* This field is required.</p> 
                        </div>
                        <?php 
                        if (session("adjustment.filled_data")['payto']=='Worker'){$PayTO3='display:block';}else{ $PayTO3='display:none';}
                        ?>
                        <div class="col-md-6 form-group PayTO3" style="{{$PayTO3}}">
                            <label>Payment Method:</label>
                            {!! Form::select('payment_method',$payment_method,session('adjustment.filled_data')['payment_method'] , ['class' => 'form-control ']) !!}
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Bill to:</label>
                            {!! Form::select('billto',$billto,session('adjustment.filled_data')['billto'] , ['class' => 'form-control changeBillTO','onchange'=>'changeBillTO()','required'=>'true']) !!}
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Internal Notes:</label>
                            <input type="text" class="form-control internal_note" name="internal_notes" value="{{session('adjustment.filled_data')['internal_notes']}}">
                        </div>
                    </div>
                    
                    <div class="row"> 
                        <div class="col-xs-12">
                            <div class="col-md-4 col-md-offset-4 form-group">
                            
                                <a href="#" class="btn btn-success pull-right form-control" data-toggle="modal" onclick="showOneTimeModal(this)"><i class="fa fa-save"></i> Submit</a>
                                <div class="modal fade" id="modal-submit-adjustment-onetime" tabindex="-1" role="dialog">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Submit One time Adjustment</h4>
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
                
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 Recurring-Adjustment" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Create Adjustment - Recurring</h3>
                    </div>
                </div>
             
                {!! Form::open(['route' => 'payroll.adjustment.submit_recurring','autocomplete' => 'off']) !!}
                <div class="col-xs-12 recurring-create-group">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Client:</label>
                            {!! Form::select('clients_id',$clients_id, isset(session("adjustment_recurring.filled_data")['clients_id']) ? session("adjustment_recurring.filled_data")['clients_id'] : '', ['class' => 'form-control recurring_clients_id','required'=>'true']) !!}
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label>Worker:</label>
                            {!! Form::select('workers_id',isset(session("adjustment_recurring.filled_data")['clients_id']) ? $recurring_workers[session("adjustment_recurring.filled_data")['clients_id']]: [],isset(session("adjustment_recurring.filled_data")['workers_id']) ? session("adjustment_recurring.filled_data")['workers_id'] : '', ['class' => 'form-control recurring_workers_id','required'=>'true','onchange'=>'RchangeWorker()']) !!}
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Pay to:</label>
                            {!!  Form::select('payto',$payto,session('adjustment_recurring.filled_data')['payto'] , ['class' => 'form-control RchangePayTO','onchange'=>'RchangePayTO()','required'=>'true']) !!}
                            <p class="text-danger hidden">* This field is required.</p> 
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Bill to:</label>
                            {!! Form::select('billto',$billto,session('adjustment_recurring.filled_data')['billto'] , ['class' => 'form-control RchangeBillTO','onchange'=>'RchangeBillTO()','required'=>'true']) !!}
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"  >
                            <label>Description:</label>
                            <input type="text" class="form-control description" name="description" required  value="{{session('adjustment_recurring.filled_data')['description']}}">
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                        <div class="col-md-3 form-group"  >
                            <label>Amount:</label>
                            <input type="number" min="0" step="0.01" class="form-control amount" name="amount" required  value="{{session('adjustment_recurring.filled_data')['amount']}}">
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                        <div class="col-md-3 form-group"  >
                            <label>Currency Type:</label>
                            {!! Form::select('currency_type',$currency_type,session('adjustment_recurring.filled_data')['currency_type'] , ['class' => 'form-control currency_type', 'required'=>'true']) !!}
                            <p class="text-danger hidden">* This field is required.</p>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Internal Notes:</label>
                            <input type="text" class="form-control internal_note" name="internal_notes" value="{{session('adjustment_recurring.filled_data')['internal_notes']}}">
                        </div>
                    </div>
                    
                    <div class="row"> 
                        <div class="col-xs-12">
                            <div class="col-md-4 col-md-offset-4 form-group">
                            
                                <a href="#" class="btn btn-success pull-right form-control" onclick="showRecurringModal(this)"><i class="fa fa-save"></i> Submit</a>
                                <div class="modal fade" id="modal-submit-adjustment-recurring" tabindex="-1" role="dialog">
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
                 
            </div>

        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $('#recurring_table').DataTable({"order": [1, 'desc']});
    $('#onetime_table').DataTable({"order": [1, 'desc']});
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });

    $('.One-Time-Adjustment').css('display','none');
    $('.Recurring-Adjustment').css('display','none');
    if ($('.selectAdjustment').val()=='onetime'){
        $('.One-Time-Adjustment').css('display','block');
    }else{
        $('.Recurring-Adjustment').css('display','block');
    }
});
function selectAdjustment(){
    $('.One-Time-Adjustment').css('display','none');
    $('.Recurring-Adjustment').css('display','none');
    if ($('.selectAdjustment').val()=='onetime'){
        $('.One-Time-Adjustment').css('display','block');
    }else{
        $('.Recurring-Adjustment').css('display','block');
    }
    
}

function changeWorker(){
    var workers_id=$('.workers_id').val();
    // $('.other_currency').val('');
    if ($('.other_currency').val()) {return;}
    if ($('.changePayTO').val()=='Worker' || $('.changeBillTO').val()=='Worker'){
        $.get("{{route('payroll.worker.special_worker')}}",{"id":workers_id}).done(function( data ) {
            if(data.currency_type) $('.other_currency').val(data.currency_type.toLowerCase());
        });
    }else{
        $('.other_currency').val('');
    }
}

function changePayTO(){
    
    $('.PayTO2').css('display','none');
    $('.PayTO3').css('display','none');
    if ($('.changePayTO').val()=='Client'){
        $('.PayTO2').css('display','block');
    }
    if ($('.changePayTO').val()=='Worker'){
        $('.PayTO3').css('display','block');
    }
    changeWorker();
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
    changeWorker();
}

if ($('.changeTYPE').val()=='Time Adjustment'){
    $('.TYPE1').css('display','block');
    $('.other_currency').removeAttr('required');
}
if ($('.changeTYPE').val()=='Other'){
    $('.TYPE2').css('display','block');
    $('.other_currency').attr('required', true);
}
function changeTYPE(){
    
    $('.TYPE1').css('display','none');
    $('.TYPE2').css('display','none');
    if ($('.changeTYPE').val()=='Time Adjustment'){
        $('.TYPE1').css('display','block');
        $('.other_currency').removeAttr('required');
    }
    if ($('.changeTYPE').val()=='Other'){
        $('.TYPE2').css('display','block');
        $('.other_currency').attr('required', true);
    }
}

function changeRate(){
    
    
    if ($('.changeRate').val()=='Percent-Other'){
        $('.Rate').css('display','block');
    }else{
        $('.Rate').css('display','none');
    }
     
}

function RchangeWorker(){
    if ($('.currency_type').val()) {return;}
    if ($('.RchangePayTO').val()=='Worker' || $('.RchangeBillTO').val()=='Worker'){
        let workers_id=$('.recurring_workers_id').val();
        $.get("{{route('payroll.worker.special_worker')}}",{"id":workers_id}).done(function( data ) {
            if(data.currency_type) $('.currency_type').val(data.currency_type.toLowerCase());
        });
    }else{
        $('.currency_type').val('');
    }
}

function RchangePayTO(){
    RchangeWorker();
}
function RchangeBillTO(){
    RchangeWorker();
}

$('.btn-column-view-onetime').click(function(){
    for (i=1;i<18;i++){
        if ($('.check_column_onetime'+i).is(':checked')){
            $('.column_onetime'+i).show();
        }else{
            $('.column_onetime'+i).hide();
        }
    }
});
$('.btn-column-view-recurring').click(function(){
    for (i=1;i<18;i++){
        if ($('.check_column_recurring'+i).is(':checked')){
            $('.column_recurring'+i).show();
        }else{
            $('.column_recurring'+i).hide();
        }
    }
});
</script>

<input type="hidden" class="onetime_workers" value="{{json_encode($onetime_workers)}}" />
<script type="text/javascript">
var onetime_workers=JSON.parse($('.onetime_workers').val());
   
$('.onetime_clients_id').change(function(){
    var clients_id=$('.onetime_clients_id').val();
    $('.onetime_workers_id').html('<option value=""></option');
    $.each(onetime_workers[clients_id],function(key,value){
        $('.onetime_workers_id').append('<option value="'+key+'">'+value+'</option');
    });
    changeWorker();
});

$('.recurring_clients_id').change(function(){
    var clients_id=$('.recurring_clients_id').val();
    $('.recurring_workers_id').html('<option value=""></option');
    $.each(onetime_workers[clients_id],function(key,value){
        $('.recurring_workers_id').append('<option value="'+key+'">'+value+'</option');
    });
    RchangeWorker();
});
$('input[type="number"]').change(function(){
   if($(this).attr('step')=='0.01'){
        let round_val=Math.round((parseFloat($(this).val()) || 0)*100)/100;
        $(this).val(round_val);
    } 
});

// check onetime from required fields
$('.onetime-create-group select').change(function(){
    hasValues('.onetime-create-group')
});
function showOneTimeModal(e) {
    if (hasValues('.onetime-create-group')) $('#modal-submit-adjustment-onetime').modal('show');
}
function hasValues(type) {
    let hasValues = true;
    $(type).find('select[required]').each(function(index){
        if (!$(this).val()) {
            hasValues = false;
            $(this).parent().find('p').removeClass('hidden');
        } else {
            $(this).parent().find('p').addClass('hidden');
        }
    });
    $(type).find('input[required]').each(function(index){
        if (!$(this).val()) {
            hasValues = false;
            $(this).parent().find('p').removeClass('hidden');
        } else {
            $(this).parent().find('p').addClass('hidden');
        }
    });
    return hasValues;
}

// check reccuring from required fields
$('.recurring-create-group select').change(function(){
    hasValues('.recurring-create-group')
});
$('.recurring-create-group input').on('input', function(){
    hasValues('.recurring-create-group')
});
function showRecurringModal(e) {
    if (hasValues('.recurring-create-group')) $('#modal-submit-adjustment-recurring').modal('show');
}
</script>
@endsection
