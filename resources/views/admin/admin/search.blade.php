@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
 
</style>
@endsection

@section('content')


    <section id="Administrator">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Search Administrator</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                {!! Form::open(['route' => 'admin.admin.setfilter','autocomplete' => 'off']) !!}
                <!-- <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Search Field</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>Administrator Status:</label>
                                     {!!  Form::select('status',$status,session('admin_search.status'), ['class' => 'form-control' ]) !!}
                                </div>
                            
                                <div class="col-md-3 form-group">
                                    <label>First Name:</label>
                                    <input name="first_name" class="form-control " type="text" value="{{session('admin_search.first_name')}}" >
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Last Name:</label>
                                    <input name="last_name" class="form-control " type="text" value="{{session('admin_search.last_name')}}" >
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Email:</label>
                                    <input name="email" class="form-control " type="text" value="{{session('admin_search.email')}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Phone Number:</label>
                                    <input name="phone" class="form-control " type="text" value="{{session('admin_search.phone')}}">
                                </div>
                            
                                <div class="col-md-3 form-group">
                                    <label>Address Line 1:</label>
                                    <input name="address1" class="form-control " type="text" value="{{session('admin_search.address1')}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Address Line 2:</label>
                                    <input name="address2" class="form-control " type="text" value="{{session('admin_search.address2')}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>City:</label>
                                    <input name="city" class="form-control " type="text" value="{{session('admin_search.city')}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>State:</label>
                                    <input name="state" class="form-control " type="text" value="{{session('admin_search.state')}}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Zip Code:</label>
                                    <input name="zip" class="form-control " type="text" value="{{session('admin_search.zip')}}">
                                </div>
                                <div class="col-md-6 form-group" style="margin-top:30px">
                                    <button type="submit" class="btn btn-success pull-right" style="margin-left: 10px"><i class="fa fa-search"></i> Search</button> 
                                    <a href="{{route('admin.admin.resetfilter')}}" class="btn btn-warning pull-right"><i class="fa fa-remove"></i> Clear</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                {!! Form::close() !!}

                <div class="col-xs-12" style="padding: 0">
                    <div class="panel panel-default">
                        <div class="panel-heading">Search Result</div>
                        <div class="panel-body">
                            
                            <div class="row" style="overflow-x: scroll;">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Status</th> 
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email Address</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Zip Code</th> 
                                        <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($admins as $admin)
                                    <tr>
                                        <td>{{ucfirst($admin->status)}}</td>
                                        <td>{{$admin->first_name}}</td>
                                        <td>{{$admin->last_name}}</td>
                                        <td>{{$admin->email}}</td>
                                        <td>{{$admin->phone}}</td>
                                        <td>{{$admin->address1}}, {{$admin->address2}}</td>
                                        <td>{{$admin->city}}</td>
                                        <td>{{$admin->state}}</td>
                                        <td>{{$admin->zip}}</td>
                                        <td>
                                            <a href="/admin/profileAdmin/{{$admin->id}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                            @if ($admin->id==1)
                                            <a href="#" class="btn btn-danger btn-xs" disabled style="pointer-events: none"><i class="fa fa-close"  data-toggle="modal" data-target="#modal-delete-{{$admin->id}}"></i></a>
                                            @else
                                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-close"  data-toggle="modal" data-target="#modal-delete-{{$admin->id}}"></i></a>
                                            @endif
                                            @component('admin.admin.components.deletemodal')
                                            @slot('id') 
                                                {{ $admin->id }}
                                            @endslot
                                            @endcomponent
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
<script>

$(function () {
    //$('#detail_table').DataTable({"order": [1, 'desc']});
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
});
 




</script>

@endsection
