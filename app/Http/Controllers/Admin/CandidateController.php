<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Workers;
use App\Clients;
use App\Positions;
use App\Candidates;
use App\HiredCandidates;
use App\DeclinedCandidates;
use App\Groups;
use App\Globals;
use Carbon\Carbon;
use Auth;


class CandidateController extends Controller
{
    public function saveCandidate(Request $request)
    { 
        $data=$request->all();
        $candidate = Candidates::where('id', $request->candidate_id)->first();
        $defaultColumns = ['groups_id', 'candidate_id','position_id','name','worker_profile','email_address','video_profile','country','requested_pay','worker_monthly_rate','worker_hourly_rate','worker_currency_type','client_hourly_rate','available_start_date','worker_source','internal_notes','shared_notes','shared_notes_client'];
        $worker = Workers::where('id', $request->name)->first();
        if (empty($candidate)) {
            $group = Groups::where('id', $request->groups_id)->first();
            $data['sort'] = count($group->candidates());
            $candidate = Candidates::create($data);
        }
        $candidate->update($data);
        $candidate->worker_profile=route('admin.worker.profile', $request->name);
        $candidate->email_address=$worker->email_main;
        $candidate->video_profile=$request->video_profile ? $request->video_profile : $worker->temp_video_link;
        $candidate->country=$worker->country;
        // $candidate->requested_pay=$worker->fulltime_compensation_amount;
        $candidate->requested_pay=$worker->fulltime_compensation_amount;
        $candidate->worker_currency_type=strtoupper($worker->currency_type);
        $candidate->available_start_date=$worker->available_start_date;
        $worker_sources=[
            ''=>'',
            'brightdrop_support_mailbox'=>'BrightDrop Support Mailbox',
            'facebook_external_post'=>'Facebook - External Post',
            'facebook_internal_page'=>'Facebook - Internal Page',
            'indeed'=>'Indeed',
            'internal_recruitment_manager'=>'Internal - Recruitment Manager',
            'internal_other'=>'Internal - Other Employee',
            'job_street'=>'Job Street',
            'onlinejob.ph'=>'Onlinejobs.ph',
            'unknown'=>'Unknown',
            'worker referral'=>'Worker Referral',  
            'other'=>'Other',
        ];
        $worker_source = $worker_sources[$worker->worker_source];
        if ($worker->worker_source=='internal_recruitment_manager') {
            $internal_recruitment_managers=[
                '0'=>'Unassigned',
                '1'=>'Recruitment Manager1',
                '2'=>'Recruitment Manager2',
                '3'=>'Recruitment Manager3'
            ];
            $worker_source = $worker_source . ($worker->internal_recruitment_manager ? ': '.$internal_recruitment_managers[$worker->internal_recruitment_manager] : '');
        } else if ($worker->worker_source=='internal_other') {
            $worker_source = $worker_source. ($worker->internal_other_employee ? ': '.$worker->internal_other_employee : '');
        } else if ($worker->worker_source=='worker referral') {
            $worker_referral = Workers::where('id', $worker->worker_referral)->first();
            $worker_source = $worker_source. ($worker_referral ? ': '.$worker_referral->full_name : '');
        }
        $candidate->worker_source=$worker_source;
        $other_columns=[];
        foreach($data as $key=>$val) {
            if (!in_array($key, $defaultColumns)) {
                $other_columns[$key]=$data[$key];
            }
        }
        $arrayWorker =  json_decode(json_encode($worker), true);
        $readonlyHyperlinkColumns = $candidate->group()->position()->columns();
        foreach($readonlyHyperlinkColumns as $col) {
            if (in_array($col['field'], $defaultColumns)) continue;
            if ($col['field_type'] == 'hyperlink') {
                $other_columns[$col['field']]=$col['drop_down_options'];
            }
            if ($col['field_type'] == 'readonly') {
                $other_columns[$col['field']]=$arrayWorker[$col['drop_down_options']];
            }
        }

        if (count($other_columns)>0) {
            $candidate->other_columns=json_encode($other_columns);
        }
        $candidate->save();
        
        return response()->json($candidate);
    }
    public function update($id, Request $request)
    {

    }

    public function removeCandidate(Request $request)
    { 
        $candidate = Candidates::where('id', $request->candidate_id)->first();
        if (empty($candidate)) {
            return 'Error: Candidate not found.';
        }
        try {
            $candidate->delete();
            return 'OK';
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    public function finalDecision(Request $request)
    { 
        $candidate = Candidates::where('id', $request->candidate_id)->first();
        if (empty($candidate)) {
            return 'Error: Candidate not found.';
        }
        try {
            $client = $candidate->group()->position()->client();
                $accountManager = $client ? $client->accountmanager() : null;
                $data = [
                    'account_managers_id' => $accountManager ? $accountManager->id : null,
                    'workers_id' => $candidate->name,
                    'candidates_id' => $candidate->id,
                    'clients_id' => $client ? $client->id : null,
                    'email' => $candidate->email_address,
                    'client_hourly_rate_usd' => $candidate->client_hourly_rate,
                    'requested_pay' => $candidate->requested_pay,
                    'worker_monthly_rate' => null, // $candidate->worker_monthly_rate,
                    'worker_hourly_rate' => null, // $candidate->worker_hourly_rate,
                    'worker_currency_type' => $candidate->worker_currency_type,
                    'status' => 'Needs Finalized'
                ];
            if ($request->decision == 'hired') {
                HiredCandidates::create($data);
            } else if ($request->decision == 'declined') {
                $data['status'] = 'Pending Notice';
                DeclinedCandidates::create($data);
            }
            $candidate->status=$request->decision;
            $candidate->save();
            return 'OK';
        } catch (\Exception $e) {
            return 'Error';
        }
    }

    public function moveToGroup(Request $request)
    { 
        $candidate = Candidates::where('id', $request->candidate_id)->first();
        if (empty($candidate)) {
            return 'Error: Candidate not found.';
        }
        try {
            $group = Groups::where('id', $request->to_group_id)->first();
            $candidate->sort = count($group->candidates());
            $candidate->groups_id=$request->to_group_id;
            $candidate->save();
            return 'OK';
        } catch (\Exception $e) {
            return 'Error';
        }
    }
    
    function updateNote(Request $request) {
        $candidate = Candidates::where('id', $request->candidate_id)->first();
        $data = [
            $request->field => $request->notes
        ];
        if (empty($candidate)) {
            return 'Error: Candidate not found.';
        }
        try {
            $candidate->update($data);
            return 'OK';
        } catch (\Exception $e) {
            return 'Error';
        }
    }
    public function updateOrder(Request $request)
    {
        $orders = array_flip(explode(',', $request->orders));
        $group = Groups::where('id', $request->group_id)->first();
        foreach($group->candidates() as $candidate) {
            $candidate->sort = $orders[$candidate->id];
            $candidate->save();
        }
        return 'OK';
    }
}
