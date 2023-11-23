<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Clients;
use App\Workers;
use App\Reimbursement;
use Auth;
use Storage;


class ReimbursementController extends Controller
{
    public function reimbursementRequest()
    {
        $worker=Auth::user()->worker();
        $clients=$worker->clients();
        $clients_list=$worker->clients()->pluck('client_name','id')->prepend('','');
        $currency_type=[
            ''=>'',
            'php'=>'PHP',
            'mxn'=>'MXN',
            'usd'=>'USD',
        ];
        $date=date('Y-m-d',strtotime(\Carbon\Carbon::now()));
        $type=[
            'Computer'=>'Computer',
            'Computer Repair'=>'Computer Repair',
            'Internet - Backup'=>'Internet - Backup',
            'Internet - Primary'=>'Internet - Primary',
            'Other'=>'Other',
        ];
        $statement_included=[
            ''=>'',
            'yes'=>'Yes',
            'no'=>'No',
        ];
        $reimbursements=$worker->reimbursements()->where('status','Pending')->get();
        

        $data=[
            'worker'=>$worker,
            'clients'=>$clients,
            'clients_list'=>$clients_list,
            'currency_type'=>$currency_type,
            'date'=>$date,
            'type'=>$type,
            'statement_included'=>$statement_included,
            'reimbursements'=>$reimbursements,
            
        ];

        return view('worker.reimbursementRequest',$data);
    }
    public function summaryReimbursement()
    {
        $worker=Auth::user()->worker();
        $clients=$worker->clients();
        $clients_list=$worker->clients()->pluck('client_name','id');
        $currency_type=[
            'php'=>'PHP',
            'mxn'=>'MXN',
            'usd'=>'USD',
        ];
         
        $reimbursements=$worker->reimbursements()->where('status','!=','Pending')->get();
        $data=[
            'worker'=>$worker,
            'clients'=>$clients,
            'clients_list'=>$clients_list,
            'currency_type'=>$currency_type,
            'reimbursements'=>$reimbursements,
        ];

        return view('worker.summaryReimbursement',$data);
    }
    public function pendingReimbursement()
    {
        $worker=Auth::user()->worker();
        $clients=$worker->clients();
        $clients_list=$worker->clients()->pluck('client_name','id');
        $currency_type=[
            'php'=>'PHP',
            'mxn'=>'MXN',
            'usd'=>'USD',
        ];
         
        $reimbursements=$worker->reimbursements()->where('status','Pending')->get();
        $data=[
            'worker'=>$worker,
            'clients'=>$clients,
            'clients_list'=>$clients_list,
            'currency_type'=>$currency_type,
            'reimbursements'=>$reimbursements,
        ];

        return view('worker.pendingReimbursement',$data);
    }

    public function submit_reimbursementRequest(Request $request)
    {
        $clients_id=$request->clients_list;
        $workers_id=Auth::user()->worker()->id;
        $reimbursement=Reimbursement::create();
        $reimbursement->clients_id=$clients_id;
        $reimbursement->workers_id=$workers_id;
        
        $reimbursement->date=$request->date;
        $reimbursement->type=$request->type;
        $reimbursement->amount=$request->amount;
        $reimbursement->currency_type=strtolower($request->currency_type);
        $reimbursement->other_type=$request->other_type;
        $reimbursement->internet_service_provider=$request->internet_service_provider;
        $reimbursement->status="Pending";
        $reimbursement->statement_date=$request->statement_date;
        $reimbursement->statement_included=$request->statement_included;
        $reimbursement->additional_notes=$request->additional_notes;
        $reimbursement->save();

        //$reimbursement->copy_statement_file=$request->copy_statement_file;
        if ($request['copy_statement_file']!=null && $request['copy_statement_file']!="" ) {
             $f = $request->file('copy_statement_file');
             $xfilename = "statement-" .$reimbursement->id . "." . $f->guessExtension();
            $xpath = 'attachments/copy-of-statement-or-receipt/';
            $f->storeAs($xpath,$xfilename);
            
            $reimbursement->copy_statement_file = $xfilename;
            $reimbursement->save();
        }

        Session::flash('message', 'Successfully sent request.');
        return redirect()->route('worker.reimbursementRequest');
    }

}
