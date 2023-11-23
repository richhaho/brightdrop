@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
input{text-align: center;} 
</style>
@endsection

@section('content')


    <section id="Cash_advance">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Create -  Cash Advance</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif

            <div class="col-md-12 col-lg-12 col-xs-12">
            {!! Form::open(['route' => 'payroll.submitCashAdvance','autocomplete' => 'off']) !!}
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Worker:</label>
                            {!!  Form::select('workers_id',$workers_id,old("workers_id"), ['class' => 'form-control workers_id' ]) !!}
                            <p class="worker_validation" style="color: red;display: none">* Please select worker.</p>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Payment Method:</label>
                            {!!  Form::select('payment_method',$payment_method,old("payment_method"), ['class' => 'form-control payment_method' ]) !!}
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Currency Type:</label>
                            <input name="currency_type" class="form-control currency_type" type="text" readonly value="{{old('currency_type')}}">
                        </div>
                    </div>
                    <div class="row">    
                        <div class="col-md-6 form-group">
                            <label>Description (for Worker paystub):</label>
                            {!!  Form::select('description',$description,old("description"), ['class' => 'form-control changeDescription ','onchange'=>'changeDescription()' ]) !!}
                             
                        </div>
                        <div class="col-md-6 form-group Description"  style="display:none">
                            <label>Other - Description:</label>
                            <input name="other_description" class="form-control other_description" type="text">
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-xs-12">
                        <div class="col-md-4 col-md-offset-4 form-group">
                            <button type="button" class="btn btn-warning pull-right form-control btn-create">Create Table</button>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 open_cash" style="display: none">
                    <div class="panel panel-default open_cash_child">
                        <div class="panel-heading"><center> Advance: <span class="worker_name_opencash">Michel Donahue </span> / <span class="description_opencash">Cash Advance (General)</span></center></div>
                        <div class="panel-body">
                            <div class="row" style="overflow-x: scroll;"> 
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr class="warning">
                                        <th width="15%">Payment Number</th>
                                        <th width="25%">Due Date</th>
                                        <th width="25%">&nbsp;&nbsp;&nbsp;&nbsp;Amount&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th width="25%">Currency Type</th>
                                        <th width="10%"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="add_advance">
                                    <tr>
                                        <td><input name="payment_number[1]" type="text" class="form-control payment_number" value="1" readonly></td>
                                        <td><input name="due_date[1]" type="date" class="form-control due_date" value="" required onchange="calculate_total();"></td>
                                        <td><input name="amount[1]" type="number" min="0" required class="form-control amount" value="" onchange="calculate_total();"></td>
                                        <td><input name="currency[1]" type="text" class="form-control currency" value="" readonly></td>
                                        <td><button type="button" class="btn btn-danger " onclick="delete_list(this)"><i class="fa fa-close"></i></button></td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <button type="button" class="btn btn-warning btn-add"><i class="fa fa-plus"></i> Add Row</button>
                                </div> 
                                <div class="box-body table_group"> 
                                    <div class="col-xs-8">
                                        <div class="col-xs-3">
                                            <label>Total Due:</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <label class="due_amount">0</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <label class="due_currency"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="row"> 
                        <div class="col-md-4 col-md-offset-4 form-group">
                            <button type="submit" class="btn btn-success pull-right form-control btn-submit">Submit</button>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}

            
            </div>

            
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
    var workers=[];
    var workers_currency=[];
    
</script>
@foreach($workers_id as $key => $value)
<script type="text/javascript">
    var key="{{$key}}";
    workers[key]="{{$value}}";
</script>
@endforeach
@foreach($workers_currency as $key => $value)
<script type="text/javascript">
    var key="{{$key}}";
    workers_currency[key]="{{$value}}";
</script>
@endforeach
<script>
$('.workers_id').change(function(){
    $('.currency_type').val(workers_currency[$('.workers_id').val()].toUpperCase());
    $('.worker_validation').css('display','none');
});
$(function () {
    //$('#detail_table').DataTable({"order": [1, 'desc']});
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
});
function changeDescription(){
    if ($('.changeDescription').val()=='Other'){
        $('.Description').css('display','block');
    }else{
        $('.Description').css('display','none');
    }
}
$('.btn-create').click(function(){
    var worker_id=$('.workers_id').val();

    if (!worker_id) {
        $('.worker_validation').css('display','block');return;
    }
    
    var worker_name=workers[worker_id];
    var currency_type=$('.currency_type').val().toUpperCase();
    var description=$('.changeDescription').val();
    if (description=="Other") description=$('.other_description').val();
    $('.worker_name_opencash').text(worker_name);
    $('.description_opencash').text(description);
    $('.currency').val(currency_type);
    $('.due_currency').text(currency_type);
    $('.open_cash').css('display','block');
});

var py_id=1;
$('.btn-add').click(function(){
    py_id++;
    var id=py_id;
    var currency_type=$('.currency_type').val().toUpperCase();
    var tb='<tr>'
            +'<td><input name="payment_number['+id+']" type="text" class="form-control payment_number" value="" readonly></td>'
            +'<td><input name="due_date['+id+']" type="date" class="form-control due_date" onchange="calculate_total();" required></td>'
            +'<td><input name="amount['+id+']" type="number" min="0" required class="form-control amount" onchange="calculate_total();"></td>'
            +'<td><input name="currency['+id+']" type="text" class="form-control currency" value="'+currency_type+'" readonly></td>'
            +'<td><button type="button" class="btn btn-danger " onclick="delete_list(this)"><i class="fa fa-close"></i></button></td>'
            +'</tr>';
    $('.add_advance').append(tb);
    var nm=0;
    $('.add_advance tr').each(function(e){
        nm++;
        $(this).find('.payment_number').val(nm);
    });
    calculate_total()
});
function delete_list(e){
    $(e).parent().parent().remove();
    var nm=0;
    $('.add_advance tr').each(function(e){
        nm++;
        $(this).find('.payment_number').val(nm);
    });
    calculate_total()
}

function calculate_total(){
    var total=0;
    $('.add_advance tr').each(function(e){
        var amount=parseFloat($(this).find('.amount').val()) || 0;
        total+=amount;
    });
    $('.due_amount').text(total);
}
function submited_calculate_total(f){
    var total=0;
    var id=$(f).attr('data');
    $('.table-'+id+' tr').each(function(e){
        var amount=parseFloat($(this).find('.amount').val()) || 0;
        total+=amount;
    });
    $('.total_due-'+id).text(total);
}

function edit_table(f){
    var id=$(f).attr('data');
    $('.table-'+id+' .amount').removeAttr('readonly');
    $('.table-'+id+' .due_date').removeAttr('readonly');

}
</script>

@endsection
