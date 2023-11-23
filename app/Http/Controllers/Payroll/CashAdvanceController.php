<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Clients;
use App\Workers;
use App\CashAdvances;
use App\PaymentLines;
use App\Payments;
use App\Globals;
use Auth;
use Storage;
use Carbon\Carbon;
use PDF;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use Response;

use Mail;
use App\Mail\CashAdvanceStatusWorker;

class CashAdvanceController extends Controller
{
    public function createCashAdvance()
    {
        $payrollmanager=Auth::user()->payrollmanager();
        
        $workers_id=Workers::where('deleted_at',null)->get()->pluck('fullname','id')->prepend('','');
        
        $payment_method=[
            'Veem'=>'Veem',
            'Western Union'=>'Western Union',
        ];
        $description=[
            'Cash Advance (General)'=>'Cash Advance (General)',
            'Computer Loan'=>'Computer Loan',
            'Early Payment'=>'Early Payment',
            'Medical Loan'=>'Medical Loan',
            'Other'=>'Other',
        ];
        $currency_type=[
            ''=>'',
            'php'=>'PHP',
            'mxn'=>'MXN',
            'usd'=>'USD',
        ];
        $workers_currency=Workers::where('deleted_at',null)->pluck('currency_type','id')->prepend('','');
        $data=[
            'workers_id'=>$workers_id,
            'workers_currency'=>$workers_currency,
            'payment_method'=>$payment_method,
            'description'=>$description,
            'currency_type'=>$currency_type,
            
        ];

        return view('payroll.cash_advance.createCashAdvance',$data);
    }
    public function openCashAdvance()
    {
        $cash_advances=CashAdvances::where('deleted_at',null)->where('status','!=','repaid')->get();
        $workers_id=Workers::where('deleted_at',null)->get()->pluck('fullname','id');
        $data=[
            'workers_id'=>$workers_id, 
            'cash_advances'=>$cash_advances,
        ];

        return view('payroll.cash_advance.openCashAdvance',$data);
    }
    public function pastCashAdvance()
    {
         
        
        $workers_id=Workers::where('deleted_at',null)->get()->pluck('fullname','id');
        
        
        $cash_advances=CashAdvances::where('deleted_at',null)->where('status', 'repaid')->get();

        $data=[
            'workers_id'=>$workers_id,
             
            'cash_advances'=>$cash_advances,
        ];

        return view('payroll.cash_advance.pastCashAdvance',$data);
    }



    public function submitCashAdvance(Request $request)
    {
        $data=$request->all();
        $cashAdvance=CashAdvances::create();
        $cashAdvance->workers_id=$data['workers_id'];
        $cashAdvance->payroll_managers_id=Auth::user()->payrollmanager()->id;
        $cashAdvance->payment_method=$data['payment_method'];
        $cashAdvance->description=$data['description'];
        $cashAdvance->other_description=$data['other_description'];
        $cashAdvance->status="Pending";
        $cashAdvance->currency_type=strtolower($data['currency_type']);
        $cashAdvance->save();

        if(isset($data['payment_number'])){
            $open_cash_advances=array();
            $pm_id=0; $total_due=0; 
            foreach ($data['payment_number'] as $key => $value) {
                $pm_id++;
                $open_cash_advance['payment_number']=$pm_id;
                $open_cash_advance['due_date']=$request->due_date[$key];
                $open_cash_advance['amount']=$request->amount[$key];
                $open_cash_advance['currency']=strtolower($request->currency[$key]);
                $open_cash_advance['status']='none';
                $open_cash_advance['payments_id']=0;
                $open_cash_advances[]=$open_cash_advance;
                $total_due+=$request->amount[$key];
            }
            $cash_advances=serialize($open_cash_advances);
            $cashAdvance->open_cash_advances=$cash_advances;
            $cashAdvance->total_due=$total_due;
        }
        $cashAdvance->save();
        $this->movetoQueue_immediatePay($cashAdvance);
        $this->cashAdvance_status($cashAdvance,'created');
        Session::flash('message', 'Submitted Successfully.');
        return redirect()->route('payroll.openCashAdvance');
    }

    public function updateCashAdvance(Request $request)
    {
        $data=$request->all();
        $cashAdvance=CashAdvances::where('id',$data['id'])->first();
        $cashAdvance->status="Pending";
        if(isset($data['payment_number'])){
            $open_cash_advances=array();
            $pm_id=0; $total_due=0; 
            foreach ($data['payment_number'] as $key => $value) {
                $pm_id++;
                $open_cash_advance['payment_number']=$pm_id;
                $open_cash_advance['due_date']=$request->due_date[$key];
                $open_cash_advance['amount']=$request->amount[$key];
                $open_cash_advance['currency']=strtolower($request->currency[$key]);
                $open_cash_advance['status']='none';
                $open_cash_advance['payments_id']=0;
                $open_cash_advances[]=$open_cash_advance;
                $total_due+=$request->amount[$key];
            }
            $cash_advances=serialize($open_cash_advances);
            $cashAdvance->open_cash_advances=$cash_advances;
            $cashAdvance->total_due=$total_due;
        }
        $cashAdvance->total_paid=$data['total_paid'];
        $cashAdvance->save();
        $this->movetoQueue_immediatePay($cashAdvance);
        $this->cashAdvance_status($cashAdvance,'modified');
        Session::flash('message', 'Updated Successfully.');
        return redirect()->route('payroll.openCashAdvance');
    }
    public function removeCashAdvance($id)
    {
        $cashAdvance=CashAdvances::where('id',$id)->first();
        $cashAdvance->delete();
        Session::flash('message', 'Deleted Successfully.');
        return redirect()->route('payroll.openCashAdvance');
    }

    public function movetoQueue_immediatePay($cashAdvance){
        $BD=Globals::first();
        $payment=Payments::where('id',$cashAdvance->payments_id)->first();
        if (count($payment)==0) $payment=Payments::create();
        $cashAdvance->payments_id=$payment->id;
        $cashAdvance->save();
        $payment->workers_id=$cashAdvance->workers_id;
        $payment->payrolls_id=Auth::user()->payrollmanager()->id;
        $payment->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        $payment->amount=$cashAdvance->total_due;
        $payment->currency_type=strtolower($cashAdvance->currency_type);

        $payment->payment_method=$cashAdvance->payment_method;
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
        $paymentline->service_id='0505';
        $paymentline->description='Cash Advance Payout: '.$cashAdvance->description;
        $paymentline->quantity_hours=1;
        $paymentline->rate=$cashAdvance->total_due;
        $paymentline->amount=$cashAdvance->total_due;
        $paymentline->save();

        $now_date=date('D, m/d/y',strtotime(Carbon::now()));
        $worker=$cashAdvance->worker();
            $data['document']='payment_summary_immediate';
            $data['BD']=$BD;
            $data['now_date']=$now_date;
            $data['worker']=$worker;
                $lines=array();
                $line['service_id']='0505';
                $line['description']='Cash Advance Payout: '.$cashAdvance->description;
                $line['quantity_hours']=1;
                $line['rate']=$cashAdvance->total_due;
                $line['amount']=$cashAdvance->total_due;
                $lines[]=$line;
            $data['lines']=$lines;

            $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
            $summary_report=$pdf->output();
            Storage::put($file,$summary_report);
             
    }

    public function cashAdvance_status($cash_advance,$status){
        $file='cash_advance/'.$cash_advance->id.'.pdf';
        $data['document']='cash_advance';
        $data['cash_advance']=$cash_advance;
        $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
        $report=$pdf->output();
        Storage::put($file,$report);

        $mailto=$cash_advance->worker()->email_main;
        Mail::to($mailto)->send(new CashAdvanceStatusWorker($cash_advance,$status));
    }

}
