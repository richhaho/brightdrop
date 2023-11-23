<?php

namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Payments;
use App\Invoices;
use App\Clients;


class PayrollManagerController extends Controller
{

    public function updateCommets(Request $request)
    {
        $type=$request->type;
        $pid=$request->pid;
        $comments=$request->comments;
        if($type=='payment'){
        	$payment=Payments::where('id',$pid)->first();
	        $payment->comments=$comments;
        	$payment->save();	
        }else{
        	$invoice=Invoices::where('id',$pid)->first();
	        $invoice->comments=$comments;
        	$invoice->save();	
        }
        
        return 'success';
    }
    
    public function activeWorkers(Request $request)
    {
        $client=Clients::where('id',$request->clients_id)->first();
        if (!$client){
            return response()->json([]);
        }
        return response()->json($client->activeWorkers());
    }
    
    public function allWorkers(Request $request)
    {
        $from = isset($request->from) ? $request->from : null;
        $clients = $request->clients_id == 'all' ? Clients::where('deleted_at',null)->where('status', 'active')->orderBy('client_name', 'ASC')->get() : Clients::where('id',$request->clients_id)->get();

        $workers[] = [
            'id' => 'all',
            'fullname' => 'All'
        ];
        foreach ($clients as $client) {
            foreach ($client->assignedWorkers() as $worker) {
                $fullname = !$from ? $worker->fullname : $worker->fullnameWithDot($client->id, $from);
                $workers[] = [
                    'id' => $worker->id,
                    'fullname' => $fullname
                ];
            }
        }
        return response()->json($workers);
    }
}
