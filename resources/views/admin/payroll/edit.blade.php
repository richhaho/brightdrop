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
.table-payment input{text-align: center;}
.table-dragable tr td{
    cursor: move;
}
</style>
@endsection

@section('content')
    <section id="editPayment">
        <div class="row">
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            {!! Form::open(['route' => 'admin.payment.update','autocomplete' => 'off']) !!}
            <input type="hidden" name="from" value="{{$from}}">
            <input type="hidden" name="payment_id" value="{{$payment->id}}">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3 class="bold">Edit Payment</h3>
                <a href="{{route('admin.payroll.'.$from)}}" class="btn btn-danger pull-right" type="button" style="margin-left: 5px">Back</a>
                <button type="submit" class="btn btn-success pull-right"> <i calss="fa fa-save"></i> Save</button>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br></div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-4">
                    <div class="col-md-6 bold">Date In Queue:</div>
                    <div class="col-md-6">{{date('m/d/y',strtotime($payment->date_queue))}}</div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6 bold">Client Name:</div>
                    <div class="col-md-6">{{$client ? $client->client_name:'' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6 bold">Worker Name:</div>
                    <div class="col-md-6">{{$worker->fullname()}}</div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6 bold">Email Address:</div>
                    <div class="col-md-6" style="word-break: break-word">{{$worker->email_main}}</div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6 bold">Total Payment:</div>
                    <div class="col-md-6 bold" style="color: red">
                        <span class="updated_total_amount">{{number_format($payment->amount_updated!==null ? $payment->amount_updated:$payment->amount,2)}}</span>
                        <input type="hidden" name="amount_updated" class="amount_updated" value="{{$payment->amount_updated!==null ? $payment->amount_updated:$payment->amount}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-6 bold">Currency Type:</div>
                    <div class="col-md-6">{{strtoupper($payment->currency_type)}}</div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <br>
                <div class="col-md-12">
                    <h4 class="bold">- Payment Lines</h4>
                </div>
                <br>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box-body table_group">
                        <table id="detail_table" class="table table-hover text-center table-bordered">
                        <thead>
                        <tr class="warning">
                            <th width="10%">Service&nbsp;Number</th>
                            <th width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="10%">Quantity/Hours</th>
                            <th width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="5%">Remove</th>
                        </tr>
                        </thead>
                        <tbody class="table-payment table-dragable">
                        <?php 
                            $items = $payment->items_updated ? json_decode($payment->items_updated) : null;
                            $lines = $items ? $items->lines : $payment->lines();
                        ?>
                        @foreach ($lines as $line)
                        <tr>
                            <td><input name="service_id[{{$line->id}}]" type="text" class="form-control service_id" value="{{$line->service_id}}" required></td>
                            <td><input name="description[{{$line->id}}]" type="text" class="form-control description" value="{{$line->description}}" required></td>
                            <td><input name="quantity_hours[{{$line->id}}]" type="number" class="form-control quantity_hours" value="{{$line->quantity_hours}}" step="0.01" oninput="change_payment_line(this)" required></td>
                            <td><input name="rate[{{$line->id}}]" type="number" class="form-control rate" value="{{$line->rate}}" min="0" step="0.01" oninput="change_payment_line(this)" required></td>
                            <td><input name="amount[{{$line->id}}]" type="text" class="form-control amount" value="{{$line->amount}}" readonly required></td>
                            <td><button type="button" class="btn btn-danger " onclick="delete_payment_line(this)"><i class="fa fa-close"></i></button></td>
                        </tr>
                        @endforeach
                        </tbody>
                        </table>
                        <button type="button" class="btn btn-warning btn-add" onclick="add_payment_line(this)" style="margin-top: 10px"><i class="fa fa-plus"></i> Add New</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <br>
                <div class="col-md-12">
                    <h4 class="bold">- Timesheets</h4>
                </div>
                <br>
            </div>
            @if($timecard)
            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="col-md-12 col-lg-12 col-xs-12">
                    <div class="panel panel-default Worker-{{$timecard->worker()->id}} Timecards">
                        <div class="panel-heading" role="tab" id="heading{{$timecard->id}}">
                            <div class="panel-title ">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$timecard->id}}" aria-expanded="true" aria-controls="collapse{{$timecard->id}}">
                                <h4><i class="fa fa-plus-square"></i> Worker: {{$timecard->worker()->fullname}} , Billing Cycle Ending: {{date('m/d/y',strtotime($timecard->end_date))}}</h4>
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
                                        </tr>
                                        <tr style="border-bottom:2px grey solid !important">
                                            <th width="10%" class="warning">&nbsp;&nbsp;Hours&nbsp;&nbsp;</th>
                                            <th width="10%" class="warning">&nbsp;&nbsp;Minutes&nbsp;&nbsp;</th>
                                            <th width="10%" class="info">&nbsp;&nbsp;Hours Only&nbsp;&nbsp;</th>
                                            <th width="10%" class="success">&nbsp;&nbsp;Hours Only&nbsp;&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody class="timecard_{{$timecard->id}}">
                                        <?php
                                            $timecard = $items ? $items->timecard : $timecard;
                                            $timesheets = $items ? $timecard->timesheets : $timecard->timesheets()->get();
                                        ?>
                                        @foreach($timesheets as $timesheet)
                                        <?php 
                                            $each_date=$timesheet->date;
                                            
                                            $holiday=$client->holiday_shedule_offered=='yes_paid' ? \App\HolidaySchedule::where('holiday_date',$each_date)->where('clients_id',$client->id)->first() : [];
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
                                            <td class="warning"><input value="{{$timesheet->work_time_hours}}" name="work_time_hours[{{$timesheet->id}}]" type="number" min="0" max="{{$t==0 ? 999:23}}" step="{{$t==0 ? 0.01: 1}}" class="form-control worktime  work_time_hours{{$t}}" data="timecard_{{$timecard->id}}" data-toggle="tooltip" title="{{$tooltip_title}}" style="{{$style}}"></td>
                                            <td class="warning"><input value="{{$timesheet->work_time_minutes}}" name="work_time_minutes[{{$timesheet->id}}]" type="number" min="0" max="60" class="form-control worktime  work_time_minutes{{$t}}" data="timecard_{{$timecard->id}}" data-toggle="tooltip" title="{{$tooltip_title}}" style="{{$style}}"></td>
                                            <td class="info"><input value="{{$timesheet->pto_time_hours}}" name="pto_time_hours[{{$timesheet->id}}]" type="number" min="0" max="12" class="form-control ptotime  pto_time_hours{{$t}}" data="timecard_{{$timecard->id}}" data-toggle="tooltip" title="{{$tooltip_title}}" style="{{$style}}"><p class="invalid_value">Invalid Value</p></td>
                                            <td class="success"><input value="{{$timesheet->holiday_time_hours}}" name="holiday_time_hours[{{$timesheet->id}}]" type="number" min="0" max="12" class="form-control holidaytime  holiday_time_hours{{$t}}" data="timecard_{{$timecard->id}}" data-toggle="tooltip" title="{{$tooltip_title}}" style="{{$style}}"><p class="invalid_value">Invalid Value</p></td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                           <td colspan=7></td>
                                        </tr>
                                        <tr>
                                            <td colspan=2>Total Hours >>></td>
                                            <td colspan=2 class="warning total_worktime">{{$timecard->total_work_time }}</td>
                                            <td class="info total_ptotime">{{$timecard->total_pto_time}}</td>
                                            <td class="success total_holidaytime">{{$timecard->total_holiday_time}}</td>
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
            {!! Form::close() !!}
        </div>
    </section>

<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/plugins/jQueryUI/jquery-ui.min.js"></script> 
<script>
$('.table-dragable').sortable();
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
$('input').on('change', function(){
    if (!$(this).attr('data')) return;
    var client_class='.'+$(this).attr('data');
    var total_worktime=0;
    var total_ptotime=0;
    var total_holidaytime=0;
     
    for (i=0;i<16;i++){
        if (!$(client_class+' .work_time_hours'+i)) continue;       var work_time_hours=(parseInt($(client_class+' .work_time_hours'+i).val()) || 0)*60+(parseInt($(client_class+' .work_time_minutes'+i).val()) || 0);
        var holiday_time_hours=parseInt($(client_class+' .holiday_time_hours'+i).val()) || 0;
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
            $(client_class+' .work_time_hours'+i).parent().parent().find('input').css('color','red');
            $(client_class+' .work_time_hours'+i).parent().parent().find('p').css('color','red');
        }

        total_worktime+=work_time_hours;
        total_ptotime+=pto_time_hours;
        total_holidaytime+=holiday_time_hours;
    }
    total_worktime = Math.round(total_worktime/60*100)/100;
    $(client_class+' .total_worktime').text(total_worktime);
    $(client_class+' .total_ptotime').text(total_ptotime);
    $(client_class+' .total_holidaytime').text(total_holidaytime);

    // let regular_time = total_worktime>80 ? 80 : total_worktime;
    // let over_time = total_worktime>80 ? total_worktime-80 : 0;

    // calc_payment_time_line('0105', regular_time);
    // calc_payment_time_line('0110', over_time);
    // calc_payment_time_line('0150', total_ptotime);
    // calc_payment_time_line('0175', total_holidaytime);

    
});
function calc_payment_time_line(service_id, hours) {
    let hasLine = false;
    let description = {'0105': 'Regular Pay', '0110': 'Overtime Pay', '0150': 'PTO Pay', '0175': 'Holiday Pay'}
    $('.table-payment').find('.service_id').each(function(index){
        if ($(this).val()==service_id) {
            $(this).parent().parent().find('.quantity_hours').val(hours);
            if (hours==0) {
                $(this).parent().parent().remove();
                update_total_amount(); 
            } else {
                change_payment_line(this);
            }
            hasLine = true; 
            return;
        }
    });
    if (!hasLine && hours>0) {
        generage_new_payment_line(service_id,description[service_id],hours,'','');
    }
}

function change_payment_line(e) {
    let quantity_hours = parseFloat($(e).parent().parent().find('.quantity_hours').val()) || 0;
    let rate = parseFloat($(e).parent().parent().find('.rate').val()) || 0;
    let amount = Math.round(quantity_hours*rate*100)/100;
    $(e).parent().parent().find('.amount').val(amount);
    update_total_amount();
}
function update_total_amount() {
    let total_amount = 0;
    $('.table-payment').find('.amount').each(function(index){
        let amount = parseFloat($(this).val()) || 0;
        total_amount += amount;
    });
    total_amount = Math.round(total_amount*100)/100;
    $('.updated_total_amount').text(new Intl.NumberFormat().format(total_amount));
    $('.amount_updated').val(total_amount);
}
function add_payment_line() {
    generage_new_payment_line('','','','','');
}
function delete_payment_line(e) {
    $(e).parent().parent().remove();
    update_total_amount();
}
function generage_new_payment_line(service_id, description, quantity_hours, rate, amount) {
    let d = Date.now();
    let newline= '<tr>'
                +    '<td><input name="service_id['+d+']" value="'+service_id+'" type="text" class="form-control service_id" required></td>'
                +    '<td><input name="description['+d+']" value="'+description+'" type="text" class="form-control description" required></td>'
                +    '<td><input name="quantity_hours['+d+']" value="'+quantity_hours+'" type="number" class="form-control quantity_hours" required step="0.01" oninput="change_payment_line(this)"></td>'
                +    '<td><input name="rate['+d+']" value="'+rate+'" type="number" class="form-control rate" required min="0" step="0.01" oninput="change_payment_line(this)"></td>'
                +    '<td><input name="amount['+d+']" value="'+amount+'" type="text" class="form-control amount" required readonly></td>'
                +    '<td><button type="button" class="btn btn-danger " onclick="delete_payment_line(this)"><i class="fa fa-close"></i></button></td>'
                +'</tr>';
    $('.table-payment').append(newline);
}
</script>

@endsection
