<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Clients;
use App\Workers;
use App\AccountManagers;
use App\OneTimeAdjustments;
use App\RecurringAdjustments;
use Auth;
use Carbon\Carbon;
use Storage;


class AdjustmentController extends Controller
{
    public function create()
    {
        $onetime_recurring=[
            'onetime'=>'One-Time Adjustment',
            'recurring'=>'Recurring Adjustment',
        ];
        $onetime_recurring_val='onetime';
        // if (session()->has('adjustment.onetime_recurring')){
        //     $onetime_recurring_val=session('adjustment.onetime_recurring');
        // }

        $accountmanager=Auth::user()->accountmanager();
        $clients=$accountmanager->clients()->orderBy('client_name', 'asc')->get();
        
        $workers=$accountmanager->workers()->get();
        $clients_id=$clients->pluck('client_name','id')->prepend('','');
        $workers_id=$workers->pluck('fullname','id')->prepend('','');;

        $billto=[
            ''=>'',
            'BrightDrop'=>'BrightDrop',
            'Client'=>'Client',
            'Worker'=>'Worker',
        ];
        $payto=[
            ''=>'',
            'BrightDrop'=>'BrightDrop',
            'Client'=>'Client',
            'Worker'=>'Worker',
        ];
        $payment_method=[
            'Veem'=>'Veem',
            'Western Union'=>'Western Union',
        ];
        $other_currency=[
            ''=>'',
            'php'=>'PHP',
            'mxn'=>'MXN',
            'usd'=>'USD',
        ];
        $type=[
            ''=>'',
            'Time Adjustment'=>'Time Adjustment',
            'Other'=>'Other',
        ];
        $rate=[
            'Regular'=>'Regular',
            'Overtime'=>'Overtime',
            'Percent-Other'=>'Percent-Other',
        ];
        

        $currency_type=[
            ''=>'',
            'php'=>'PHP',
            'mxn'=>'MXN',
            'usd'=>'USD',
        ];
        $onetime_adjustments=OneTimeAdjustments::where('account_managers_id',$accountmanager->id)->where('status','Pending')->orderBy('date_submitted','desc')->get();
        $recurring_adjustments=RecurringAdjustments::where('account_managers_id',$accountmanager->id)->where('status','Pending')->orderBy('date_submitted','desc')->get(); 

        $onetime_workers=array();
        $onetime_workers['']=[];
        foreach ($clients as $client) {
            $onetime_workers[$client->id]=$client->activeWorkers()->pluck('fullname','id');
        }

        $data=[
            'onetime_workers'=>$onetime_workers,
            'recurring_workers'=>$onetime_workers,
            'onetime_recurring'=>$onetime_recurring,
            'onetime_recurring_val'=>$onetime_recurring_val,
            'clients'=>$clients,
            'billto'=>$billto,
            'payto'=>$payto,
            'payment_method'=>$payment_method,
            'clients_id'=>$clients_id,
            'workers_id'=>$workers_id,
             
            'other_currency'=>$other_currency,
            'type'=>$type,
            'rate'=>$rate,
            'currency_type'=>$currency_type,
            'paytoclient'=>$clients_id,
            'paytoworker'=>$workers_id,
            'billtoclient'=>$clients_id,
            'billtoworker'=>$workers_id,
            'onetime_adjustments'=>$onetime_adjustments,
            'recurring_adjustments'=>$recurring_adjustments,
             
        ];
        return view('account.adjustment.create',$data);
    }

    public function active()
    {
        $accountmanager=Auth::user()->accountmanager();
        $clients=$accountmanager->clients()->get();
        $workers=$accountmanager->workers()->get();
        $clients_id=$clients->pluck('client_name','id');
        $workers_id=$workers->pluck('fullname','id');

        $onetime_adjustments=OneTimeAdjustments::where('account_managers_id',$accountmanager->id)->where('status','Pending')->orderBy('date_submitted','desc')->get();
        $recurring_adjustments=RecurringAdjustments::where('account_managers_id',$accountmanager->id)->where('status','Approved')->orderBy('date_submitted','desc')->get(); 
        $data=[
            'onetime_adjustments'=>$onetime_adjustments,
            'recurring_adjustments'=>$recurring_adjustments,
            'clients_id'=>$clients_id,
            'workers_id'=>$workers_id,
            'paytoclient'=>$clients_id,
            'paytoworker'=>$workers_id,
            'billtoclient'=>$clients_id,
            'billtoworker'=>$workers_id,
        ];
        return view('account.adjustment.active',$data);
    }
    public function past()
    {
        $accountmanager=Auth::user()->accountmanager();
        $clients=$accountmanager->clients()->get();
        $workers=$accountmanager->workers()->get();
        $clients_id=$clients->pluck('client_name','id');
        $workers_id=$workers->pluck('fullname','id');

        // $onetime_adjustments=OneTimeAdjustments::where('account_managers_id',$accountmanager->id)
        //     ->where(function($query) {
        //         $query->where(function($q){
        //             $q->where('billto', 'Client')->where('invoices_id', '!=', null)->where('status', 'Paid');
        //         })->orwhere(function($q){
        //             $q->where('billto', '!=', 'Client')->where('status', 'Paid');
        //         })->orwhere(function($q){
        //             $q->where('payto', '!=','Worker')->where('invoices_id', '!=', null)->where('status', 'Approved');
        //         })->orWhere('status', 'Declined');
        //     })
        //     ->orderBy('date_submitted','desc')->get();
        $onetime_adjustments=[];
        $adjustments=OneTimeAdjustments::where('account_managers_id',$accountmanager->id)->whereIn('status',['Declined', 'Paid', 'Approved'])->orderBy('date_submitted','desc')->get();
        foreach($adjustments as $adjustment) {
            if ($adjustment->payto == 'Worker' && $adjustment->billto == 'BrightDrop') {
                $onetime_adjustments[] = $adjustment;
            } elseif ($adjustment->payto == 'Worker' && $adjustment->billto == 'Client') {
                if ($adjustment->status == 'Paid' && $adjustment->invoices_id) {
                    $onetime_adjustments[] = $adjustment;
                } elseif ($adjustment->status == 'Declined') {
                    $onetime_adjustments[] = $adjustment;
                }
            } elseif ($adjustment->payto == 'Client' && $adjustment->billto == 'BrightDrop') {
                if (($adjustment->invoices_id && $adjustment->status == 'Approved')|| $adjustment->status == 'Declined') {
                    $onetime_adjustments[] = $adjustment;
                }
            } elseif ($adjustment->payto == 'Client' && $adjustment->billto == 'Worker') {
                if ($adjustment->status == 'Paid' && $adjustment->invoices_id) {
                    $onetime_adjustments[] = $adjustment;
                } elseif ($adjustment->status == 'Declined') {
                    $onetime_adjustments[] = $adjustment;
                }                
            } elseif ($adjustment->payto == 'BrightDrop' && $adjustment->billto == 'Client') {
                if (($adjustment->invoices_id && $adjustment->status == 'Approved')|| $adjustment->status == 'Declined') {
                    $onetime_adjustments[] = $adjustment;
                }
            } elseif ($adjustment->payto == 'BrightDrop' && $adjustment->billto == 'Worker' && $adjustment->status != 'Approved') {
                $onetime_adjustments[] = $adjustment;
            }
        }
        $recurring_adjustments=RecurringAdjustments::where('account_managers_id',$accountmanager->id)->whereIn('status',['Paid', 'Invoice Verified', 'Declined'])->orderBy('date_submitted','desc')->get(); 
        $data=[
            'onetime_adjustments'=>$onetime_adjustments,
            'recurring_adjustments'=>$recurring_adjustments,
            'clients_id'=>$clients_id,
            'workers_id'=>$workers_id,
            'paytoclient'=>$clients_id,
            'paytoworker'=>$workers_id,
            'billtoclient'=>$clients_id,
            'billtoworker'=>$workers_id,
        ];
        return view('account.adjustment.past',$data);
    }
    public function pending()
    {
        $accountmanager=Auth::user()->accountmanager();
        $clients=$accountmanager->clients()->get();
        $workers=$accountmanager->workers()->get();
        $clients_id=$clients->pluck('client_name','id');
        $workers_id=$workers->pluck('fullname','id');

        $onetime_adjustments=OneTimeAdjustments::where('account_managers_id',$accountmanager->id)->where('status','Pending')->orderBy('date_submitted','desc')->get();
        $recurring_adjustments=RecurringAdjustments::where('account_managers_id',$accountmanager->id)->where('status','Pending')->orderBy('date_submitted','desc')->get();  
        $data=[
            'onetime_adjustments'=>$onetime_adjustments,
            'recurring_adjustments'=>$recurring_adjustments,
            'clients_id'=>$clients_id,
            'workers_id'=>$workers_id,
            'paytoclient'=>$clients_id,
            'paytoworker'=>$workers_id,
            'billtoclient'=>$clients_id,
            'billtoworker'=>$workers_id,
        ];
        return view('account.adjustment.pending',$data);
    }
     

    public function submit_onetime(Request $request)
    {
        $data=$request->all();
        session(['adjustment.onetime_recurring'=>'onetime']);
        if ($data['type']=='Time Adjustment') {$data['other_currency']='';}
        session(['adjustment.filled_data'=>$data]);
        if ($data['payto']==$data['billto']){
            Session::flash('message', '"Pay To" and "Bill To" can not be selected with same option in One-Time Adjustment.');
            return redirect()->route('account.adjustment.create');
        }
        if ($data['type']=='Time Adjustment'){
            if (strtotime($data['adjustment_date'])>strtotime(date('Y-m-d',strtotime(Carbon::now())))){
                Session::flash('message', "A Future date cannot be used for One-Time Adjustment.");
                return redirect()->route('account.adjustment.create');
            }
            if (!$data['adjustment_date'] || !$data['adjustment_total_hours']){
                Session::flash('message', "Please input time adjustment date and total hours in One-Time Adjustment.");
                return redirect()->route('account.adjustment.create');
            }
            if ($data['rate']=='Percent-Other'){
                if (!$data['percent_other']){
                    Session::flash('message', "Please input Percent-Other value in One-Time Adjustment.");
                    return redirect()->route('account.adjustment.create');
                }
            }

        }
        if ($data['type']=='Other'){
            if (!$data['other_description'] || !$data['other_amount']){
                Session::flash('message', "Please input other description and amount in One-Time Adjustment.");
                return redirect()->route('account.adjustment.create');
            }
        }
        $client=Clients::where('id',$data['clients_id'])->first();
        if (count($client->workers()->where('id',$data['workers_id']))==0){
            Session::flash('message', "The worker is not assigned to this client.");
            return redirect()->route('account.adjustment.create');
        }
        $adjustment=OneTimeAdjustments::create();
        $adjustment->account_managers_id=Auth::user()->accountmanager()->id;
        $adjustment->payto=$data['payto'];
        $adjustment->billto=$data['billto'];
        $adjustment->type=$data['type'];
        $adjustment->internal_notes=$data['internal_notes'];
        $adjustment->status='Pending';
        $adjustment->date_submitted=date('Y-m-d',strtotime(Carbon::now()));
        $adjustment->clients_id=$data['clients_id'];
        $adjustment->workers_id=$data['workers_id'];

        if ($data['payto']=='Client') {$adjustment->paytoclient=$data['clients_id'];}
        if ($data['payto']=='Worker') {$adjustment->paytoworker=$data['workers_id'];$adjustment->payment_method=$data['payment_method'];}

        if ($data['billto']=='Client') {$adjustment->billtoclient=$data['clients_id'];}
        if ($data['billto']=='Worker') {$adjustment->billtoworker=$data['workers_id'];}

        if ($data['type']=='Time Adjustment'){
            $adjustment->adjustment_date=$data['adjustment_date'];
            $adjustment->adjustment_total_hours=$data['adjustment_total_hours'];
            $adjustment->rate=$data['rate'];
            $adjustment->percent_other=$data['percent_other'];
        }else{
            $adjustment->other_description=$data['other_description'];
            $adjustment->other_amount=$data['other_amount'];
            $adjustment->other_currency=strtolower($data['other_currency']);
            // if ($data['payto']=='Worker' || $data['billto']=='Worker'){
            //     $worker=Workers::where('id',$data['workers_id'])->first();
            //     $adjustment->other_currency=$worker->currency_type;
            // }else{
            //     $adjustment->other_currency='usd';
            // }
        }
        $adjustment->save();
        $data = [
            'clients_id' => '',
            'workers_id' => '',
            'type' => '',
            'adjustment_date' => '',
            'adjustment_total_hours' => '',
            'rate' => '',
            'percent_other' => '',
            'other_description' => '',
            'other_amount' => '',
            'other_currency' => '',
            'payto' => '',
            'payment_method' => '',
            'billto' => '',
            'internal_notes' => ''
        ];
        session()->forget('adjustment.filled_data');
        session(['adjustment.filled_data'=>$data]);
        Session::flash('message', "Submitted Successfully.");
        return redirect()->route('account.adjustment.create');
    }

    public function submit_recurring(Request $request)
    {
        $data=$request->all();
        session(['adjustment.onetime_recurring'=>'recurring']); 
        session(['adjustment_recurring.filled_data'=>$data]);

        if ($data['payto']==$data['billto']){
            Session::flash('message', '"Pay To" and "Bill To" can not be selected with same option when create adjustment recurring.');
            return redirect()->route('account.adjustment.create');
        }
        if ($data['amount']==0) {
            Session::flash('message', 'Amount can not be set by 0.');
            return redirect()->route('account.adjustment.create');
        }
       
        $adjustment=RecurringAdjustments::create();
        $adjustment->clients_id=$data['clients_id'];
        $adjustment->workers_id=$data['workers_id'];
        $adjustment->account_managers_id=Auth::user()->accountmanager()->id;
        $adjustment->payto=$data['payto'];
        $adjustment->billto=$data['billto'];
        $adjustment->internal_notes=$data['internal_notes'];
        $adjustment->status='Pending';
        $adjustment->date_submitted=date('Y-m-d',strtotime(Carbon::now()));

        if ($data['payto']=='Client') {$adjustment->paytoclient=$data['clients_id'];}
        if ($data['payto']=='Worker') {$adjustment->paytoworker=$data['workers_id'];$adjustment->payment_method='Veem';}

        if ($data['billto']=='Client') {$adjustment->billtoclient=$data['clients_id'];}
        if ($data['billto']=='Worker') {$adjustment->billtoworker=$data['workers_id'];}
        $adjustment->save();
        $adjustment->description=$data['description'];
        $adjustment->amount=$data['amount'];
        $adjustment->currency_type=strtolower($data['currency_type']);
        // if ($data['payto']=='Worker' || $data['billto']=='Worker'){
        //     $worker=Workers::where('id',$adjustment->workers_id)->first();
        //     $adjustment->currency_type=$worker->currency_type;
        // }else{
        //     $adjustment->currency_type='usd';
        // }


        $adjustment->save();
        $data = [
            'payto' => '',
            'paytoclient' => '',
            'paytoworker' => '',
            'billto' => '',
            'billtoclient' => '',
            'billtoworker' => '',
            'description' => '',
            'currency_type' => '',
            'amount' => '',
            'internal_notes' => ''
        ];
        session()->forget('adjustment_recurring.filled_data');
        session(['adjustment_recurring.filled_data'=>$data]);
        Session::flash('message', "Submitted Successfully.");
        return redirect()->route('account.adjustment.create');
    }
    public function remove_onetime(Request $request)
    {
        $id=$request->id;
        $from=$request->from;
        $adjustment=OneTimeAdjustments::where('id',$id)->first();
        $adjustment->delete();
        if ($from == 'pending') {
            $payment_immediate = $adjustment->payment_immediate();
            if ($payment_immediate) {
                $payment_immediate->delete();
            }
        }
        Session::flash('message', "The One time adjustment deleted.");
        return redirect()->route('account.adjustment.'.$from);
    }
    public function remove_recurring(Request $request)
    {
        $id=$request->id;
        $from=$request->from;
        $adjustment=RecurringAdjustments::where('id',$id)->first();
        $adjustment->delete();
        Session::flash('message', "Recurring Adjustment deleted.");
        return redirect()->route('account.adjustment.'.$from);
    }
    public function update_onetime(Request $request)
    {
        $id=$request->id; 
        $data=$request->all();
        
        $adjustment=OneTimeAdjustments::where('id',$id)->first();
        if ($adjustment->type=='Other') {
            $adjustment->other_description=$data['other_description'];
            $adjustment->other_amount=$data['other_amount'];
            $adjustment->other_currency=$data['other_currency'];
        } else {
            $adjustment->adjustment_date=$data['adjustment_date'];
            $adjustment->adjustment_total_hours=$data['adjustment_total_hours'];
            $adjustment->rate=$data['rate'];
        }
        $adjustment->internal_notes=$data['internal_notes'];
        $adjustment->date_submitted=date('Y-m-d',strtotime(Carbon::now()));
        
        $adjustment->save();

        Session::flash('message', "Onetime Adjustment updated Successfully.");
        return redirect()->route('account.adjustment.'.$data['from']);
    }
    public function update_recurring(Request $request)
    {
        $id=$request->id; 
        $data=$request->all();
        
        $adjustment=RecurringAdjustments::where('id',$id)->first();
        
        $adjustment->internal_notes=$data['internal_notes'];
        $adjustment->date_submitted=date('Y-m-d',strtotime(Carbon::now()));
        $adjustment->description=$data['description'];
        $adjustment->amount=$data['amount'];
        $adjustment->currency_type=$data['currency_type'];
        
        $adjustment->save();

        Session::flash('message', "Recurring Adjustment updated Successfully.");
        return redirect()->route('account.adjustment.'.$data['from']);
    }

}
