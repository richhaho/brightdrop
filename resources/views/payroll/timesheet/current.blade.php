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
                        <h3 class="bold">Current Timesheets</h3>
                        <br>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    {!! Form::open(['route' => 'payroll.timesheet.setfilter', 'class'=>'form-inline'])!!}
                        <input type="hidden" name="page" value="currentTimesheets">
                        <span>&nbsp; Client:&nbsp;&nbsp;&nbsp;</span>
                        {!!  Form::select('clients_id',$search_clients,$clients_id, ['class' => 'search_clients','style'=>'margin-left:5px;margin-top:2px;width:200px', 'required'=>true]) !!}<br>
                        <span>&nbsp; Worker:</span>
                        {!!  Form::select('workers_id',$search_workers,$workers_id, ['class' => 'search_workers','style'=>'margin-left:5px;margin-top:2px;width:200px']) !!}
                        &nbsp;&nbsp;<button type="submit" class="btn btn-success btn-xs btn-search" > <i class="fa fa-search"></i> Search</button> 
                        &nbsp;&nbsp;<a href="{{ route('payroll.timesheet.resetfilter') }}?page=currentTimesheets" class="btn btn-danger btn-xs"> <i class="fa fa-times"></i> Clear</a>
                    {!! Form::close() !!}
                    </div>
                </div>
            </div><br>

            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif

            <div class="col-md-12 col-lg-12 col-xs-12">
                @foreach($timecards as $timecard)
                <?php 
                    $end_date=date('m/d/y',strtotime($timecard->end_date));
                    $start_date=date('m/d/y',strtotime($timecard->start_date));
                    $client=$timecard->client();
                    $worker=$timecard->worker();
                    $t=-1;
                    $worker_info=\App\ClientInfoWorkers::where('workers_id',$timecard->workers_id)->where('clients_id',$timecard->clients_id)->first();
                ?>
                @if($client && $worker)
                {!! Form::open(['route' => 'payroll.timesheet.submitCurrent','autocomplete' => 'off']) !!}
                <div class="panel panel-default Worker-{{$worker->id}} Timecards">
                    <div class="panel-heading" role="tab" id="heading{{$timecard->id}}">
                        <div class="panel-title ">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$timecard->id}}" aria-expanded="true" aria-controls="collapse{{$timecard->id}}">
                            <h4><i class="fa fa-plus-square"></i> Worker: {{$worker->fullname}} , Billing Cycle Ending: {{date('m/d/y',strtotime($timecard->end_date))}}</h4>
                        </a>
                        </div>
                        <div id="collapse{{$timecard->id}}" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading{{$timecard->id}}">    
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
                                        if ($timesheet->work_time_hours>0 || $timesheet->work_time_minutes>0){
                                           if($timesheet->pto_time_hours>0 || $timesheet->holiday_time_hours>0) $style="color:red;";
                                        }
                                        if($timesheet->pto_time_hours>0 && $timesheet->holiday_time_hours>0) $style="color:red;";

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
                                        <td class="success">{{$timecard->total_holiday_time}}</td>
                                        <td >
                                            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modal-approve-hours-{{$timecard->id}}"><i class="fa fa-check"></i> Save Hours</a>
                                            <div class="modal fade" id="modal-approve-hours-{{$timecard->id}}" tabindex="-1" role="dialog">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title">Save Hours</h4>
                                                  </div>
                                                  <div class="modal-body">
                                                      <p>Are you sure you want to save hours?</p>
                                                  </div>
                                                  <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success btn-submit-approve"> Save</button>&nbsp;&nbsp;
                                                        <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                                                  </div>
                                                </div><!-- /.modal-content -->
                                              </div><!-- /.modal-dialog -->
                                            </div>
                                        </td>
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
    $('.btn-submit-approve').click(function() {
        $(this).css('pointer-events','none');
        $(this).css('opacity','0.5');
    });
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
        $(client_class+' .work_time_hours'+i).parent().parent().css('color','black');
        $(client_class+' .work_time_hours'+i).parent().parent().find('input').css('color','black');
        $(client_class+' .work_time_hours'+i).parent().parent().find('p').css('color','black');
        if(work_time_hours>0 && (holiday_time_hours>0 || pto_time_hours>0)){
            $(client_class+' .work_time_hours'+i).parent().parent().css('color','red');
            $(client_class+' .work_time_hours'+i).parent().parent().find('input').css('color', 'red');
            $(client_class+' .work_time_hours'+i).parent().parent().find('p').css('color', 'red');
        }
        if (holiday_time_hours>0 && pto_time_hours>0){
            $(client_class+' .work_time_hours'+i).parent().parent().css('color','red');
            $(client_class+' .work_time_hours'+i).parent().parent().find('input').css('color', 'red');
            $(client_class+' .work_time_hours'+i).parent().parent().find('p').css('color', 'red');
        }

        total_worktime+=work_time_hours;
        total_ptotime+=pto_time_hours;
    }
    
    $(client_class+' .total_worktime').text(Math.round(total_worktime/60*100)/100);
    $(client_class+' .total_ptotime').text(total_ptotime);
     
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
</script>

@endsection
