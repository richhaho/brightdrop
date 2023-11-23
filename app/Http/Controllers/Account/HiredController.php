<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Session;
use Storage;
use Illuminate\Support\Facades\Input;
use App\AccountManagers;
use App\Workers;
use App\Clients;
use App\Positions;
use App\Candidates;
use App\HiredCandidates;
use App\Groups;
use App\Globals;
use Carbon\Carbon;
use Auth;


class HiredController extends Controller
{
    public function needsFinalized()
    {
        $accountmanager=Auth::user()->accountmanager();
        $accountManagers=$search_account=[
            '' => 'All',
            $accountmanager->id => $accountmanager->full_name
        ];
        $workers=Workers::where('deleted_at', null)->get()->pluck('full_name', 'id')->prepend('', '')->toArray();
        $clients=Clients::where('deleted_at',null)->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('', '')->toArray();
        $candiateList = Candidates::where('status', null)->groupBy('name')->get();
        $candidates[''] = '';
        foreach ($candiateList as $candidate) {
            $candidates[$candidate->id] = $workers[$candidate->name];
        }
        $hireds=HiredCandidates::where('status', 'Needs Finalized')->where('account_managers_id', $accountmanager->id)->get();
        $data=[
            'accountManagers' => $accountManagers,
            'workers' => $workers,
            'clients'=>$clients,
            'hireds'=>$hireds,
            'candidates'=>$candidates
        ];

        return view('account.hired.needsFinalized',$data);
    }

    public function pendingReview()
    {
        $accountmanager=Auth::user()->accountmanager();
        $accountManagers=$search_account=[
            '' => 'All',
            $accountmanager->id => $accountmanager->full_name
        ];
        // $workers=$accountmanager->workers()->get()->pluck('full_name', 'id')->prepend('', '')->toArray();
        // $clients=$accountmanager->clients()->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('', '')->toArray();
        $workers=Workers::where('deleted_at', null)->get()->pluck('full_name', 'id')->prepend('', '')->toArray();
        $clients=Clients::where('deleted_at',null)->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('', '')->toArray();
        $candiateList = Candidates::where('status', null)->groupBy('name')->get();
        $candidates[''] = '';
        foreach ($candiateList as $candidate) {
            $candidates[$candidate->id] = $workers[$candidate->name];
        }
        $hireds=HiredCandidates::where('status', 'Pending Review')->where('account_managers_id', $accountmanager->id)->get();
        $data=[
            'accountManagers' => $accountManagers,
            'workers' => $workers,
            'clients'=>$clients,
            'hireds'=>$hireds,
            'candidates'=>$candidates
        ];

        return view('account.hired.pendingReview',$data);
    }

    public function needsSetup()
    {
        $accountmanager=Auth::user()->accountmanager();
        $accountManagers=$search_account=[
            '' => 'All',
            $accountmanager->id => $accountmanager->full_name
        ];
        // $workers=$accountmanager->workers()->get()->pluck('full_name', 'id')->prepend('', '')->toArray();
        // $clients=$accountmanager->clients()->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('', '')->toArray();
        $workers=Workers::where('deleted_at', null)->get()->pluck('full_name', 'id')->prepend('', '')->toArray();
        $clients=Clients::where('deleted_at',null)->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('', '')->toArray();
        $candiateList = Candidates::where('status', null)->groupBy('name')->get();
        $candidates[''] = '';
        foreach ($candiateList as $candidate) {
            $candidates[$candidate->id] = $workers[$candidate->name];
        }
        $hireds=HiredCandidates::where('status', 'Needs Setup')->where('account_managers_id', $accountmanager->id)->get();
        $data=[
            'accountManagers' => $accountManagers,
            'workers' => $workers,
            'clients'=>$clients,
            'hireds'=>$hireds,
            'candidates'=>$candidates
        ];

        return view('account.hired.needsSetup',$data);
    }

    public function completed()
    {
        $accountmanager=Auth::user()->accountmanager();
        $accountManagers=$search_account=[
            '' => 'All',
            $accountmanager->id => $accountmanager->full_name
        ];
        // $workers=$accountmanager->workers()->get()->pluck('full_name', 'id')->prepend('', '')->toArray();
        // $clients=$accountmanager->clients()->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('', '')->toArray();
        $workers=Workers::where('deleted_at', null)->get()->pluck('full_name', 'id')->prepend('', '')->toArray();
        $clients=Clients::where('deleted_at',null)->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('', '')->toArray();
        $candiateList = Candidates::where('status', null)->groupBy('name')->get();
        $candidates[''] = '';
        foreach ($candiateList as $candidate) {
            $candidates[$candidate->id] = $workers[$candidate->name];
        }
        $hireds=HiredCandidates::where('status', 'Completed')->where('account_managers_id', $accountmanager->id)->get();
        $data=[
            'accountManagers' => $accountManagers,
            'workers' => $workers,
            'clients'=>$clients,
            'hireds'=>$hireds,
            'candidates'=>$candidates
        ];

        return view('account.hired.completed',$data);
    }

    public function saveHired(Request $request)
    { 
        $data=$request->all();
        $candidate = Candidates::where('id', $request->candidates_id)->first();
        $data['workers_id'] = $candidate->name;
        $data['email'] = $data['email'] ? $data['email'] : $candidate->email_address;
        $data['client_hourly_rate_usd'] = $data['client_hourly_rate_usd'] ? $data['client_hourly_rate_usd'] : $candidate->client_hourly_rate_usd;
        $data['worker_monthly_rate'] = $data['worker_monthly_rate'] ? $data['worker_monthly_rate'] : $candidate->worker_monthly_rate;
        $data['worker_hourly_rate'] = $data['worker_hourly_rate'] ? $data['worker_hourly_rate'] : $candidate->worker_hourly_rate;
        $data['worker_currency_type'] = $data['worker_currency_type'] ? $data['worker_currency_type'] : $candidate->worker_currency_type;
        $data['requested_pay'] = $candidate->requested_pay;
        
        $hired = HiredCandidates::where('id', $request->hired_id)->first();
        if (empty($hired)) {
            $hired = HiredCandidates::create($data);
        } else {
            $hired->update($data);
        }
        return response()->json($hired);
    }
    public function updateOne(Request $request)
    {
        $hired = HiredCandidates::where('id', $request->hired_id)->first();
        if (empty($hired)) {
            return 'Error: Hired Candidate not found.';
        }
        $data[$request->field] = $request->value;
        try {
            $hired->update($data);
            return response()->json($hired);
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    public function remove(Request $request)
    { 
        $hired = HiredCandidates::where('id', $request->hired_id)->first();
        if (empty($hired)) {
            return 'Error: Hired Candidate not found.';
        }
        try {
            $hired->delete();
            return 'OK';
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    public function updateStatus(Request $request)
    { 
        $hired = HiredCandidates::where('id', $request->hired_id)->first();
        if (empty($hired)) {
            return 'Error: Hired Candidate not found.';
        }
        try {
            $worker = $hired->worker();
            $worker->status = $hired->available_additional_work == 'yes' ? 'available_hired' : 'not_available_hired';
            $worker->save();
            $hired->status=$request->status;
            $hired->save();
            return 'OK';
        } catch (\Exception $e) {
            return 'Error';
        }
    }
    
    public function uploadICA(Request $request) {
        $hired = HiredCandidates::where('id', $request->hired_id)->first();
        if (empty($hired)) {
            return 'Error: Hired Candidate not found.';
        }
        $f = $request->file('ica');
        if (empty($f)) {
            return 'Error';
        }
        $xfilename = $hired->id . "." . $f->guessExtension();
        $xpath = 'attachments/hired_candidates/ica/';
        try {
            $f->storeAs($xpath,$xfilename);
            $hired->ica = $xpath.$xfilename;
            $hired->save();
            $worker = $hired->worker();
            if ($worker) {
                $wfilename = $worker->id . "." . $f->guessExtension();
                $wpath = 'attachments/workers/ica/';
                $f->storeAs($wpath,$wfilename);
                $worker->ica = $wpath.$wfilename;
            }
            return response()->json($hired);
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    public function downloadICA(Request $request) {
        $filename=$request->filename;
        $array=explode('/',$filename);
        $file=end($array);
        if (!isset($request->type)) {
            if(!Storage::disk()->exists($filename)) {
                Session::flash('message',"No Uploaded File.");
                return redirect()->back();
            };
            $contents = Storage::get($filename);
        }else{
            if(!Storage::disk('public_uploads')->exists($filename)) {
                Session::flash('message',"No Uploaded File.");
                return redirect()->back();
            };
            $contents = Storage::disk('public_uploads')->get($filename);
        }
        $response = Response::make($contents, '200',[
            'Content-Disposition' => 'attachment; filename="'.$file.'"',
        ]);
       
        return $response;
    }
}
