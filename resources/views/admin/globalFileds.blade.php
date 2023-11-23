@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
 
</style>
@endsection

@section('content')

{!! Form::open(['route' => 'admin.updateGlobalFileds','autocomplete' => 'off']) !!}
    <section id="Global">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12" >
                        <h3 class="bold">Global Fields</h3>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
                        <button type="submit" class="btn btn-success pull right"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Foreign Exchange Rate</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>PHP per USD:</label>
                                    <input name="php_usd" class="form-control " type="text" value="{{$global->php_usd}}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>MXN per USD:</label>
                                    <input name="mxn_usd" class="form-control " type="text" value="{{$global->mxn_usd}}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Brightdrop Company Information</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Company Name:</label>
                                    <input name="company_name" class="form-control " type="text" value="{{$global->company_name}}" required>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Street Address:</label>
                                    <input name="address" class="form-control " type="text" value="{{$global->address}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>City:</label>
                                    <input name="city" class="form-control " type="text" value="{{$global->city}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>State:</label>
                                    <input name="state" class="form-control " type="text" value="{{$global->state}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Zip Code:</label>
                                    <input name="zip" class="form-control " type="text" value="{{$global->zip}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Phone Number:</label>
                                    <input name="phone" class="form-control " type="text" value="{{$global->phone}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Email:</label>
                                    <input name="email" class="form-control " type="email" value="{{$global->email}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Billing Cycle End Date:</label>
                                    <input name="billing_cycle_end_date" class="form-control " type="date" value="{{$global->billing_cycle_end_date}}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Other Values Setting</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="50%">Field Name</th>
                                        <th width="40%">Value</th>
                                        <!-- <th width="10%"></th> -->
                                    </tr>
                                    </thead>
                                    <tbody class="other_values">
                                        @if (unserialize($global->values))
                                        @foreach (unserialize($global->values) as $other) 
                                        <tr>
                                            <td><input type="text" name="otherfield[{{$other['id']}}]" class="form-control" required value="{{$other['fieldname']}}" readonly></td>
                                            <td><input type="text" name="setvalue[{{$other['id']}}]" class="form-control" required value="{{$other['value']}}" ></td>
                                            <!-- <td><button type="button" class="btn btn-danger" onclick="fields_delete_list(this)"><i class="fa fa-close"></i></button></td></td> -->
                                        </tr>
                                        @endforeach 
                                        @endif
                                    </tbody>
                                    </table>
                                </div>     
                            </div>
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <button type="button" class="btn btn-success pull-right btn-add-field"><i class="fa fa-plus"></i> Add Field</button>
                            </div> 
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Run Cron Job Manually.</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="box-body table_group">
                                    <ul>
                                        <li>Run Cron-job "TimeCardEnded"</li>
                                        <li>Run Cron-job "ResetBillingCycleEndDate"</li>
                                        <li>Run Cron-job "NewTimesheet"</li>
                                    </ul>
                                </div>     
                            </div>
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <a href="{{route('admin.cron.run')}}" class="btn btn-success pull-right"><i class="fa fa-play"></i> RUN</a>
                            </div> 
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </section>
{!! Form::close() !!}
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    //$('#detail_table').DataTable({"order": [1, 'desc']});
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
});

function fields_delete_list(e){
    $(e).parent().parent().remove();
}

var field_id=100;
$('.btn-add-field').click(function(){
    field_id++;
    var add='<tr>'
                +'<td><input type="text" name="otherfield['+field_id+']" class="form-control" required></td>'
                +'<td><input type="text" name="setvalue['+field_id+']" class="form-control" required></td>'
                // +'<td><button type="button" class="btn btn-danger" onclick="fields_delete_list(this)"><i class="fa fa-close"></i></button></td></td>' 
            +'</tr>';
    $('.other_values').append(add);
});



</script>

@endsection
