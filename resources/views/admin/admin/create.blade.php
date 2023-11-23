@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.panel-body{padding: 0px 15px 0px 15px !important;}  
</style>
@endsection

@section('content')

{!! Form::open(['route' => 'admin.admin.store','autocomplete' => 'off']) !!}
    <section id="AccoutManager">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12" >
                        <h3 class="bold">Create New Administrator</h3>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
                        <button type="submit" class="btn btn-success pull right"><i class="fa fa-save"></i> Save</button>
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
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1"><i class="fa fa-minus-square"></i> Administrator Status</a></div>
                        <div class="panel-body panel-collapse collapse in" id="collapse1"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Administrator Status:</label>
                                    {!!  Form::select('status',$status,old("status"), ['class' => 'form-control' ]) !!} 
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2"><i class="fa fa-plus-square"></i> Contact Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse2"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>First Name:</label>
                                    <input name="first_name" class="form-control " type="text" value="" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Last Name:</label>
                                    <input name="last_name" class="form-control " type="text" value="" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Email:</label>
                                    <input name="email" class="form-control email" type="email" value="" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Phone Number:</label>
                                    <input name="phone" class="form-control " type="text" value="" required>
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="true" aria-controls="collapse3"><i class="fa fa-plus-square"></i> Mailing Address</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse3"><br>
                            
                            <div class="row changeCountryUS">
                                <div class="col-md-6 form-group">
                                    <label>Address Line 1:</label>
                                    <input name="address1" class="form-control " type="text" value="" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Address Line 2:</label>
                                    <input name="address2" class="form-control " type="text" value="">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>City:</label>
                                    <input name="city" class="form-control " type="text" value="" required>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>State:</label>
                                    <input name="state" class="form-control " type="text" value="" required>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Zip Code:</label>
                                    <input name="zip" class="form-control " type="text" value="" required>
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="true" aria-controls="collapse4"><i class="fa fa-plus-square"></i> Login Information</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse4"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>UserName: (Login User Email)</label>
                                    <input name="user_email" class="form-control user_email" type="email" value="" required>
                                </div>
                                <div class="col-md-6 form-group" >
                                    <label>Password:</label>
                                    <input name="password" class="form-control " type="password" value="" required>
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>
                 
                
            </div>
        </div>
    </section>
{!! Form::close() !!}
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    //$('#detail_table').DataTable({"order": [1, 'desc']});
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
    var anchor = window.location.hash;
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
$('.email').change(function(){
    $('.user_email').val($('.email').val());
});



</script>
<script type="text/javascript">
    $('button[type="submit"]').click(function(){
        if (!$('input[name="first_name"]').val() || !$('input[name="last_name"]').val() || !$('input[name="email"]').val() || !$('input[name="phone"]').val()){
            $("#collapse2").collapse('show');
        }
        if (!$('input[name="address1"]').val() || !$('input[name="city"]').val() || !$('input[name="state"]').val() || !$('input[name="zip"]').val()){
            $("#collapse3").collapse('show');
        }
        if (!$('input[name="user_email"]').val() || !$('input[name="password"]').val()){
            $("#collapse4").collapse('show');
        }
    });
</script>
@endsection
