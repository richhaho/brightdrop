@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
 
</style>
@endsection

@section('content')


    <section id="contactSearch">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12" >
                        <h3 class="bold">Search Contact</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif

            
            <div class="col-md-12 col-lg-12 col-xs-12">
                {!! Form::open(['route' => 'admin.contact.setfilter','autocomplete' => 'off']) !!}
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Search <a class="btn btn-default pull-right btn-xs" data-toggle="modal" data-target="#modal-advanced-search"><i class="fa fa-plus"></i> Advanced Search Fields</a></div>
                        <div class="modal fade" id="modal-advanced-search" tabindex="-1" role="dialog">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Add Advanced Fields to Search.</h4>
                                      </div>
                                      <div class="modal-body row">
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    @if(session('contact_search.city'))
                                                    <input type="checkbox" name="" class="check_advanced_search1" checked><span> City</span>
                                                    @else
                                                    <input type="checkbox" name="" class="check_advanced_search1" ><span> City</span>
                                                    @endif
                                                </div>
                                            </div>

                                      </div>
                                      <div class="modal-footer">
                                            <button class="btn btn-success btn-advanced-search" type="button"  data-dismiss="modal"> Apply</button>
                                            <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>Status:</label>
                                    {!!  Form::select('status',$status,session('contact_search.status'), ['class' => 'form-control' ]) !!} 
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Client Name:</label>
                                    {!!  Form::select('clients_list',$clients_list,session('contact_search.clients_id'), ['class' => 'form-control' ]) !!} 
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>First Name:</label>
                                    <input name="first_name" class="form-control " type="text" value="{{session('contact_search.first_name')}}"  >
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Last Name:</label>
                                    <input name="last_name" class="form-control " type="text" value="{{session('contact_search.last_name')}}"  >
                                </div>
                                
                                <!-- <div class="col-md-3 form-group">
                                    <label>Email Address:</label>
                                    <input name="email" class="form-control " type="email" value="{{session('contact_search.email')}}"  >
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Phone Number:</label>
                                    <input name="phone" class="form-control " type="text" value="{{session('contact_search.phone')}}">
                                </div> -->
                                <div class="col-md-3 form-group">
                                    <label>Country:</label>
                                    <input name="country" class="form-control " type="text" value="{{session('contact_search.country')}}">
                                </div>
                                <!-- <div class="col-md-3 form-group">
                                    <label>Address:</label>
                                    <input name="address" class="form-control " type="text" value="{{session('contact_search.address')}}">
                                </div> -->
                                
                                <div class="col-md-3 form-group">
                                    <label>State:</label>
                                    <input name="state" class="form-control " type="text" value="{{session('contact_search.state')}}">
                                </div>
                                <div class="col-md-3 form-group advanced_field1" style="{{session('contact_search.city') ? '':'display:none'}}">
                                    <label>City:</label>
                                    @if (session('contact_search.city'))
                                    <input name="city" class="form-control advanced_field1" type="text" value="{{session('contact_search.city')}}" >
                                    @else
                                    <input name="city" class="form-control advanced_field1" type="text" value="{{session('contact_search.city')}}" disabled>
                                    @endif
                                </div>
                                <!-- <div class="col-md-3 form-group">
                                    <label>Zip Code:</label>
                                    <input name="zip" class="form-control " type="text" value="{{session('contact_search.zip')}}">
                                </div> -->
                                <div class="col-md-12 form-group"  >
                                    <button type="submit" class="btn btn-success pull-right" style="margin-left: 10px"><i class="fa fa-search"></i> Search</button> 
                                    <a href="{{route('admin.contact.resetfilter')}}" class="btn btn-warning pull-right"><i class="fa fa-remove"></i> Clear</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Search Result</div>
                        <div class="panel-body">
                            <div class="row" style="overflow-x: scroll;">
                                <div class="box-body table_group">
                                    <table id="list_table" class="table table-hover text-center table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Client Name</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Email</th>
                                            <th>State</th>
                                            <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($contacts as $contact)
                                        <tr>
                                            <td>{{ucfirst($contact->status)}}</td>
                                            <td>@if(isset($clients_list[$contact->clients_id])) {{$clients_list[$contact->clients_id]}} @endif</td>
                                            <td>{{$contact->last_name}}</td>
                                            <td>{{$contact->first_name}}</td>
                                            <td>{{$contact->email}}</td>
                                            <td>{{$contact->state}} </td>
                                             
                                            <td>
                                                <a href="/admin/profileContact/{{$contact->id}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-close"  data-toggle="modal" data-target="#modal-delete-{{$contact->id}}"></i></a>
                                                @component('admin.contact.components.deletemodal')
                                                @slot('id') 
                                                    {{ $contact->id }}
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
                </div>
      
            </div>
            
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $('#list_table').DataTable({searching: false, "pageLength": 20, "lengthMenu": [ 10, 20, 50 ]});
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
});
function changeIndustry(){
    if ($('.changeIndustry').val()=='24'){
        $('.Industry').css('display','block');
    }else{
        $('.Industry').css('display','none');
    }
}
function changeWorkerJobFunctions(){
    if ($('.changeWorkerJobFunctions').val()=='17'){
        $('.WorkerJobFunctions').css('display','block');
    }else{
        $('.WorkerJobFunctions').css('display','none');
    }
}

function changeCountry(){
    if ($('.changeCountry').val()=='1'){
        $('.changeCountryUS').css('display','block');
        $('.changeCountryOther').css('display','none');
    }else{
        $('.changeCountryUS').css('display','none');
        $('.changeCountryOther').css('display','block');
    }
}
function changeLeadGenerated(){
    $('.LeadGenerated1').css('display','none');
    $('.LeadGenerated2').css('display','none');
    $('.LeadGenerated5').css('display','none');
    $('.LeadGenerated7').css('display','none');
    $('.LeadGenerated8').css('display','none');

    if ($('.changeLeadGenerated').val()=='1'){
        $('.LeadGenerated1').css('display','block');
    }
    if ($('.changeLeadGenerated').val()=='2'){
        $('.LeadGenerated2').css('display','block');
    }
    if ($('.changeLeadGenerated').val()=='5'){
        $('.LeadGenerated5').css('display','block');
    }
    if ($('.changeLeadGenerated').val()=='7'){
        $('.LeadGenerated7').css('display','block');
    }
    if ($('.changeLeadGenerated').val()=='8'){
        $('.LeadGenerated8').css('display','block');
    }
}

function changeOvertimePay(){
    if ($('.changeOvertimePay').val()=='1'){
        $('.OvertimePay').css('display','block');
    }else{
        $('.OvertimePay').css('display','none');
    }
}

function changePaymentMethod(){
    if ($('.changePaymentMethod').val()=='1'){
        $('.PaymentMethod').css('display','none');
    }else{
        $('.PaymentMethod').css('display','block');
    }
}

function changeClientPTO(){
    if ($('.changeClientPTO').val()=='1'){
        $('.ClientPTO').css('display','block');
    }else{
        $('.ClientPTO').css('display','none');
    }
}
function changeHolidaySchedule(){
    if ($('.changeHolidaySchedule').val()=='1'){
        $('.HolidaySchedule').css('display','block');
    }else{
        $('.HolidaySchedule').css('display','none');
    }
}

$('.btn-advanced-search').click(function(){
    for (i=1;i<2;i++){
        if ($('.check_advanced_search'+i).is(':checked')){
            $('.advanced_field'+i).css('display','block');
            $('.advanced_field'+i).attr('disabled',false);
        }else{
            $('.advanced_field'+i).css('display','none');
            $('.advanced_field'+i).attr('disabled',true);
        }
    }
});




</script>

@endsection
