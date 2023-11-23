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
.lunchtime{border:none;margin:0px;background-color:#d9edf7;text-align:center}
.breaktime{border:none;margin:0px;background-color:#dff0d8;text-align:center}
.notes{border:none;margin:0px;background-color:#f2dede;text-align:center}
.questions{border:none;margin:0px;background-color:#fcf8e3;text-align:center}
</style>
@endsection

@section('content')


    <section id="NeedsApproval">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Contact - Needs Approval</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="alert-modal">
                    <div class="modal">
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="modal-dialog">
                                <button class="btn btn-success" style="margin-left: 30px">APPROVE ALL <br> (NO QUESTIONS)</button>

                            </div>
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="heading01">
                                            <div class="panel-title ">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse01" aria-expanded="true" aria-controls="collapse01">
                                                <h4><i class="fa fa-plus-square"></i> Review Hours:  Joe Smith  >>>  Billing Cycle: 07/09/18 - 07/15/18</h4>
                                            </a>
                                            </div>
                                            <div id="collapse01" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading01">    
                                                <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="col-md-6 bold">Worker:</div>
                                                            <div class="col-md-6">Joe Smith</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="col-md-6 bold">Start Date:</div>
                                                            <div class="col-md-6">07/09/18</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="col-md-6 bold">End Date:</div>
                                                            <div class="col-md-6">07/15/18</div>
                                                        </div>
                                                </div>
                                                <div class="row" style="padding: 10px 0px 0px 0px">
                                                    <div class="box-body table_group">
                                                        <table id="detail_table" class="table table-hover text-center table-bordered">
                                                        <thead style="border-bottom:3px lightgrey solid !important" >
                                                        <tr>
                                                            <th rowspan="2" width="10%">Day</th>
                                                            <th rowspan="2" width="10%">Date</th>
                                                            <th colspan="2" width="20%" class="warning">Work Time</th>
                                                            <th colspan="2" width="20%" class="info">Lunch Time</th>
                                                            <th colspan="2" width="20%" class="success">Break Time</th>
                                                            <th rowspan="2" width="10%" class="danger">Worker Notes</th>
                                                            <th rowspan="2" width="10%" class="warning">Client Questions</th>
                                                        </tr>
                                                        <tr style="border-bottom:2px grey solid !important">
                                                            <th width="10%" class="warning">Hours</th>
                                                            <th width="10%" class="warning">Minutes</th>
                                                            <th width="10%" class="info">Hours</th>
                                                            <th width="10%" class="info">Minutes</th>
                                                            <th width="10%" class="success">Hours</th>
                                                            <th width="10%" class="success">Minutes</th>
                                                            
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>Monday</td>
                                                            <td>07/09/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>

                                                        </tr>
                                                        <tr>
                                                            <td>Tuesday</td>
                                                            <td>07/10/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Wednesday</td>
                                                            <td>07/11/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Thursday</td>
                                                            <td>07/12/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Friday</td>
                                                            <td>07/13/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Saturday</td>
                                                            <td>07/14/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sunday</td>
                                                            <td>07/15/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                        <td colspan=10></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan=2>Total Hours >>></td>
                                                            <td colspan=2 class="warning">0.00</td>
                                                            <td colspan=2 class="info">0.00</td>
                                                            <td colspan=2 class="success">0.00</td>
                                                            <td ><button class="btn btn-success" data-toggle="modal" data-target="#modal-contact-submit">Approve Hours</button></td>
                                                            <td ><button class="btn btn-warning">Submit Questions</button></td>
                                                        </tr>
                                                        </tbody>

                                                        </table>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="heading02">
                                        <div class="panel-title ">
                                          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse02" aria-expanded="true" aria-controls="collapse02">
                                              <h4><i class="fa fa-plus-square"></i> Review Hours:  Lee Thompson  >>>  Billing Cycle: 07/09/18 - 07/15/18</h4>
                                          </a>
                                        </div>
                                        <div id="collapse02" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading02">    
                                            <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="col-md-6 bold">Worker:</div>
                                                        <div class="col-md-6">Lee Thompson</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="col-md-6 bold">Start Date:</div>
                                                        <div class="col-md-6">07/09/18</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="col-md-6 bold">End Date:</div>
                                                        <div class="col-md-6">07/15/18</div>
                                                    </div>
                                            </div>
                                            <div class="row" style="padding: 10px 0px 40px 0px">
                                                <div class="box-body table_group">
                                                        <table id="detail_table" class="table table-hover text-center table-bordered">
                                                        <thead style="border-bottom:3px lightgrey solid !important" >
                                                        <tr>
                                                            <th rowspan="2" width="10%">Day</th>
                                                            <th rowspan="2" width="10%">Date</th>
                                                            <th colspan="2" width="20%" class="warning">Work Time</th>
                                                            <th colspan="2" width="20%" class="info">Lunch Time</th>
                                                            <th colspan="2" width="20%" class="success">Break Time</th>
                                                            <th rowspan="2" width="10%" class="danger">Worker Notes</th>
                                                            <th rowspan="2" width="10%" class="warning">Client Questions</th>
                                                        </tr>
                                                        <tr style="border-bottom:2px grey solid !important">
                                                            <th width="10%" class="warning">Hours</th>
                                                            <th width="10%" class="warning">Minutes</th>
                                                            <th width="10%" class="info">Hours</th>
                                                            <th width="10%" class="info">Minutes</th>
                                                            <th width="10%" class="success">Hours</th>
                                                            <th width="10%" class="success">Minutes</th>
                                                            
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>Monday</td>
                                                            <td>07/09/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>

                                                        </tr>
                                                        <tr>
                                                            <td>Tuesday</td>
                                                            <td>07/10/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Wednesday</td>
                                                            <td>07/11/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Thursday</td>
                                                            <td>07/12/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Friday</td>
                                                            <td>07/13/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Saturday</td>
                                                            <td>07/14/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sunday</td>
                                                            <td>07/15/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                        <td colspan=10></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan=2>Total Hours >>></td>
                                                            <td colspan=2 class="warning">0.00</td>
                                                            <td colspan=2 class="info">0.00</td>
                                                            <td colspan=2 class="success">0.00</td>
                                                            <td ><button class="btn btn-success" data-toggle="modal" data-target="#modal-contact-submit">Approve Hours</button></td>
                                                            <td ><button class="btn btn-warning">Submit Questions</button></td>
                                                        </tr>
                                                        </tbody>

                                                        </table>
                                                    </div> 
                                            </div>
                                            
                                        </div>
                                        </div>
                                        </div>

                                        <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="heading03">
                                            <div class="panel-title ">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse03" aria-expanded="true" aria-controls="collapse03">
                                                <h4><i class="fa fa-plus-square"></i>    Review Hours:  Richard Albertson  >>>  Billing Cycle: 07/09/18 - 07/15/18</h4>
                                            </a>
                                            </div>
                                            <div id="collapse03" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading03">    
                                                <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="col-md-6 bold">Worker:</div>
                                                            <div class="col-md-6">Richard Albertson</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="col-md-6 bold">Start Date:</div>
                                                            <div class="col-md-6">07/09/18</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="col-md-6 bold">End Date:</div>
                                                            <div class="col-md-6">07/15/18</div>
                                                        </div>
                                                </div>
                                                <div class="row" style="padding: 10px 0px 40px 0px">
                                                    <div class="box-body table_group">
                                                        <table id="detail_table" class="table table-hover text-center table-bordered">
                                                        <thead style="border-bottom:3px lightgrey solid !important" >
                                                        <tr>
                                                            <th rowspan="2" width="10%">Day</th>
                                                            <th rowspan="2" width="10%">Date</th>
                                                            <th colspan="2" width="20%" class="warning">Work Time</th>
                                                            <th colspan="2" width="20%" class="info">Lunch Time</th>
                                                            <th colspan="2" width="20%" class="success">Break Time</th>
                                                            <th rowspan="2" width="10%" class="danger">Worker Notes</th>
                                                            <th rowspan="2" width="10%" class="warning">Client Questions</th>
                                                        </tr>
                                                        <tr style="border-bottom:2px grey solid !important">
                                                            <th width="10%" class="warning">Hours</th>
                                                            <th width="10%" class="warning">Minutes</th>
                                                            <th width="10%" class="info">Hours</th>
                                                            <th width="10%" class="info">Minutes</th>
                                                            <th width="10%" class="success">Hours</th>
                                                            <th width="10%" class="success">Minutes</th>
                                                            
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>Monday</td>
                                                            <td>07/09/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>

                                                        </tr>
                                                        <tr>
                                                            <td>Tuesday</td>
                                                            <td>07/10/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Wednesday</td>
                                                            <td>07/11/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Thursday</td>
                                                            <td>07/12/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Friday</td>
                                                            <td>07/13/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Saturday</td>
                                                            <td>07/14/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sunday</td>
                                                            <td>07/15/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                        <td colspan=10></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan=2>Total Hours >>></td>
                                                            <td colspan=2 class="warning">0.00</td>
                                                            <td colspan=2 class="info">0.00</td>
                                                            <td colspan=2 class="success">0.00</td>
                                                            <td ><button class="btn btn-success" data-toggle="modal" data-target="#modal-contact-submit">Approve Hours</button></td>
                                                            <td ><button class="btn btn-warning">Submit Questions</button></td>
                                                        </tr>
                                                        </tbody>

                                                        </table>
                                                    </div> 
                                                </div>
                                                
                                            </div>
                                        </div>
                                        </div>
                                        <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="heading04">
                                        <div class="panel-title ">
                                          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse04" aria-expanded="true" aria-controls="collapse04">
                                              <h4><i class="fa fa-plus-square"></i>  Review Hours:  David Walker  >>>  Billing Cycle: 07/09/18 - 07/22/18</h4>
                                          </a>
                                        </div>
                                        <div id="collapse04" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading04">    
                                            <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="col-md-6 bold">Worker:</div>
                                                        <div class="col-md-6">David Walker</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="col-md-6 bold">Start Date:</div>
                                                        <div class="col-md-6">07/09/18</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="col-md-6 bold">End Date:</div>
                                                        <div class="col-md-6">07/15/18</div>
                                                    </div>
                                            </div>
                                            <div class="row" style="padding: 10px 0px 40px 0px">
                                                <div class="box-body table_group">
                                                        <table id="detail_table" class="table table-hover text-center table-bordered">
                                                        <thead style="border-bottom:3px lightgrey solid !important" >
                                                        <tr>
                                                            <th rowspan="2" width="10%">Day</th>
                                                            <th rowspan="2" width="10%">Date</th>
                                                            <th colspan="2" width="20%" class="warning">Work Time</th>
                                                            <th colspan="2" width="20%" class="info">Lunch Time</th>
                                                            <th colspan="2" width="20%" class="success">Break Time</th>
                                                            <th rowspan="2" width="10%" class="danger">Worker Notes</th>
                                                            <th rowspan="2" width="10%" class="warning">Client Questions</th>
                                                        </tr>
                                                        <tr style="border-bottom:2px grey solid !important">
                                                            <th width="10%" class="warning">Hours</th>
                                                            <th width="10%" class="warning">Minutes</th>
                                                            <th width="10%" class="info">Hours</th>
                                                            <th width="10%" class="info">Minutes</th>
                                                            <th width="10%" class="success">Hours</th>
                                                            <th width="10%" class="success">Minutes</th>
                                                            
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>Monday</td>
                                                            <td>07/09/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>

                                                        </tr>
                                                        <tr>
                                                            <td>Tuesday</td>
                                                            <td>07/10/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Wednesday</td>
                                                            <td>07/11/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Thursday</td>
                                                            <td>07/12/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Friday</td>
                                                            <td>07/13/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Saturday</td>
                                                            <td>07/14/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sunday</td>
                                                            <td>07/15/18</td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="warning"><input type="text" class="form-control worktime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="info"><input type="text" class="form-control lunchtime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="success"><input type="text" class="form-control breaktime"></td>
                                                            <td class="danger"><input type="text" class="form-control notes"></td>
                                                            <td class="warning"><input type="text" class="form-control questions"></td>
                                                        </tr>
                                                        <tr>
                                                        <td colspan=10></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan=2>Total Hours >>></td>
                                                            <td colspan=2 class="warning">0.00</td>
                                                            <td colspan=2 class="info">0.00</td>
                                                            <td colspan=2 class="success">0.00</td>
                                                            <td ><button class="btn btn-success" data-toggle="modal" data-target="#modal-contact-submit">Approve Hours</button></td>
                                                            <td ><button class="btn btn-warning">Submit Questions</button></td>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<div class="modal fade" id="modal-contact-submit" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Approve Hours</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to approve hours?</p>
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>&nbsp;&nbsp;
            <button class="btn btn-danger" type="submit"><i calss="fa fa-times"></i> OK</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-contact-decline" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Decline Hours</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to decline hours?</p>
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>&nbsp;&nbsp;
            <button class="btn btn-danger" type="submit"><i calss="fa fa-times"></i> OK</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
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
});

</script>

@endsection
