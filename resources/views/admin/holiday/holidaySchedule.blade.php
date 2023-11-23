@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important}  
</style>
@endsection

@section('content')


    <section id="LogTime">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Holiday Schedule - Default</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="panel panel-default open_cash_child">
                    <div class="panel-body">
                        <div class="row" style="overflow-x: scroll;">
                            <div class="box-body table_group">
                                {!! Form::open(['route' => 'admin.addholiday','autocomplete' => 'off']) !!}
                                <table id="" class="table table-hover text-center table-bordered">
                                <thead>
                                <tr class="info">
                                    <th width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Holiday&nbsp;&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th width="25%">Holiday Date</th>
                                    <th width="25%">  </th>
                                </tr>
                                </thead>
                                <tbody class=" add_advance">
                                <tr>
                                    <td><input name="holiday_name" type="text" class="form-control" value="" required></td>
                                    <td><input name="holiday_date" type="date" class="form-control" value="" required></td>
                                    <td><button type="submit" class="btn btn-success"> <i class="fa fa-plus"></i> Add Holiday</button> </td>
                                     
                                </tr>
                                </tbody>
                                </table>
                                {!! Form::close() !!}
                                
                            </div> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="panel panel-default open_cash_child">
                    
                    <div class="panel-body">
                        <div class="row" style="overflow-x: scroll;">
                            <div class="box-body table_group">
                                 
                                <table id="detail_table" class="table text-center table-bordered">
                                <thead>
                                <tr class="">
                                    <th width="10%">Years</th>
                                    <th width="90%">Holiday List</th>
                                    
                                </tr>
                                </thead>
                                <tbody class="">
                                @foreach($holiday_defaults as $holiday_default)
                                <tr>
                                    <td>{{$holiday_default['year']}} - Observed</td>
                                    <td>
                                        <table class="table text-center ">
                                        <thead>
                                        <tr class="warning">
                                            <th width="50%"></th>
                                            <th width="30%"></th>
                                            <th width="20%"></th>
                                            
                                        </tr>
                                        <tbody>
                                            @foreach($holiday_default['holidays'] as $holiday)
                                            <tr>
                                                <td>{{$holiday->holiday_name}}
                                                </td>

                                                <td>
                                                    {{date('l, F d, Y',strtotime($holiday->holiday_date))}}
                                                </td>

                                                <td>
                                                    @component('admin.holiday.components.editholiday_modal')
                                                    @slot('id') 
                                                        {{ $holiday->id }}
                                                    @endslot
                                                    @slot('holiday_name') 
                                                        {{ $holiday->holiday_name}}
                                                    @endslot
                                                    @slot('holiday_date') 
                                                        {{ $holiday->holiday_date}}
                                                    @endslot
                                                    @endcomponent
                                                    @component('admin.holiday.components.deleteholiday_modal')
                                                    @slot('id') 
                                                        {{ $holiday->id }}
                                                    @endslot
                                                    @endcomponent
                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>

                                        </table>

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
    $('#detail_table').DataTable({"order": [0, 'asc']});
});


// $('.btn-add').click(function(){
//     var tb=`<tr>
//                 <td><input type="text" class="form-control" value=""></td>
//                 <td><input type="date" class="form-control" value=""></td>
//                 <td><input type="date" class="form-control" value=""></td>
//                 <td><input type="date" class="form-control" value=""></td>
//             </tr>`;
//     $('.open_cash .add_advance').append(tb);
// });

</script>

@endsection
