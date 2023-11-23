@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<link href="/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="/vendor/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css">

<style>
 
</style>
@endsection

@section('content')


    <section id="Reimbursement">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Create Reimbursement Request </h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            {!! Form::open(['route' => 'worker.submit_reimbursementRequest','autocomplete' => 'off','class'=>'submitform','files'=>true]) !!}
            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Request</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Client:</label>
                                    {!!  Form::select('clients_list',$clients_list,old("clients_list"), ['class' => 'form-control clients_list','required'=>true ]) !!}
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Date:</label>
                                    <input name="date" class="form-control" type="date" value="{{$date}}" required style="line-height: 0px;">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Amount:</label>
                                    <input name="amount" class="form-control amount" type="number" min="0" step="0.01" value="">
                                    <p class="amount_error" style="color: red;display: none;">Amount must be set more than 0.</p>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Currency Type:</label>
                                    <input name="currency_type" class="form-control currency_type" value="{{strtoupper(Auth::user()->worker()->currency_type)}}" readonly>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Reimbursement Type:</label>
                                    {!!  Form::select('type',$type,old("type"), ['class' => 'form-control changeType','onchange'=>'changeType()' ]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group OtherReimbursement" style="display: none">
                                    <label>Other - Reimbursement Type:</label>
                                    <input name="other_type" class="form-control other_type" type="text" value="" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group Internet_tpye"  style="display: none">
                                    <label>Internet Service Provider:</label>
                                    <input name="internet_service_provider" class="form-control" type="text" value="" >
                                </div>
                            </div>
                            <div class="row">    
                                <div class="col-md-6 form-group Internet_tpye" style="display: none">
                                    <label>Date of Statement:</label>
                                    <input type="date" name="statement_date" class="form-control" value="">
                                </div>
                            </div>
                            <div class="row">    
                                <div class="col-md-6 form-group Internet_tpye"  style="display: none">
                                    <label>Reimbursement for this statement is already included with my bi-weekly payment:</label>
                                    {!!  Form::select('statement_included',$statement_included,old("statement_included"), ['class' => 'form-control statement_included','onchange'=>'alreadyIncluded()' ]) !!}
                               </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group filegroup">
                                    <label>Upload Copy of Statement or Receipt:</label>
                                    <input name="copy_statement_file" type="file" id="files" value="" class="form-control file" placeholder="no">
                                    
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Additional Notes (Employee):</label>
                                    <input name="additional_notes" type="text" value="" class="form-control">
                                </div>

                                <div class="col-md-12 form-group">
                                    <button type="submit" class="btn btn-success pull-right">Submit</button>
                                </div>
                            </div>
                                                            
                            
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/vendor/select2/js/select2.min.js" type="text/javascript"></script>
<script src="/vendor/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
<?php
$max_uploadfileSize= min(ini_get('post_max_size'), ini_get('upload_max_filesize'));
$max_uploadfileSize= substr($max_uploadfileSize, 0, -1)*1024*1024;
?>
<script>

$(function () {
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
    $('#detail_table').DataTable({"order": [1, 'desc']});

    $( '.submitform').submit( function(event){
        // if(parseInt($('.amount').val())==0){
        //     $('.amount_error').css('display','block');
        //     event.preventDefault();
        // }
        $(".filegroup p").remove();        
        var fe=$('input:file')[0].files[0].size;
        var max_uploadfileSize={{$max_uploadfileSize}};
        var file_name=$('input:file')[0].files[0].name;
        var ext=file_name.split('.').pop().toLowerCase();
        var ext_area=['pdf','jpeg','jpg','tiff','tif','doc','xls','docx','xlsx'];
        if (ext_area.indexOf(ext)==-1){
            $(".filegroup").append('<p>This file type is not permitted for upload.</p>');
            event.preventDefault();
        }
        
        if (fe>max_uploadfileSize){
            $(".filegroup").append('<p>This file is too large to upload.</p>');
            event.preventDefault();
        }

        
    });
    $('input:file').click( function(){
      $(".filegroup p").remove(); 
      $('.btn-success').removeClass("disabled");
      $('.btn-success').css('pointer-events','auto'); 
    });
    $('.amount').click( function(){
        $('.amount_error').css('display','none');
    });

    
    
});
$(":file").filestyle();
$("input[type='file']").attr('accept', '.pdf,.jpg,.jpeg,.tiff,.tif,.doc,.xls,.docx,.xlsx');
$(".bootstrap-filestyle input").attr('placeholder','No file chosen.');
$(".bootstrap-filestyle input").css('text-align','center');

function changeType(){
    $('.amount').removeAttr('readonly');
    if ($('.changeType').val()=='Other'){
        $('.OtherReimbursement').css('display','block');
        $('.other_type').attr('required',true);
    }else{
        $('.OtherReimbursement').css('display','none');
        $('.other_type').attr('required',false);
    }
    if ($('.changeType').val()=='Internet - Backup' || $('.changeType').val()=='Internet - Primary'){
        $('.Internet_tpye').css('display','block');

        if($('.statement_included').val()=='yes') {
            $('.amount').val('0');
            $('.amount').attr('readonly',true);
        }

    }else{
        $('.Internet_tpye').css('display','none');
    }
}
function alreadyIncluded(){
    if($('.statement_included').val()=='yes') {
        $('.amount').val('0');
        $('.amount').attr('readonly',true);
    }else{
        $('.amount').removeAttr('readonly');
    }
}
// $('.clients_list').change(function(){
//     var client_id=$('.clients_list').val();
//     $.get("{{route('worker.assignedWorkerCurrency')}}",{"client_id":client_id}).done(function( data ) {
//         $('.currency_type').val(data);
//     });
    
// });

$('input[type="number"]').change(function(){
   if($(this).attr('step')=='0.01'){
        let round_val=Math.round((parseFloat($(this).val()) || 0)*100)/100;
        $(this).val(round_val);
    } 
});
</script>

@endsection
