<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Clients;
use App\Workers;
use App\Reimbursement;
use App\Globals;
use App\PaymentLines;
use App\Payments;
use PDF;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use Response;

use Auth;
use Storage;
use Carbon\Carbon;

class ReimbursementController extends Controller
{
    public function ReimbursementNeedsApproval()
    {
        $clients=Clients::where('deleted_at',null)->get();
        
        $reimbursementss=Reimbursement:: where('status', 'Approved')->where('payroll_managers_id','!=' ,null)->get();
        $approve_decline=[
            'Approved'=>'Approved',
            'Declined'=>'Declined',
        ];
        $bill_to=[
            'BrightDrop'=>'BrightDrop',
            'Client'=>'Client',
        ];
        $payment_method=[
            'Veem'=>'Veem',
            'Western Union'=>'Western Union',
        ];

        $data=[
            'clients'=>$clients,
            'bill_to'=>$bill_to,
            'payment_method'=>$payment_method,
            'approve_decline'=>$approve_decline,
            'reimbursementss'=>$reimbursementss,
             
        ];

        return view('payroll.needsApproval.workerReimbursement',$data);
    }

    public function submit_reimbursementApproval(Request $request)
    {
        $clients_id=$request->client_id;
        $workers_id=$request->worker_id;
        $payrollmanager=Auth::user()->payrollmanager();
         
        $approve=$request->approve_decline;
        foreach ($request['reimbursement'] as $key => $value) {
            $reimbursement=Reimbursement::where('id',$key)->first();
            if ($approve=='Approved'){
                $reimbursement->bill_to=$request->bill_to;
                $reimbursement->payment_method=$request->payment_method;
            }
            $reimbursement->status=$approve;
            $reimbursement->payroll_managers_id=$payrollmanager->id;
            $reimbursement->handle_date=date('Y-m-d',strtotime(Carbon::now()));
            $reimbursement->save();
            $this->movetoQueue_immediatePay($reimbursement);
        }
        
        Session::flash('message', $approve.' by Payroll Manager.');
        return redirect()->route('payroll.needsApproval.ReimbursementNeedsApproval');
    }


    public function movetoQueue_immediatePay($reimbursement){
        $BD=Globals::first();
        $payment=Payments::where('id',$reimbursement->payments_id)->first();
        if (count($payment)==0) $payment=Payments::create();
        $reimbursement->payments_id=$payment->id;
        $reimbursement->save();
        $payment->workers_id=$reimbursement->workers_id;
        $payment->clients_id=$reimbursement->clients_id;
        
        $payment->payrolls_id=Auth::user()->payrollmanager()->id;
        $payment->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        $payment->amount=$reimbursement->amount;
        $payment->currency_type=$reimbursement->currency_type;

        $payment->payment_method=$reimbursement->payment_method;
        $payment->payment_type='immediate';
        $payment->status='Pending';
        $file='payment_summary/immediate/'.$payment->id.'.pdf';
        $payment->payment_summary_report_file=$file;
        $payment->save();
        $paymentlines=$payment->lines();
        foreach ($paymentlines as $paymentline) {
            $paymentline->delete();
        }
        $paymentline=PaymentLines::create();
        $paymentline->payments_id=$payment->id;
        $paymentline->service_id='0205';
        $description=($reimbursement->type=='Other' ? $reimbursement->other_type:$reimbursement->type);
        $paymentline->description='Worker Reimbursement: '.$description;
        $paymentline->quantity_hours=1;
        $paymentline->rate=$reimbursement->amount;
        $paymentline->amount=$reimbursement->amount;
        $paymentline->save();

        $now_date=date('D, m/d/y',strtotime(Carbon::now()));
        $worker=$reimbursement->worker();
            $data['document']='payment_summary_immediate';
            $data['BD']=$BD;
            $data['now_date']=$now_date;
            $data['worker']=$worker;
                $lines=array();
                $line['service_id']=$paymentline->service_id;
                $line['description']='Worker Reimbursement: '.$description;
                $line['quantity_hours']=1;
                $line['rate']=$reimbursement->amount;
                $line['amount']=$reimbursement->amount;
                $lines[]=$line;
            $data['lines']=$lines;

            $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
            $summary_report=$pdf->output();
            Storage::put($file,$summary_report);
             
    }


}
