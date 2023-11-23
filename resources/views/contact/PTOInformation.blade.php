@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important}   
</style>
@endsection

@section('content')
@if ($client)

    <section id="Holiday">
        <div class="row">
 
            <div class="col-md-12 col-lg-12 col-xs-12 main_content">
                
                 
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3>PTO Information</h3></div>
                        <div class="panel-body">
                            @if ($client->pto_infomation=='yes')
                            <div class="row">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover  table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="20%">Worker</th>
                                        <th width="20%">Client</th>
                                        <th width="60%">PTO Summary</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($workers as $worker)
                                    <?php 
                                        $info=$client->assigned_worker_info()->where('workers_id',$worker->id)->first();
                                        $Year = date('Y');
                                        $currentyear=date('Y');
                                        $hiredyear=date('Y',strtotime($info->hired_at));
                                        if ($currentyear==$hiredyear){
                                            $pto_days_worker_default=$info->ptodays_current_calendar;
                                        }else{
                                            $pto_days_worker_default=$info->ptodays_full_calendar;
                                        }
                                        $pto_timesheets=$worker->timesheets()->where('clients_id',$client->id)->where('date','like',$Year.'-%')->get();
                                        $pto_used = array();
                                        $pto_sum = 0;
                                        foreach ($pto_timesheets as $pto_timesheet) {
                                            if ($pto_timesheet->status=='approved' && $pto_timesheet->pto_time_hours>0 && $pto_timesheet->pto_time_hours_updated===null) {
                                                $pto_used[]=[
                                                    'date' => $pto_timesheet->date,
                                                    'hours' => $pto_timesheet->pto_time_hours
                                                ];
                                                $pto_sum+=$pto_timesheet->pto_time_hours;
                                            }
                                            if ($pto_timesheet->pto_time_hours_updated>0) {
                                                $pto_used[]=[
                                                    'date' => $pto_timesheet->date,
                                                    'hours' => $pto_timesheet->pto_time_hours_updated
                                                ];
                                                $pto_sum+=$pto_timesheet->pto_time_hours_updated;
                                            }
                                        }
                                        $pto_remain=$pto_days_worker_default*8-$pto_sum;
                                        $pto_remaining=intval($pto_remain/8).' Days, '.($pto_remain-intval($pto_remain/8)*8).' Hours';
                                    ?>
                                    <tr>
                                        <td>{{$worker->fullname()}}</td>
                                        <td>{{$client->client_name}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-3 form-group">
                                                    <label>PTO Remaining:</label>
                                                </div>
                                                <div class="col-md-9 form-group">
                                                    <label>{{$pto_remaining}}</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 form-group">
                                                    <label>PTO Used:</label>
                                                </div>
                                                <div class="col-md-9 form-group">
                                                    @foreach ($pto_used as $pto)
                                                    <div class="row">
                                                        <div class="col-md-6 form-group">
                                                            <label>{{$pto['date']}}</label>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label>{{$pto['hours']}} Hours</label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                     
                                                </div>
                                            </div>    
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>

                                    </table>
                                </div>

                            </div>
                            @else
                            <h4>{{$client->client_name}} didn't set PTO information on their profile.</h4>
                            @endif
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
    $('#detail_table').DataTable({"order": [1, 'desc']});
});


</script>

@endsection
