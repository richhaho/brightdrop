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
.invalid_value{
    color: red;
    font-size: 10px;
    display: none;
}
</style>
@endsection

@section('content')


    <section id="NeedsApproval">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Timesheet - Log time</h3>
                        <br>
                    </div>
                </div>
            </div>

            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                @foreach($timecards as $timecard)
                @if($timecard->client())
                {!! Form::open(['route' => 'worker.storetime','autocomplete' => 'off']) !!}
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading{{$timecard->id}}">
                        <div class="panel-title ">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$timecard->id}}" aria-expanded="true" aria-controls="collapse{{$timecard->id}}">
                            <h4><i class="fa fa-plus-square"></i> Client: {{$timecard->client()->client_name}} </h4>
                        </a>
                        </div>
                        <div id="collapse{{$timecard->id}}" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading{{$timecard->id}}">    
                             
                            <?php 
                                $end_date=date('m/d/y',strtotime($timecard->end_date));
                                $start_date=date('m/d/y',strtotime($timecard->start_date));
                                $client=$timecard->client();
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
                                    <?php 
                                        $each_date=$timesheet->date;
                                        
                                        $holiday=$client->holiday_shedule_offered=='yes_paid' ? \App\HolidaySchedule::where('holiday_date',$each_date)->where('clients_id',$client->id)->first() : [];
                                        if ($holiday) {
                                            $timesheet->holiday_time_hours=$worker_info->target_hours_week/5;
                                            $timesheet->save();
                                        } else {
                                            $timesheet->holiday_time_hours=null;
                                            $timesheet->save();
                                        }
                                        $tooltip_holiday=count($holiday)>0 ? ' This is a holiday, ' .$holiday->holiday_name.'. Do you want to log extra time?':'';
                                        $tooltip_title=$tooltip_holiday;
                                        $t++;

                                        $style="";
                                        // if ($timesheet->work_time_hours>0 || $timesheet->work_time_minutes>0){
                                        //    if($timesheet->pto_time_hours>0 || $timesheet->holiday_time_hours>0) $style="color:red;";
                                        // }
                                        // if($timesheet->pto_time_hours>0 && $timesheet->holiday_time_hours>0) $style="color:red;";
                                    ?>
                                    <tr style="{{$style}}">
                                        <td>{{$timesheet->day}}<input type="hidden" name="day[{{$timesheet->id}}]" value="{{$t}}"></td>
                                        <td>{{date('m/d/y',strtotime($each_date))}}</td>
                                        <td class="warning"><input style="{{$style}}" value="{{$timesheet->work_time_hours}}" name="work_time_hours[{{$timesheet->id}}]" type="number" min="0" max="{{$t==0 ? 999:23}}" step="{{$t==0 ? 0.01: 1}}" class="form-control worktime  work_time_hours{{$t}}" data="timecard_{{$timecard->id}}" data-toggle="tooltip" title="{{$tooltip_title}}"></td>
                                        <td class="warning"><input style="{{$style}}" value="{{$timesheet->work_time_minutes}}" name="work_time_minutes[{{$timesheet->id}}]" type="number" min="0" max="60" class="form-control worktime  work_time_minutes{{$t}}" data="timecard_{{$timecard->id}}" data-toggle="tooltip" title="{{$tooltip_title}}"></td>
                                        <td class="info"><input style="{{$style}}" value="{{$timesheet->pto_time_hours}}" name="pto_time_hours[{{$timesheet->id}}]" type="number" min="0" max="12" class="form-control ptotime  pto_time_hours{{$t}}" data="timecard_{{$timecard->id}}" data-toggle="tooltip" title="{{$tooltip_title}}"><p class="invalid_value">Invalid Value</p></td>
                                         
                                        <td class="success"><p  style="{{$style}}" class="form-control holidaytime holiday_time_hours{{$t}}">{{$timesheet->holiday_time_hours ? $timesheet->holiday_time_hours:''}}</p>
                                         
                                        <td class="danger"><input style="{{$style}}" value="{{$timesheet->notes}}" name="notes[{{$timesheet->id}}]" type="text" class="form-control notes"></td>
                                    </tr>

                                    @endforeach
                                    <?php 
                                    $timecard->total_holiday_time=$timecard->timesheets()->sum('holiday_time_hours');
                                    $timecard->save();

                                    ?>
                                    <tr>
                                       <td colspan=7></td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>Total Hours >>></td>
                                        <td colspan=2 class="warning total_worktime">{{$timecard->total_work_time }}</td>
                                        <td class="info total_ptotime">{{$timecard->total_pto_time}}</td>
                                        <td class="success total_holidaytime">{{$timecard->total_holiday_time}}</td>
                                        <td ><button class="btn btn-success" type="submit">Save Hours</button></td>
                                    </tr>
                                    
                                    </tbody>

                                    </table>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                @endif
                @endforeach
            </div>
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

$('.work_time_hours0').change(function(){
    let worktime = parseFloat($(this).val()) || 0;
    let hours = Math.floor(worktime);
    let minutes = Math.round((worktime-hours) * 60);
    if (minutes) {
        $(this).val(hours);
        $(this).parent().parent().find('.work_time_minutes0').val(minutes);
    }
});

$('input').change(function(){
    if (!$(this).attr('data')) return;
    var client_class='.'+$(this).attr('data');
    var total_worktime=0;
    var total_ptotime=0;
     
    for (i=0;i<16;i++){
        if (!$(client_class+' .work_time_hours'+i)) continue;       var work_time_hours=(parseInt($(client_class+' .work_time_hours'+i).val()) || 0)*60+(parseInt($(client_class+' .work_time_minutes'+i).val()) || 0);
        var holiday_time_hours=parseInt($(client_class+' .holiday_time_hours'+i).text()) || 0;
        var pto_time_hours=parseInt($(client_class+' .pto_time_hours'+i).val())|| 0;
        // $(client_class+' .work_time_hours'+i).parent().parent().css('color','black');
        // $(client_class+' .work_time_hours'+i).parent().parent().find('input').css('color','black');
        // $(client_class+' .work_time_hours'+i).parent().parent().find('p').css('color','black');
        // if(work_time_hours>0 && (holiday_time_hours>0 || pto_time_hours>0)){
        //     $(client_class+' .work_time_hours'+i).parent().parent().css('color','red');
        //     $(client_class+' .work_time_hours'+i).parent().parent().find('input').css('color', 'red');
        //     $(client_class+' .work_time_hours'+i).parent().parent().find('p').css('color', 'red');
        // }
        // if (holiday_time_hours>0 && pto_time_hours>0){
        //     $(client_class+' .work_time_hours'+i).parent().parent().css('color','red');
        //     $(client_class+' .work_time_hours'+i).parent().parent().find('input').css('color', 'red');
        //     $(client_class+' .work_time_hours'+i).parent().parent().find('p').css('color', 'red');
        // }

        total_worktime+=work_time_hours;
        total_ptotime+=pto_time_hours;
    }
    
    $(client_class+' .total_worktime').text(Math.round(total_worktime/60*100)/100);
    $(client_class+' .total_ptotime').text(total_ptotime);
     
});

// $('form').submit(function(event){
     
//     var timecard_tbody='.'+$(this).find('tbody').attr('class');
//     var lunchtime_max_minutes=parseInt($(this).find('.lunchtime_max_minutes').val()) || 0;
//     var breaktime_max_minutes=parseInt($(this).find('.breaktime_max_minutes').val()) || 0;
    
//     var error_count=0;
//     for (i=0;i<14;i++){
//         var worktime=(parseInt($(timecard_tbody+' .work_time_hours'+i).val()) || 0)*60+(parseInt($(timecard_tbody+' .work_time_minutes'+i).val()) || 0);
//         var lunchtime=(parseInt($(timecard_tbody+' .lunch_time_hours'+i).val())|| 0)*60+(parseInt($(timecard_tbody+' .lunch_time_minutes'+i).val())|| 0);
//         var breaktime=(parseInt($(timecard_tbody+' .break_time_hours'+i).val())|| 0)*60+(parseInt($(timecard_tbody+' .break_time_minutes'+i).val())|| 0);
//         if (lunchtime>lunchtime_max_minutes || (worktime==0 && lunchtime>0)) {
//             $(timecard_tbody+' .lunch_time_hours'+i).parent().find('.invalid_value').css('display','block');
//             $(timecard_tbody+' .lunch_time_minutes'+i).parent().find('.invalid_value').css('display','block');
//             error_count++;
//         }
//         if (breaktime>breaktime_max_minutes || (worktime==0 && breaktime>0)) {
//             $(timecard_tbody+' .break_time_hours'+i).parent().find('.invalid_value').css('display','block');
//             $(timecard_tbody+' .break_time_minutes'+i).parent().find('.invalid_value').css('display','block');
//             error_count++;
//         }
//     }
//     if (error_count>0) event.preventDefault();
// });
$('input').click(function(){
    $(this).parent().parent().find('.invalid_value').css('display','none');
});

</script>

@endsection
