@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
 
</style>
@endsection

@section('content')


    <section id="PTO">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Create Request (PTO) </h3>
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
                    {!! Form::open(['route' => 'worker.submitPTO','autocomplete' => 'off']) !!}
                    <div class="panel panel-default">
                        <div class="panel-heading">Request PTO</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Client:</label>
                                    {!!  Form::select('clients_list',$clients_list,old("clients_list"), ['class' => 'form-control clients_list' ]) !!}
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Date Of PTO:</label>
                                    <input name="date_pto" type="date" class="form-control date_pto"  data-date-autoclose="true" class="form-control date-picker"  data-date-format ="mm/dd/yy" data-toggle="tooltip" data-placement="top" title="" required>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Total Hours:</label>
                                    {!!  Form::select('total_hours',$total_hours,old("total_hours"), ['class' => 'form-control' ]) !!}
                               </div>
                               <div class="col-md-12 form-group">
                                    <label>Reason:</label>
                                    <input name="reason" type="text" value="" class="form-control reason" required>
                                </div>
                                <div class="col-md-12 form-group">
                                    <a href="#" class="btn btn-success btn_first_submit pull-right" data-toggle="modal" data-target="#modal-submit"><i class="fa fa-check"></i> Submit PTO</a>

                                    <div class="modal fade" id="modal-submit" tabindex="-1" role="dialog">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Submit PTO</h4>
                                          </div>
                                          <div class="modal-body">
                                              <p class="confirm_text">Are you sure you want to submit PTO?</p>
                                          </div>
                                          <div class="modal-footer">
                                                
                                                <button type="submit" class="btn btn-success btn-submit"> Submit</button>&nbsp;&nbsp;
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
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
    //$('#detail_table').DataTable({"order": [1, 'desc']});
});
$('.btn_first_submit').click(function(){
    var client_id=$('.clients_list').val();
    var date=$('.date_pto').val();
    var reason=$('.reason').val();
    if (!date || !reason || !client_id ){
        $('.confirm_text').text('Please fill out all fields.');
        $('.btn-submit').attr('disabled',true);return;
    }
    $.get("{{route('worker.exist_pto')}}",{"client_id":client_id,"date":date}).done(function( data ) {
        if(data.id>0){
            if (data.status=='Approved' || data.status=='Declined'){
                $('.confirm_text').text('Your previous PTO request for this date was '+data.status.toLowerCase()+'.  If you need to make changes, please contact your manager.');    
                $('.btn-submit').attr('disabled',true);return;
            }else{
                $('.confirm_text').text('You already have a PTO request for this client for this day. Do you want to edit it?');
                $('.btn-submit').attr('disabled',false);
            }
            
            
        }else{
            $('.confirm_text').text('Are you sure you want to submit PTO?');
            $('.btn-submit').attr('disabled',false);
        }

    });
    
    
});

</script>

@endsection
