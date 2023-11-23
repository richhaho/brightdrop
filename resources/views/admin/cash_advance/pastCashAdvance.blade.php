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
                        <h3 class="bold">Past -  Cash Advances</h3>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="col-md-12 col-lg-12 col-xs-12 message-box">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            @endif

            <div class="col-md-12 col-lg-12 col-xs-12">

            @foreach ($cash_advances as $cash_advance)
            {!! Form::open(['route' => 'admin.updateCashAdvance','autocomplete' => 'off']) !!}
                <input type="hidden" name="id" value="{{$cash_advance->id}}">
                <div class="col-xs-12 submited_advance">
                    <div class="panel panel-default open_cash_child">
                        <div class="panel-heading"><center>Open Cash Advance: {{$workers_id[$cash_advance->workers_id]}} / {{$cash_advance->description}}</center></div>
                        <div class="panel-body">
                            <div class="row" style="overflow-x: scroll;">
                                <div class="box-body table_group">
                                     
                                    <a href="{{route('admin.removeCashAdvance',$cash_advance->id)}}" class="btn btn-danger pull-right btn-xs"><i class="fa fa-close"> Delete</i></a>
                                </div>
                                <div class="box-body table_group">
                                    <table id="detail_table" class="table table-hover text-center table-bordered">
                                    <thead>
                                    <tr class="warning">
                                        <th width="15%">Payment Number</th>
                                        <th width="20%">Due Date</th>
                                        <th width="25%">&nbsp;&nbsp;&nbsp;&nbsp;Amount&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th width="15%">Currency Type</th>
                                        <th width="15%">Payment Summary</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-{{$cash_advance->id}}">
                                    @foreach(unserialize($cash_advance->open_cash_advances) as $row)
                                    <tr>
                                        <td><input name="payment_number[{{$row['payment_number']}}]" type="text" class="form-control payment_number" value="{{$row['payment_number']}}" readonly></td>
                                        <td><input name="due_date[{{$row['payment_number']}}]" type="date" class="form-control due_date" value="{{$row['due_date']}}" required readonly onchange="submited_calculate_total(this);" data="{{$cash_advance->id}}"></td>
                                        <td><input name="amount[{{$row['payment_number']}}]" type="number" min="0" required readonly class="form-control amount" value="{{$row['amount']}}"  onchange="submited_calculate_total(this);" data="{{$cash_advance->id}}"></td>
                                        <td><input name="currency[{{$row['payment_number']}}]" type="text" class="form-control currency" value="{{strtoupper($row['currency'])}}" readonly></td>
                                        <td>    
                                            @if(isset($row['payments_id']))
                                            @if($row['payments_id']>0)
                                            <a href="{{route('admin.payroll.viewReport',$row['payments_id'])}}" target="_blank">View Report</a>
                                            @endif
                                            @endif
                                        </td> 
                                    </tr>
                                    <?php $currency=strtoupper($row['currency']);?>
                                    @endforeach
                                    
                                    </tbody>
                                    </table>
                                     
                                </div>
                                <div class="box-body table_group"> 
                                    <div class="col-lg-8 col-md-12 col-xs-12">
                                        <div class="col-xs-4 col-md-3">
                                            <label>Total Paid:</label>
                                        </div>
                                        <div class="col-xs-5 col-md-3">
                                            <label class="total_paid"><input type="text" name="total_paid" class="form-control" style="margin-top: -5px"  onchange="submited_calculate_total(this);" data="{{$cash_advance->id}}" value="{{$cash_advance->total_paid}}" readonly></label>
                                        </div>
                                        <div class="col-xs-3 col-md-3">
                                            <label>{{$currency}}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-xs-12">
                                        <div class="col-xs-4 col-md-3">
                                            <label>Total Due:</label>
                                        </div>
                                        <div class="col-xs-5 col-md-3">
                                            <center><label class="total_due-{{$cash_advance->id}}">{{$cash_advance->total_due}}</label></center>
                                        </div>
                                        <div class="col-xs-3 col-md-3">
                                            <label>{{$currency}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!} 
            @endforeach   
            </div>

            
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
    var workers=[];
    
</script>
@foreach($workers_id as $key => $value)
<script type="text/javascript">
    var key="{{$key}}";
    workers[key]="{{$value}}";
</script>
@endforeach
<script>

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
