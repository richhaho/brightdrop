@extends('template.template')

@section('content-header')

<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
<style>
.input-sm{width: 100px !important}  
</style>
@endsection

@section('content')

@if ($client)
    <section id="PastInvoice">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <h3 class="bold">Contact - Past Invoices</h3>
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
                                                    <th width="50%">Billing Cycle End Date</th>
                                                    <th width="50%">Link</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($invoices as $invoice)
                                                <tr>
                                                    <td>{{date('m/d/y',strtotime($invoice->billing_cycle_end_date))}}</td>
                                                    <td><a href="{{route('contact.invoices.download',$invoice->id)}}">Download Invoice</a></td>
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
        </div>
    </section>
@else
<h3>You have not been assigned to a client yet.</h3>
@endif
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>

$(function () {
    $('#detail_table').DataTable({"order": [0, 'desc']});
});

</script>

@endsection
