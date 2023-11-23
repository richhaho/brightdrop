<?php

namespace App\Http\Controllers\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Auth;

use Storage;
use PDF;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use Response;
use App\InvoiceLines;
use App\Invoices;

class ContactController extends Controller
{
    public function needsApproval()
    {
        return view('contact.Contact_needsApproval');
    }

    public function pastInvoices()
    {
        $client=Auth::user()->contact()->client();
        if ($client){
            $invoices=$client->invoices()->where('status','Bank Verified')->orderBy('date_verified','desc')->get();
        }else{
            $invoices=array();
        }
        $data=[
            'invoices'=>$invoices,
            'client'=>$client,
        ];
        return view('contact.pastInvoices',$data);
    }
    
    
    public function holidaySchedule()
    {
        $current_year=date('Y',strtotime(Carbon::now()));
        $client=Auth::user()->contact()->client();
        $holidays=[];
        if ($client){
            if ($client->holiday_shedule_offered && $client->holiday_shedule_offered!='no_holiday') {
                $holidays=$client->holidays()->where('year',$current_year)->get();
            }            
        }        
        $data=[
            'current_year'=>$current_year,
            'holidays'=>$holidays,
            'client'=>$client,
        ];
        return view('contact.holidaySchedule',$data);
    }
    
    public function PTOInformation()
    {
        $current_year=date('Y',strtotime(Carbon::now()));
        $client= Auth::user()->contact()->client();
        if ($client && $client->pto_infomation=='yes'){
            $workers=Auth::user()->contact()->client()->activeWorkers();
        }else{
             $workers=[];
        }
        

        $data=[
            'client'=>$client,
            'workers'=>$workers,
            'current_year'=>$current_year,
        ];
        return view('contact.PTOInformation',$data);
    }


    public function download($id)
    {
        $invoice=Invoices::where('id',$id)->first();
        $contents = Storage::get($invoice->invoice_report_file);
        $response = Response::make($contents, '200',[
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice.pdf"',
            ]);
       
        return $response;
    }
    
}
