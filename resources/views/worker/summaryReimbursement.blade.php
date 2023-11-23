@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<link href="/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css">
<link href="/vendor/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css">

<style>
.input-sm{width: 100px !important} 
</style>
@endsection

@section('content')


    <section id="Reimbursement">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Reimbursement Summary </h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
             
            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                         
                        <div class="panel-body">
                            <div class="row" style="overflow-x: scroll;">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr >
                                        <th width="5%">Status</th>
                                        <th width="10%">Date</th>
                                        <th width="15%">Client</th>
                                        <th width="15%">Reimbursement Type</th>
                                        <th width="10%">Amount</th>
                                        <th width="5%">Currency Type</th>

                                        <th width="15%">Internet Service Provider</th>
                                        <th width="10%">Date of Statement</th>
                                        <th width="15%">Additional Notes</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reimbursements as $reimbursement)
                                    <tr>
                                        <td>{{$reimbursement->status}}</td>
                                        <td>{{date('m/d/y',strtotime($reimbursement->date))}}</td>
                                        <td>{{$reimbursement->client()->client_name}}</td>
                                        <td>{{$reimbursement->type}}</td>
                                        <td>{{$reimbursement->amount}}</td>
                                        <td>{{strtoupper($reimbursement->currency_type)}}</td>

                                        <td>{{$reimbursement->internet_service_provider}}</td>
                                        <td>{{$reimbursement->statement_date ? date('m/d/y',strtotime($reimbursement->statement_date)):'' }}</td>
                                        <td>{{$reimbursement->additional_notes}}</td>

                                    </tr>
                                    @endforeach
                                    </tbody>
                                    </table>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
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
    
    
});
$(":file").filestyle();
$("input[type='file']").attr('accept', '.pdf,.jpg,.jpeg,.tiff,.tif,.doc,.xls,.docx,.xlsx');
$(".bootstrap-filestyle input").attr('placeholder','No file chosen.');
$(".bootstrap-filestyle input").css('text-align','center');

function changeType(){
    if ($('.changeType').val()=='Other'){
        $('.OtherReimbursement').css('display','block');
    }else{
        $('.OtherReimbursement').css('display','none');
    }
    if ($('.changeType').val()=='Internet - Backup' || $('.changeType').val()=='Internet - Primary'){
        $('.Internet_tpye').css('display','block');
    }else{
        $('.Internet_tpye').css('display','none');
    }
}
</script>

@endsection
