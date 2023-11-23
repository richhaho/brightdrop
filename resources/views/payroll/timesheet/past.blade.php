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
                    {!! Form::open(['route' => 'payroll.timesheet.setfilter', 'class'=>'form-inline'])!!}
                        <input type="hidden" name="page" value="pastTimesheets">
                        <span>&nbsp; Client:&nbsp;&nbsp;&nbsp;</span>
                        {!!  Form::select('clients_id',$search_clients,$clients_id, ['class' => 'search_clients','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}<br>
                        <span>&nbsp; Worker:</span>
                        {!!  Form::select('workers_id',$search_workers,$workers_id, ['class' => 'search_workers','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}
                        &nbsp;&nbsp;<button type="submit" class="btn btn-success btn-xs btn-search" > <i class="fa fa-search"></i> Search</button> 
                        &nbsp;&nbsp;<a href="{{ route('payroll.timesheet.resetfilter') }}?page=pastTimesheets" class="btn btn-danger btn-xs"> <i class="fa fa-times"></i> Clear</a>
                    {!! Form::close() !!}
                    </div>
                </div>
            </div><br>

            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif

            

            @foreach($timecards as $timecard)
            @if (count($timecard->client())>0 && count($timecard->worker())>0)
            <div class="Status Status-{{$timecard->status}}">
            <div class="col-md-12 col-lg-12 col-xs-12 Client-{{$timecard->client()->id}} Clients">
                <div class="panel panel-default Worker-{{$timecard->worker()->id}} Timecards">
                    <div class="panel-heading" role="tab" id="heading{{$timecard->id}}">
                        <div class="panel-title ">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$timecard->id}}" aria-expanded="true" aria-controls="collapse{{$timecard->id}}">
                            <h4><i class="fa fa-plus-square"></i> Client: {{$timecard->client()->client_name}}, Worker: {{$timecard->worker()->fullname}} , Billing Cycle Ending: {{date('m/d/y',strtotime($timecard->end_date))}}</h4>
                        </a>
                        </div>
                        <div id="collapse{{$timecard->id}}" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading{{$timecard->id}}">    
                             
                            <?php 
                                $end_date=date('m/d/y',strtotime($timecard->end_date));
                                $start_date=date('m/d/y',strtotime($timecard->start_date));
                                $client=$timecard->client();
                                $worker=$timecard->worker();
                                $t=-1;
                                $worker_info=\App\ClientInfoWorkers::where('workers_id',$timecard->workers_id)->where('clients_id',$timecard->clients_id)->first();
                            ?>
                            <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-6 bold">Worker:</div>
                                        <div class="col-md-6">{{$worker->fullname()}}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="col-md-6 bold">Start Date:</div>
                                        <div class="col-md-6">{{$start_date}}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="col-md-6 bold">End Date:</div>
                                        <div class="col-md-6">{{$end_date}}</div>
                                    </div>
                                    <input type="hidden" name="timecard_id" value="{{$timecard->id}}">
                            </div>
                            <div class="row" style="padding: 10px 0px 40px 0px;overflow-x: scroll;">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead style="border-bottom:3px lightgrey solid !important" >
                                    <tr>
                                        <th rowspan="2" width="10%">Day</th>
                                        <th rowspan="2" width="10%">Date</th>
                                        <th colspan="2" width="40%" class="warning">Work Time</th>
                                        <th width="10%" class="info">PTO&nbsp;Time</th>
                                        <th width="10%" class="success">Paid&nbsp;Holiday</th>
                                        <th rowspan="2" width="20%" class="danger">Notes</th>
                                    </tr>
                                    <tr style="border-bottom:2px grey solid !important">
                                        <th width="10%" class="warning">&nbsp;&nbsp;Hours&nbsp;&nbsp;</th>
                                        <th width="10%" class="warning">&nbsp;&nbsp;Minutes&nbsp;&nbsp;</th>
                                        <th width="10%" class="info">&nbsp;&nbsp;Hours Only&nbsp;&nbsp;</th>
                                        <th width="10%" class="success">&nbsp;&nbsp;Hours Only&nbsp;&nbsp;</th>
                                         
                                        
                                    </tr>
                                    </thead>
                                    <tbody class="timecard_{{$timecard->id}}">
                                    @foreach($timecard->timesheets()->get() as $timesheet)
                                    <tr>
                                        <td>{{$timesheet->day}}</td>
                                        <td>{{date('m/d/y',strtotime($timesheet->date))}}</td>
                                        <td class="warning">{{$timesheet->work_time_hours}}</td>
                                        <td class="warning">{{$timesheet->work_time_minutes}}</td>
                                        <td class="info">{{$timesheet->pto_time_hours}}</td>
                                        <td class="success">{{$timesheet->holiday_time_hours}}</td>
                                        <td class="danger">{{$timesheet->notes}}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                       <td colspan=7></td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>Total Hours >>></td>
                                        <td colspan=2 class="warning total_worktime">{{$timecard->total_work_time }}</td>
                                        <td class="info total_ptotime">{{$timecard->total_pto_time}}</td>
                                        <td class="success">{{$timecard->total_holiday_time}}</td>
                                        <td > 
                                        </td>
                                    </tr>
                                    
                                    </tbody>

                                    </table>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            @endif
            @endforeach
        </div>
    </section>

<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
    var anchor = window.location.hash;
    //console.log(anchor);
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
});

$('.search_clients').change(function () {
    const id = $('.search_clients').val();
    if (!id) {
        $('.search_workers').empty();
        return;
    }
    $.get("{{route('payroll.client.activeWorkers')}}",{"clients_id":id}).done(function( workers ) {
        let workerOptions = '<option value=""></option>';
        $('.search_workers').empty();
        workers.forEach((worker) => {
            workerOptions += '<option value="'+worker.id+'">'+worker.fullname+'</option>';
        });
        $('.search_workers').html(workerOptions);
    });
});

// $('.btn-search').click(function(){
//     var status=$('.search_status').val();
//     var client=$('.search_client').val();
//     var worker=$('.search_worker').val();
//     if (status=='all'){
//         $('.Status').css('display','block');
//     }else{
//         $('.Status').css('display','none');
//         $('.Status-'+status).css('display','block');
//     }
//     if (client==0){
//         $('.Clients').css('display','block');
//     }else{
//         $('.Clients').css('display','none');
//         $('.Client-'+client).css('display','block');
//     }
//     if (worker==0){
//         $('.Timecards').css('display','block');
//     }else{
//         $('.Timecards').css('display','none');
//         $('.Worker-'+worker).css('display','block');
//     }
// });

</script>

@endsection
