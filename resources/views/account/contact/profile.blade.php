@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.panel-body{padding: 0px 15px 0px 15px !important;}  
</style>
@endsection

@section('content')

{!! Form::open(['route' => 'account.contact.update','autocomplete' => 'off']) !!}
    <input type="hidden" name="contact_id" value="{{$contact->id}}">
    <section id="Profile">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                        <h3 class="bold">Contact: {{$contact->fullname()}}'s Profile</h3>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                        <button type="submit" class="btn btn-success pull-right btn-save" disabled><i class="fa fa-save"></i> Save Profile</button>
                        <button type="button" class="btn btn-warning pull-right btn-edit"><i class="fa fa-edit"></i> Edit Profile</button>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12 main_content">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1"><i class="fa fa-minus-square"></i> Client Status</a></div>
                        <div class="panel-body panel-collapse collapse in" id="collapse1"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Contact Status:</label>
                                    {!!  Form::select('status',$status,$contact->status, ['class' => 'form-control' ]) !!} 
                                </div>
                            </div>
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
                                    <input name="first_name" class="form-control " type="text" value="{{$contact->first_name}}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Last Name:</label>
                                    <input name="last_name" class="form-control " type="text" value="{{$contact->last_name}}" required>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Client Name:&nbsp;&nbsp;&nbsp;</label>
                                    @if($client)
                                    {{$client->client_name}}
                                    @else
                                    No assigned client.
                                    @endif
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Email Address:</label>
                                    <input name="email" class="form-control email" type="email" value="{{$contact->email}}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Phone Number:</label>
                                    <input name="phone" class="form-control " type="text" value="{{$contact->phone}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="true" aria-controls="collapse3"><i class="fa fa-plus-square"></i> Mailing Address</a></div>
                        <div class="panel-body panel-collapse collapse out" id="collapse3"><br>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Same as Client:</label>
                                    {!!  Form::select('same_as_client',$same_as_client,$contact->same_as_client, ['class' => 'form-control same_as_client' ]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Country:</label>
                                    {!!  Form::select('country',$country,$contact->same_as_client=='yes' && $client ? $client->country:$contact->country, ['class' => 'form-control changeCountry mailing_address','onchange'=>'changeCountry()' ]) !!}
                                </div>
                            </div>
                            <?php 
                                $style='';$style_other='';
                                $country=$contact->same_as_client=='yes' && $client ? $client->country:$contact->country;
                                if ($country=='Other') {
                                    $style='display: none'; 
                                }else{
                                    $style_other='display: none';
                                }
                            ?>
                            <div class="row changeCountryOther" style="{{$style_other}}">
                                <div class="col-md-6 form-group">
                                    <label>Country - Other:</label>
                                    <input name="country_other" class="form-control  mailing_address" type="text" value="{{$contact->same_as_client=='yes' && $client ? $client->country_other:$contact->country_other}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label> Full Address - Foreign:</label>
                                    <input name="full_address_other" class="form-control  mailing_address" type="text" value="{{$contact->same_as_client=='yes' && $client ? $client->address_foreign:$contact->full_address_other}}">
                                </div>
                            </div>
                            <div class="row changeCountryUS" style="{{$style}}">
                                <div class="col-md-6 form-group">
                                    <label>Address Line 1:</label>
                                    <input name="address1" class="form-control  mailing_address" type="text" value="{{$contact->same_as_client=='yes' && $client ? $client->address1:$contact->address1}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Address Line 2:</label>
                                    <input name="address2" class="form-control  mailing_address" type="text" value="{{$contact->same_as_client=='yes' && $client ? $client->address2:$contact->address2}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>City:</label>
                                    <input name="city" class="form-control  mailing_address" type="text" value="{{$contact->same_as_client=='yes' && $client ? $client->city:$contact->city}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>State:</label>
                                    <input name="state" class="form-control mailing_address" type="text" value="{{$contact->same_as_client=='yes' && $client ? $client->state:$contact->state}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Zip Code:</label>
                                    <input name="zip" class="form-control mailing_address" type="text" value="{{$contact->same_as_client=='yes' && $client ? $client->zip:$contact->zip}}">
                                </div>
                            </div>
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
                                    <input name="user_email" class="form-control user_email" type="text" value="{{$contact->user()->email}}" required>
                                </div>
                                <div class="col-md-6 form-group" style="pointer-events:initial;opacity: 1;">
                                    <label>Password:</label><br>
                                    <a href="{{route('account.contact.resetpassword')}}?id={{$contact->id}}" class="btn btn-default"><i class="fa fa-lock"></i> Send Password Reset Link</a>
                                </div>
                            </div>
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

function changeCountry(){
    if ($('.changeCountry').val()=='US'){
        $('.changeCountryUS').css('display','block');
        $('.changeCountryOther').css('display','none');
    }else{
        $('.changeCountryUS').css('display','none');
        $('.changeCountryOther').css('display','block');
    }
}
$('.email').change(function(){
    $('.user_email').val($('.email').val());
});
 

$('.panel-body').css('pointer-events','none');
$('.panel-body').css('opacity','0.8');
$('.btn-edit').click(function(){
    $('.panel-body').css('pointer-events','auto');
    $('.panel-body').css('opacity','1');
    $('.btn-save').removeAttr('disabled');
});
$('.btn-save').click(function(){
    //$('.btn-save').attr('disabled',true);
});

if($('.same_as_client').val()=="no") {
        $('.mailing_address').removeAttr('readonly');
    }else{
       $('.mailing_address').attr('readonly','true'); 
}
$('.same_as_client').change(function(){
    if($('.same_as_client').val()=="no") {
        $('.mailing_address').removeAttr('readonly');
    }else{
       $('.mailing_address').attr('readonly','true'); 
    }
});

</script>
<script type="text/javascript">
    $('button[type="submit"]').click(function(){
        if (!$('input[name="first_name"]').val() || !$('input[name="last_name"]').val() || !$('input[name="email"]').val()){
            $("#collapse2").collapse('show');
        }
        if (!$('input[name="user_email"]').val()){
            $("#collapse4").collapse('show');
        }
    });
</script>
@endsection
