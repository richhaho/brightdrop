@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>

</style>
@endsection

@section('content')


    <section id="LogTime">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" >
                        <h4>The following is a list of paid holidays.  You will receive holiday pay for these days regardless if you work on these days.  However, this does not necessarily mean these are off days.  Please consult your Client or Account Manager to determine if these are off days for you. </h4>
                    </div>
                </div>
            </div>
            <?php $holiday_count=0; ?>
            @foreach ($clients as $client)
            <?php $holidays=($client->holiday_shedule_offered!='no_holiday') ? $client->holidays()->where('year',$current_year)->orderBy('holiday_date')->get() : []; ?>
            @if (count($holidays)>0)
            <?php $holiday_count++; ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">{{$current_year}} - Paid Holidays =>> Client: {{$client->client_name}} </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="alert-modal">
                    <div class="modal">
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        
                                        <div class="row" style="padding: 10px 0px 40px 0px">
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
                                                @foreach($holidays as $holiday)
                                                <tr>
                                                    <td>{{$holiday->holiday_name}}</td>
                                                    <td>{{date('l',strtotime($holiday->holiday_date))}}</td>
                                                    <td>	{{date('m/d/y',strtotime($holiday->holiday_date))}}</td>
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
                </div>
            </div>
            @endif
            @endforeach
            @if (!$holiday_count)
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">No Holiday Schedule</h3>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    
});

</script>

@endsection
