@section('css')

<style type="text/css">
   #page  h1 {
        display: block;
        font-size: 1.5em;
        margin-top: 0px;
        margin-bottom: 0px;
        font-weight: bold;
    }
    th{background-color: #c0c0c0 !important;}
@media print {
    #page {
        font-size: 14pt !important;

    }
    .small {
        font-size: 10pt !important;
     
    }
    .bold {
         font-size: 14pt !important;
        font-weight: bold;
    }
    .noprint{
        display: none;
    }
    .active{
        background-color: #c0c0c0 !important;
    }
    th{background-color: #c0c0c0 !important;}
}

@media screen {
    #page {

        font-size: 12pt !important;

    }
    
    .small {
        font-size: 9pt !important;
  
    }
    .bold {
        font-size: 12pt !important;
        font-weight: bold;
    }
    th{background-color: #c0c0c0 !important;}
}
</style>

@append

<div id="page">
    <div class="content">
        
        <h1 class="text-center">Timesheet</h1>
        <p>&nbsp;</p>
        <table style="width: 50%">
            <tbody>
                <tr>
                    <td class="bold">Worker:</td><td>{{$timecard->worker()->fullname}}</td>
                </tr>
                <tr>
                    <td class="bold">Client:</td><td>{{$timecard->client()->client_name}}</td>
                </tr>
                <tr>
                    <td class="bold">Start Date:</td><td>{{date('m/d/y',strtotime($timecard->start_date))}}</td>
                </tr>
                <tr>
                    <td class="bold">End Date:</td><td>{{date('m/d/y',strtotime($timecard->end_date))}}</td>
                </tr>

            </tbody>
        </table>
        <div class="box-body table_group">
            <table id="detail_table" class="table table-hover text-center table-bordered">
            <thead style="border-bottom:3px lightgrey solid !important" >
            <tr>
                <th rowspan="2" width="20%" style="background-color: #c0c0c0 !important;text-align: center;">Day</th>
                <th rowspan="2" width="20%" style="background-color: #c0c0c0 !important;text-align: center;">Date</th>
                <th colspan="2" width="30%" style="background-color: #c0c0c0 !important;text-align: center;">Work Time</th>
                <th rowspan="2" width="15%" style="background-color: #c0c0c0 !important;text-align: center;">PTO Time</th>
                <th rowspan="2" width="15%" style="background-color: #c0c0c0 !important;text-align: center;">Paid Holiday</th>
            </tr>
            <tr style="border-bottom:2px grey solid !important">
                <th width="15%" style="background-color: #c0c0c0 !important;text-align: center;">Hours</th>
                <th width="15%" style="background-color: #c0c0c0 !important;text-align: center;">Minutes</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $timesheets=$timecard->timesheets()->get();
            foreach ($timesheets as $timesheet) {
                $each_date=$timesheet->date;
                $each_day=date('l',strtotime($each_date));
            ?>
            <tr>
                <td>{{$each_day}}</td>
                <td>{{date('m/d/y',strtotime($each_date))}}</td>
                <td>{{$timesheet->work_time_hours}}</td>
                <td>{{$timesheet->work_time_minutes}}</td>
                <td>{{$timesheet->pto_time_hours}}</td>
                <td>{{$timesheet->holiday_time_hours}}</td>
            </tr>
            <?php } ?>
            <tr>
               <td colspan=6></td>
            </tr>
            <tr>
                <td colspan=2>Total Hours >>></td>
                <td colspan=2>{{floor($timecard->total_work_time*100)/100}}</td>
                <td>{{$timecard->total_pto_time}}</td>
                <td>{{$timecard->total_holiday_time}}</td>
                 
            </tr>
            
            </tbody>

            </table>
        </div>

        
     
     
    </div>
</div>