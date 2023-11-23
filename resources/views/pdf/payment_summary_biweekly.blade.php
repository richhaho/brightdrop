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
        
        <h1 class="text-center">BrightDrop Virtual Assistants - Payment Summary</h1>
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
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                    </td>
                    <td width="70%">
                        <table style="width: 100%" class="table text-center table-bordered">
                            <tbody class="active">
                                <tr>
                                    <td class="active bold"  style="background-color: #c0c0c0 !important;">Payment Period - Start Date</td>
                                </tr>
                                <tr>
                                    <td>{{$start_date}}</td>
                                </tr>
                                <tr>
                                    <td class="active bold"  style="background-color: #c0c0c0 !important;">Payment Period - End Date</td>
                                </tr>
                                <tr>
                                    <td>{{$end_date}}</td>
                                </tr>
                                <tr>
                                    <td class="active bold"  style="background-color: #c0c0c0 !important;">Client Name</td>
                                </tr>
                                <tr>
                                    <td>{{$client->client_name}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <p>&nbsp;</p>
      
        <table style="width: 50%;margin-top: -150px"  class="table table-bordered">
            <thead class="active">
                <tr>
                    <th class="active bold" style="background-color: #c0c0c0 !important;">PAYMENT TO:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$worker->fullname}}</td>
                </tr>
                <tr>
                    <td>{{$worker->address}}</td>
                </tr>
                <tr>
                    <td>  {{$worker->country=='Philippines' ? $worker->philippines_region:''}}</td>
                </tr>
                <tr>
                    <td>{{$worker->country}}</td>
                </tr>
            </tbody>
        </table>&nbsp;

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
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" style="background-color: #c0c0c0 !important;">Total</td>
                    <td>{{number_format($total_amount, 2, '.', ',')}}</td>
                </tr>
            </tbody>
        </table>&nbsp;


        
     
     
    </div>
</div>