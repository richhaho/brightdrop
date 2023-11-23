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
use App\DeclinedCandidates;
use App\Groups;
use App\Globals;
use Carbon\Carbon;
use Auth;


class DeclinedController extends Controller
{
    public function pendingNotice()
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
        $declineds=DeclinedCandidates::where('status', 'Pending Notice')->where('account_managers_id', $accountmanager->id)->get();

        $decline_reason = [
            '' => '',
            'Applicant Withdrew' => 'Applicant Withdrew',
            'Client Declined' => 'Client Declined',
            'Not Qualified' => 'Not Qualified',
            'Other (See Notes)' => 'Other (See Notes)'
        ];
        $data=[
            'accountManagers' => $accountManagers,
            'workers' => $workers,
            'clients'=>$clients,
            'declineds'=>$declineds,
            'candidates'=>$candidates,
            'decline_reason'=>$decline_reason
        ];

        return view('account.declined.pendingNotice',$data);
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
        $declineds=DeclinedCandidates::where('status', 'Completed')->where('account_managers_id', $accountmanager->id)->get();
        $decline_reason = [
            '' => '',
            'Applicant Withdrew' => 'Applicant Withdrew',
            'Client Declined' => 'Client Declined',
            'Not Qualified' => 'Not Qualified',
            'Other (See Notes)' => 'Other (See Notes)'
        ];
        $data=[
            'accountManagers' => $accountManagers,
            'workers' => $workers,
            'clients'=>$clients,
            'declineds'=>$declineds,
            'candidates'=>$candidates,
            'decline_reason'=>$decline_reason
        ];

        return view('account.declined.completed',$data);
    }

    public function saveDeclined(Request $request)
    { 
        $data=$request->all();
        $candidate = Candidates::where('id', $request->candidates_id)->first();
        $data['workers_id'] = $candidate->name;
        $data['email'] = $data['email'] ? $data['email'] : $candidate->email_address;
        
        $declined = DeclinedCandidates::where('id', $request->declined_id)->first();
        if (empty($declined)) {
            $declined = DeclinedCandidates::create($data);
        } else {
            $declined->update($data);
        }
        return response()->json($declined);
    }
    public function updateOne(Request $request)
    {
        $declined = DeclinedCandidates::where('id', $request->declined_id)->first();
        if (empty($declined)) {
            return 'Error: Declined Candidate not found.';
        }
        $data[$request->field] = $request->value;
        try {
            $declined->update($data);
            return response()->json($declined);
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    public function remove(Request $request)
    { 
        $declined = DeclinedCandidates::where('id', $request->declined_id)->first();
        if (empty($declined)) {
            return 'Error: Declined Candidate not found.';
        }
        try {
            $declined->delete();
            return 'OK';
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    public function updateStatus(Request $request)
    { 
        $declined = DeclinedCandidates::where('id', $request->declined_id)->first();
        if (empty($declined)) {
            return 'Error: Declined Candidate not found.';
        }
        try {
            $declined->status=$request->status;
            $declined->save();
            return 'OK';
        } catch (\Exception $e) {
            return 'Error';
        }
    }
    
}
