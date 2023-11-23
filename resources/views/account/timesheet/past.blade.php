@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.canvasjs-chart-credit{
    display:none !important;
}
.btn-real{
    background-color:#058dc7 !important;
    border:none;
}
td,th{
    border:1px lightgrey solid !important}
.worktime{border:none;margin:0px;background-color:#fcf8e3;text-align:center}
.ptotime{border:none;margin:0px;background-color:#d9edf7;text-align:center}
.holidaytime{border:none;margin:0px;background-color:#dff0d8;text-align:center}
.notes{border:none;margin:0px;background-color:#f2dede;text-align:center}
</style>
@endsection
 
@section('content')


    <section id="NeedsApproval">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Past Timesheets</h3>
                        <br>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    {!! Form::open(['route' => 'account.timesheet.setfilter', 'class'=>'form-inline'])!!}
                        <input type="hidden" name="page" value="pastTimesheets">
                        <span>&nbsp; Client:&nbsp;&nbsp;&nbsp;</span>
                        {!!  Form::select('clients_id',$search_clients,$clients_id, ['class' => 'search_clients','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}
                        <span>&nbsp; Start Date:</span>
                        {!!  Form::date('start_date',$start_date, ['class' => 'start_date','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}<br>
                        <span>&nbsp; Worker:</span>
                        {!!  Form::select('workers_id',$search_workers,$workers_id, ['class' => 'search_workers','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}
                        <span>&nbsp; End Date:&nbsp;&nbsp;</span>
                        {!!  Form::date('end_date',$end_date, ['class' => 'end_date','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}
                        &nbsp;&nbsp;<button type="submit" class="btn btn-success btn-xs btn-search" > <i class="fa fa-search"></i> Search</button>
                        &nbsp;&nbsp;<a href="{{ route('account.timesheet.resetfilter') }}?page=pastTimesheets" class="btn btn-danger btn-xs"> <i class="fa fa-times"></i>Clear</a>
                    {!! Form::close() !!}
                    </div>
                </div>
            </div><br>

            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif

            @if(count($timesheets))
                <div class="row right" style="padding: 10px 40px 0px 20px;"><button class="btn btn-success btn-md btn-download">download</button></div>
                <div class="row" style="padding: 10px 10px 40px 20px;overflow-x: scroll; clear: both;">
                    <div class="box-body table_group">
                        <table id="detail_table" class="table table-hover text-center table-bordered">
                            <thead style="border-bottom:3px lightgrey solid !important" >
                                <tr>
                                    <th width="10%">Day</th>
                                    <th width="10%">Date</th>
                                    <th width="40%" class="warning">Work Time</th>
                                    <th width="10%" class="info">PTO&nbsp;Time</th>
                                    <th width="10%" class="success">Paid&nbsp;Holiday</th>
                                    <th width="20%" class="danger">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach($timesheets as $timesheet)
                                    <tr>
                                        <td>{{$timesheet->day}}</td>
                                        <td>{{date('m/d/y',strtotime($timesheet->date))}}</td>
                                        <td class="warning">{{$timesheet->work_time_hours + $timesheet->work_time_minutes/60}}</td>
                                        <td class="info">{{$timesheet->pto_time_hours}}</td>
                                        <td class="success">{{$timesheet->holiday_time_hours}}</td>
                                        <td class="danger">{{$timesheet->notes}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> 
                </div>
            @else
                <div class="col-md-12 col-lg-12 col-xs-12 text-center" style="padding-top:20px">
                    There is no timesheets
                </div>
            @endif
        </div>
    </section>

<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>
    const processRow = (row) => {
        const row_str = (row.day?row.day.trim():'') + "," 
                    + (row.date?row.date.trim():'') + ","
                    + (row.work_time_hours?row.work_time_hours.toString():'') + ","
                    + (row.work_time_minutes?row.work_time_minutes.toString():'') + ","
                    + (row.pto_time_hours?row.pto_time_hours.toString():'') + ","
                    + (row.holiday_time_hours?row.holiday_time_hours.toString():'') + ","
                    + (row.notes?row.notes.toString().trim():'');
        return row_str + '\n';
    };
    const exportToCsv = (filename, rows) => {
        var csvFile = 'Day,Date,Work Time Hours, Work Time Minutes, PTO Time, Paid Holiday, Notes\n';
        for (var i = 0; i < rows.length; i++) {
            csvFile += processRow(rows[i]);
        }
        const blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
        if (navigator.msSaveBlob) { // IE 10+
            navigator.msSaveBlob(blob, filename);
        } else {
            const link = document.createElement("a");
            if (link.download !== undefined) { // feature detection
                // Browsers that support HTML5 download attribute
                const url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
    }
    const csv_string = `<?php echo json_encode($timesheets);?>`;
    const csv_data = JSON.parse(csv_string);
    const filename = `<?php echo $client_name.'-'.$worker_name.'('.$start_date.'~'.$end_date.')'; ?>`;
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

        $('[data-toggle="tooltip"]').tooltip();
        $('.search_clients').change(function () {
            const id = $('.search_clients').val();
            if (!id) {
                $('.search_workers').empty();
                return;
            }
            $.get("{{route('account.client.activeWorkers')}}",{"clients_id":id}).done(function( workers ) {
                let workerOptions = '<option value=""></option><option value="All">All</option>';
                $('.search_workers').empty();
                workers.forEach((worker) => {
                    workerOptions += '<option value="'+worker.id+'">'+worker.fullname+'</option>';
                });
                $('.search_workers').html(workerOptions);
            });
        });
        $('.btn-download').on('click', () => {
            exportToCsv(filename,csv_data);
        });
    });
</script>

@endsection
