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
        
        <h1 class="text-center">Cash Advance Repayment Table</h1>
        <p>&nbsp;</p>
        <table style="width: 50%">
            <tbody>
                <tr>
                    <td class="bold">Worker:</td><td>{{$cash_advance->worker()->fullname}}</td>
                </tr>
                <tr>
                    <td class="bold">Description:</td><td>{{$cash_advance->description}}</td>
                </tr>
                <tr>
                    <td class="bold">Total Paid:</td><td>{{number_format($cash_advance->total_paid, 2, '.', ',')}} {{strtoupper($cash_advance->currency_type)}}</td>
                </tr>
                <tr>
                    <td class="bold">Total Due:</td><td>{{number_format($cash_advance->total_due, 2, '.', ',')}} {{strtoupper($cash_advance->currency_type)}}</td>
                </tr>

            </tbody>
        </table>
        <br>
        <div class="box-body table_group">
            <table id="detail_table" class="table table-hover text-center table-bordered">
            <thead style="border-bottom:3px lightgrey solid !important" >
            <tr class="warning">
                <th width="15%" style="background-color: #c0c0c0 !important;text-align: center;">Payment Number</th>
                <th width="20%" style="background-color: #c0c0c0 !important;text-align: center;">Due Date</th>
                <th width="25%" style="background-color: #c0c0c0 !important;text-align: center;">Amount</th>
                <th width="15%" style="background-color: #c0c0c0 !important;text-align: center;">Currency Type</th>
                <th width="15%" style="background-color: #c0c0c0 !important;text-align: center;">Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach(unserialize($cash_advance->open_cash_advances) as $row)
            <tr>
                <td>{{$row['payment_number']}}</td>
                <td>{{$row['due_date']}}</td>
                <td>{{number_format($row['amount'], 2, '.', ',')}}</td>
                <td>{{$row['currency']}}</td>
                <td>
                    @if(isset($row['payments_id']))
                    @if($row['payments_id']>0 && $row['status']=='none')
                    Approved
                    @endif

                    @if($row['payments_id']>0 && $row['status']=='repaid')
                    Repaid
                    @endif
                    @endif
                </td>
            </tr>
            @endforeach            
            </tbody>

            </table>
        </div>
    </div>
</div>