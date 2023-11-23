@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important}   
</style>
@endsection

@section('content')


    <section id="CreateAdjustment">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Active Adjustments - Recurring</h3>
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
                    <div class="panel panel-default open_cash_child">
                        <div class="panel-heading"><center>Active Adjustments - Recurring</center></div>
                        <div class="panel-body col-xs-12">
                            <a class="btn btn-default pull-right btn-xs" data-toggle="modal" data-target="#modal-view-columns-recurring">View Columns</a>
                                <div class="modal fade" id="modal-view-columns-recurring" tabindex="-1" role="dialog">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Set Columns</h4>
                                      </div>
                                      <div class="modal-body row">
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring1" checked><span> Reference Number</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring2" checked><span> Date Submitted</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring12" checked><span> Internal Notes</span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring3" checked><span> Pay To</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring4" checked><span> Pay To Client</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring5" checked><span> Pay To Worker</span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring6" checked><span> Bill To</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring7" checked><span> Bill To Client</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring8" checked><span> Bill To Worker</span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring9" checked><span> Description</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring10" checked><span> Amount</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="checkbox" name="" class="check_column_recurring11" checked><span> Currency Type</span>
                                                </div>
                                            </div>
                                             

                                      </div>
                                      <div class="modal-footer">
                                            <button class="btn btn-success btn-column-view-recurring" type="button"  data-dismiss="modal">View</button>
                                            <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                        </div>
                        <div class="panel-body" style="overflow-x:scroll">
                            <table id="recurring_table" class="table table-hover text-center table-bordered">
                            <thead>
                            <tr class="">
                                <th class="column_recurring1">Reference Number</th>
                                <th class="column_recurring2">Date Submitted</th>
                                <th class="column_recurring3">Pay To</th>
                                <th class="column_recurring4">Pay To - Client</th>
                                <th class="column_recurring5">Pay To - Worker</th>
                                <th class="column_recurring6">Bill To</th>
                                <th class="column_recurring7">Bill To - Client</th>
                                <th class="column_recurring8">Bill To - Worker</th>
                                <th class="column_recurring9">Description</th>
                                <th class="column_recurring10">Amount</th>
                                <th class="column_recurring11">Currency Type</th>
                                <th class="column_recurring12">Internal Notes</th>
                                <th>&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody class="add_Reference"> 
                            @foreach ($recurring_adjustments as $adjustment)    
                            <tr>
                                <td class="column_recurring1">{{$adjustment->id}}</td>
                                <td class="column_recurring2">{{date('m/d/y',strtotime($adjustment->date_submitted))}}</td>
                                <td class="column_recurring3">{{$adjustment->payto}}</td>
                                <td class="column_recurring4">
                                    @if($adjustment->paytoclient && isset($paytoclient[$adjustment->paytoclient]))
                                    {{$paytoclient[$adjustment->paytoclient]}}
                                    @endif
                                </td>
                                <td class="column_recurring5">
                                    @if($adjustment->paytoworker && isset($paytoworker[$adjustment->paytoworker]))
                                    {{$paytoworker[$adjustment->paytoworker]}}
                                    @endif
                                </td>
                                <td class="column_recurring6">{{$adjustment->billto}}</td>
                                <td class="column_recurring7">
                                    @if($adjustment->billtoclient && isset($billtoclient[$adjustment->billtoclient]))
                                    {{$billtoclient[$adjustment->billtoclient]}}
                                    @endif
                                </td>
                                <td class="column_recurring8">
                                    @if($adjustment->billtoworker && isset($billtoworker[$adjustment->billtoworker]))
                                    {{$billtoworker[$adjustment->billtoworker]}}
                                    @endif
                                </td>
                                <td class="column_recurring9">{{$adjustment->description}}</td>
                                <td class="column_recurring10">{{$adjustment->amount}}</td>
                                <td class="column_recurring11">{{$adjustment->currency_type}}</td>
                                <td class="column_recurring12">{{$adjustment->internal_notes}}</td>
                                <td>
                                    <a href="#" class="btn btn-success btn-xs"><i class="fa fa-edit" data-toggle="modal" data-target="#modal-edit-{{$adjustment->id}}"></i></a>
                                    @component('payroll.adjustment.components.editmodal')
                                    @slot('id') 
                                        {{ $adjustment->id }}
                                    @endslot
                                    @slot('amount') 
                                        {{ $adjustment->amount }}
                                    @endslot
                                    @slot('description') 
                                        {{ $adjustment->description }}
                                    @endslot
                                    @slot('currency_type') 
                                        {{ $adjustment->currency_type }}
                                    @endslot
                                    @slot('internal_notes') 
                                        {{ $adjustment->internal_notes }}
                                    @endslot
                                    @slot('from') 
                                        {{ 'active' }}
                                    @endslot  
                                    @endcomponent
                                             
                                    <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-close"  data-toggle="modal" data-target="#modal-delete-{{$adjustment->id}}"></i></a>
                                     
                                    @component('payroll.adjustment.components.deletemodal')
                                    @slot('id') 
                                        {{ $adjustment->id }}
                                    @endslot
                                    @slot('to')
                                        {{ 'remove_recurring' }}
                                    @endslot
                                    @slot('from')
                                        {{ 'active' }}
                                    @endslot
                                    @endcomponent
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                            </table>
                               
                        </div>
                    </div>
                     
                </div>
                
            </div>
        </div>
    </section>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $('#recurring_table').DataTable({"order": [1, 'desc']});
    $('#onetime_table').DataTable({"order": [1, 'desc']});
    $(".message-box").fadeTo(6000, 500).slideUp(500, function(){
        $(".message-box").slideUp(500).remove();
    });
});
function changePayTO(){
    
    $('.PayTO2').css('display','none');
    $('.PayTO3').css('display','none');
    if ($('.changePayTO').val()=='Client'){
        $('.PayTO2').css('display','block');
    }
    if ($('.changePayTO').val()=='Worker'){
        $('.PayTO3').css('display','block');
    }
}
function changeBillTO(){
    
    $('.BillTO2').css('display','none');
    $('.BillTO3').css('display','none');
    if ($('.changeBillTO').val()=='Client'){
        $('.BillTO2').css('display','block');
    }
    if ($('.changeBillTO').val()=='Worker'){
        $('.BillTO3').css('display','block');
    }
}
function changeTYPE(){
    
    $('.TYPE1').css('display','none');
    $('.TYPE2').css('display','none');
    if ($('.changeTYPE').val()=='Time Adjustment'){
        $('.TYPE1').css('display','block');
    }
    if ($('.changeTYPE').val()=='Other'){
        $('.TYPE2').css('display','block');
    }
}

function changeRate(){
    
    
    if ($('.changeRate').val()=='Percent-Other'){
        $('.Rate').css('display','block');
    }else{
        $('.Rate').css('display','none');
    }
     
}

$('.btn-column-view-recurring').click(function(){
    for (i=1;i<18;i++){
        if ($('.check_column_recurring'+i).is(':checked')){
            $('.column_recurring'+i).show();
        }else{
            $('.column_recurring'+i).hide();
        }
    }
});
</script>

@endsection