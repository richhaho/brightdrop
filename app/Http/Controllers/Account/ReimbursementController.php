<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Clients;
use App\Workers;
use App\Reimbursement;
use App\OneTimeAdjustments;
use Auth;
use Storage;
use Carbon\Carbon;
use Response;
use Mail;
use App\Mail\ReimbursementDeclinedWorker;

class ReimbursementController extends Controller
{
    public function ReimbursementNeedsApproval()
    {
        $accountmanager=Auth::user()->accountmanager();
        $clients=$accountmanager->clients()->get();
        $worker_ids=$accountmanager->workers()->get()->pluck('id');
        $reimbursements=Reimbursement::whereIn('workers_id',$worker_ids)->where('status','Pending')->get();

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
        $statement_included=[
            'yes'=>'Yes',
            'no'=>'No',
        ];
        $data=[
            'clients'=>$clients,
            'bill_to'=>$bill_to,
            'payment_method'=>$payment_method,
            'approve_decline'=>$approve_decline,
            'reimbursements'=>$reimbursements,
            'statement_included'=>$statement_included,
             
        ];

        return view('account.adjustment.ReimbursementNeedsApproval',$data);
    }
    

    public function submit_reimbursementApproval(Request $request)
    {
        $clients_id=$request->client_id;
        $workers_id=$request->worker_id;
         
        $reimbursement=Reimbursement::where('id',$request->reimbursement_id)->first();
        $reimbursement->additional_notes_account=$request->additional_notes_account;
        $reimbursement->bill_to=$request->bill_to;
        $reimbursement->payment_method=$request->payment_method;
        $reimbursement->status='Approved';
        $reimbursement->handle_date=date('Y-m-d',strtotime(Carbon::now()));
        $reimbursement->save();
        if ($reimbursement->type=='Internet - Backup' || $reimbursement->type=='Internet - Primary'){
            $reimbursement->internet_service_provider=$request->internet_service_provider;
            $reimbursement->statement_date=$request->statement_date;
            $reimbursement->statement_included=$request->statement_included;
            $reimbursement->save();

            if ($request['copy_statement_file']!=null && $request['copy_statement_file']!="" ) {
                 $f = $request->file('copy_statement_file');
                 $xfilename = "statement-" .$reimbursement->id . "." . $f->guessExtension();
                $xpath = 'attachments/copy-of-statement-or-receipt/';
                $f->storeAs($xpath,$xfilename);
                
                $reimbursement->copy_statement_file = $xfilename;
                $reimbursement->save();
            }
        }


        $adjustment=OneTimeAdjustments::create();
        $adjustment->account_managers_id=Auth::user()->accountmanager()->id;
        $adjustment->payto='Worker';
        $adjustment->billto=$request->bill_to;
        $adjustment->type=$reimbursement->type!='Other' ? $reimbursement->type: $reimbursement->other_type;
        $adjustment->other_description=$adjustment->type;
        $adjustment->payment_method=$request->payment_method;
        $adjustment->internal_notes=$request->additional_notes_account;
        $adjustment->status='Pending';
        $adjustment->other_amount=$reimbursement->amount;
        $adjustment->other_currency=$reimbursement->currency_type;
        $adjustment->date_submitted=date('Y-m-d',strtotime(Carbon::now()));
        $adjustment->clients_id=$clients_id;
        $adjustment->workers_id=$workers_id;
        $adjustment->paytoworker=$workers_id;
        if ($request->bill_to=='Client') $adjustment->billtoclient=$clients_id;

        $adjustment->save();
        $reimbursement->onetime_adjustments_id=$adjustment->id;
        $reimbursement->save();
        
        Session::flash('message', 'Approved.');
        return redirect()->route('account.ReimbursementNeedsApproval');
    }

    public function decline_reimbursementApproval(Request $request)
    {
        $id=$request->id;
        $reimbursement=Reimbursement::where('id',$id)->first();

        $mailto=$reimbursement->worker()->email_main;
        Mail::to($mailto)->send(new ReimbursementDeclinedWorker($reimbursement,'declined'));
        $reimbursement->additional_notes_account=$request->additional_notes_account;
        $reimbursement->status='Declined';
        $reimbursement->handle_date=date('Y-m-d',strtotime(Carbon::now()));
        $reimbursement->save();
        
        Session::flash('message', 'Declined');
        return redirect()->route('account.ReimbursementNeedsApproval');
    }







////////////////////////////////////////////////////////////////////////
    public function ReimbursementPast()
    {
        $accountmanager=Auth::user()->accountmanager();
        $clients=$accountmanager->clients()->get();
        $worker_ids=$accountmanager->workers()->get()->pluck('id');
        $reimbursementss=Reimbursement::whereIn('workers_id',$worker_ids)->where('status','!=','Pending')->orderBy('handle_date','desc')-> orderBy('date','desc')->get();
        $data=[
            'reimbursementss'=>$reimbursementss,
        ];

        return view('account.worker_additional.ReimbursementPast',$data);
    }
    public function ReimbursementArchive()
    {
        $accountmanager=Auth::user()->accountmanager();
        $clients=$accountmanager->clients()->get();
        $worker_ids=$accountmanager->workers()->get()->pluck('id');
        $reimbursementss=Reimbursement::whereIn('workers_id',$worker_ids)->orderBy('handle_date','desc')-> orderBy('date','desc')->get();
        $data=[
            'reimbursementss'=>$reimbursementss,
        ];

        return view('account.worker_additional.ReimbursementArchive',$data);
    }


    public function download(Request $request)
    {
        $id=$request->id;
        $xpath = 'attachments/copy-of-statement-or-receipt/';
        $filename=$xpath.$request->filename;
        if(!Storage::disk()->exists($filename)) {
            Session::flash('message',"No Uploaded File.");
            return redirect()->route('account.ReimbursementNeedsApproval');
        };
        $array=explode('/',$filename);
        $file=end($array);
        $contents = Storage::get($filename);
        $response = Response::make($contents, '200',[
            'Content-Disposition' => 'attachment; filename="'.$file.'"',
            ]);
       
        return $response;
    }

}
