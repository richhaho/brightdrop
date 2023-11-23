@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
 
</style>
@endsection

@section('content')
@if ($client)
    <section id="Holiday">
        <div class="row">
 
            <div class="col-md-12 col-lg-12 col-xs-12 main_content">
                
                 
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3>Holiday Schedule - {{$current_year}}</h3></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="50%">Holiday Name</th>
                                        <th width="20%">Day</th>
                                        <th width="30%">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($holidays as $holiday)
                                    <tr>
                                        <td>{{$holiday->holiday_name}}</td>
                                        <td>{{date('l',strtotime($holiday->holiday_date))}}</td>
                                        <td>{{date('m/d/y',strtotime($holiday->holiday_date))}}</td>
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
@else
<h3>You have not been assigned to a client yet.</h3>
@endif
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    //$('#detail_table').DataTable({"order": [1, 'desc']});
});


</script>

@endsection
