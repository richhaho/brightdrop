@section('css')

<style type="text/css">
   #page  h1 {
        display: block;
        font-size: 1.5em;
        margin-top: 0px;
        margin-bottom: 0px;
        font-weight: bold;
    }
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
}
</style>

@append

<div id="page">
    <div class="content">
        
        <h1 class="text-center">BrightDrop Virtual Assistants, LLC</h1>
        <p>&nbsp;</p>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td width="70%">
                        <p class="bold">Issuer:</p>
                        <p>{{$BD->company_name}}</p>
                        <p>{{$BD->address}}</p>
                        <p>{{$BD->city}}, {{$BD->state}} {{$BD->zip}}, USA</p>
                        <p>&nbsp;</p>
                        <p>Phone: {{$BD->phone}}</p>
                        <p>Email: {{$BD->email}}</p>
                    </td>
                    <td width="30%">
                        <p ></p>
                        <table style="width: 100%" class="table text-center table-bordered">
                            <thead class="active">
                                <tr>
                                    <th class="active bold"  style="background-color: #c0c0c0 !important;text-align: center;">DATE</th>
                                    <th class="active bold"  style="background-color: #c0c0c0 !important;text-align: center;">INVOICE #</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$invoiced_date}}</td>
                                    <td>{{$invoice_number}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="background-color: #c0c0c0 !important;text-align: center;">BILLING CYCLE</td>
                                </tr>
                                <tr>
                                    <td>{{$start_date}}</td>
                                    <td>{{$end_date}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <p>&nbsp;</p>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td width="50%">
                        <table style="width: 100%"  class="table table-bordered">
                            <thead class="active">
                                <tr>
                                    <th class="active bold" style="background-color: #c0c0c0 !important;">BILL TO:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$client->client_name}}</td>
                                </tr>
                                @if($client->country=='Other')
                                <tr>
                                    <td>{{$client->address_foreign}}</td>
                                </tr>
                                <tr>
                                    <td>{{$client->country_other}}</td>
                                </tr>
                                @else
                                <tr>
                                    <td>{{$client->address1}} {{$client->address2}}</td>
                                </tr>
                                <tr>
                                    <td>{{$client->city}}, {{$client->state}} {{$client->zip}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </td>
                    <td width="20%"></td>
                    <td width="30%">
                        <p>&nbsp;</p><p>&nbsp;</p>
                        <table style="width: 100%" class="table text-center table-bordered">
                            <thead>
                                <tr>
                                    <th class="active bold"  style="background-color: #c0c0c0 !important;text-align: center;">AMOUNT DUE</th>
                                    <th class="active bold"  style="background-color: #c0c0c0 !important;text-align: center;">ENCLOSED</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> $ {{$total_amount}}</td>
                                    <td> </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>


        <table style="width: 100%" class="table text-center table-bordered">
            <thead>
                <tr class="active bold text-center " >
                    <th width="15%"  style="background-color: #c0c0c0 !important;text-align: center;">Service ID</th>
                    <th width="40%"  style="background-color: #c0c0c0 !important;text-align: center;">Description:</th>
                    <th width="15%"  style="background-color: #c0c0c0 !important;text-align: center;">Quantity/Hours</th>
                    <th width="15%"  style="background-color: #c0c0c0 !important;text-align: center;">Rate</th>
                    <th width="15%"  style="background-color: #c0c0c0 !important;text-align: center;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lines as $line)
                <tr>
                    <td>{{$line['service_id']}}</td>
                    <td>{{$line['description']}}</td>
                    <td>{{number_format($line['quantity_hours'], 2, '.', ',')}}</td>
                    <td>{{number_format($line['rate'], 2, '.', ',')}}</td>
                    <td>{{number_format($line['amount'], 2, '.', ',')}}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5"></td>
                </tr>
                @if ($client->ACH_discount_participation=='yes')
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" style="background-color: #c0c0c0 !important;">SUB - TOTAL</td>
                    <td>$ {{$sub_total_amount}}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" style="background-color: #c0c0c0 !important;">ACH Discount (2.5%)</td>
                    <td>$ ({{$ACH_discount}})</td>
                </tr>
                @endif
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" style="background-color: #c0c0c0 !important;"> TOTAL</td>
                    <td>$ {{$total_amount}}</td>
                </tr>
            </tbody>
        </table>&nbsp;


        
     
     
    </div>
</div>