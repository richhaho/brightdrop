<?php

namespace App\Http\Controllers\Account;

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


class CashAdvanceController extends Controller
{
    
    public function openCashAdvance()
    {
        $cash_advances=CashAdvances::where('deleted_at',null)->where('status','!=','repaid')->get();
        $workers_id=Workers::where('deleted_at',null)->get()->pluck('fullname','id');
        $data=[
            'workers_id'=>$workers_id, 
            'cash_advances'=>$cash_advances,
        ];

        return view('account.cash_advance.openCashAdvance',$data);
    }
    public function pastCashAdvance()
    {
         
        
        $workers_id=Workers::where('deleted_at',null)->get()->pluck('fullname','id');
        
        
        $cash_advances=CashAdvances::where('deleted_at',null)->where('status','repaid')->get();

        $data=[
            'workers_id'=>$workers_id,
             
            'cash_advances'=>$cash_advances,
        ];

        return view('account.cash_advance.pastCashAdvance',$data);
    }

    public function viewReport($id)
    {
        $payment=Payments::where('id',$id)->first();
        $contents = Storage::get($payment->payment_summary_report_file);
        $response = Response::make($contents, '200',[
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="payment_sumary.pdf"',
            ]);
       
        return $response;
    }

}
