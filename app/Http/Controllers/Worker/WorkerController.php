<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Workers;
use App\AccountManagers;
use App\SpecialNotes;
use App\Clients;
use App\ClientInfoWorkers;
use App\Payments;
use Auth;
use App\HolidaySchedule;
use Carbon\Carbon;
use Storage;
use Response;

class WorkerController extends Controller
{
    public function holidaySchedule()
    {
        $worker=Auth::user()->worker();
        $clients=$worker->activeClients();
        $current_year=date('Y',strtotime(Carbon::now()));
        $data=[
            'worker'=>$worker,
            'clients'=>$clients,
            'current_year'=>$current_year,
        ];
        return view('worker.holidaySchedule',$data);
    }
        
    public function paymentSummaries()
    {
        $worker=Auth::user()->worker();
        $payments=$worker->payments()->orderBy('date_queue','desc')->get();
        $data=[
            'worker'=>$worker,
            'payments'=>$payments,
        ];
        return view('worker.paymentSummaries',$data);
    }
    public function downloadSummaries($id, Request $request)
    {
        if (isset($request->type)){
            $contents = Storage::get('timecard/'.$id.'.pdf');
        }else{
            $payment=Payments::where('id',$id)->first();
            $contents = Storage::get($payment->payment_summary_report_file);
        }

        $response = Response::make($contents, '200',[
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="payment_sumary.pdf"',
            ]);
       
        return $response;
    }
    
     
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function profile()
    {
        $worker=Auth::user()->worker();
        $special_notes=$worker->specialCandiateNotes()->get();

        $status=[
            'new_candidate'=>'Candidate - New',
            'pre_candidate'=>'Candidate - Previous Hire (See Notes)',
            'available_hired'=>'Hired - Available',
            'not_available_hired'=>'Hired - Not Available',
            'not_available_see_notes'=>'Not Available (See Notes)',
            'disqualfied'=>'Disqualified (See Notes)'
        ];
        $country=[
            'Mexico'=>'Mexico',
            'Nicaragua'=>'Nicaragua',
            'Philippines'=>'Philippines',
            'USA'=>'USA'
        ];
        $philippines_region=[
            'none'=>' ',
            'Luzon'=>'Luzon',
            'Mindanao'=>'Mindanao',
            'Visayas'=>'Visayas'
        ];
        $candidate_account_manager_id=AccountManagers::where('deleted_at',null)->get()->pluck('fullname','id')->prepend('Unassigned',0);
        $gender=[
            'Male'=>'Male',
            'Female'=>'Female'
        ];
        $currency_type=[
            ''=>'',
            'php'=>'PHP',
            'mxn'=>'MXN',
            'usd'=>'USD',
        ];
        $fulltime_compensation_currency=[
            ''=>'',
            'mxn'=>'MXN',
            'php'=>'PHP',
            'usd'=>'USD'
        ];
        $available_hours=[
            '0'=>'0',
            '10'=>'10',
            '20'=>'20',
            '30'=>'30',
            '40'=>'40',            
        ];
        $outside_brightdrop=[
            'yes'=>'Yes',
            'no'=>'No',
        ];
        $hours_outside_perweek=[
            '0'=>'0',
            '10'=>'10',
            '20'=>'20',
            '30'=>'30',
            '40'=>'40',            
        ];
        $target_client=Clients::where('deleted_at',null)->get()->pluck('client_name','id');
        $worker_source=[
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
            'other'=>'Other'   
        ];
        $internal_recruitment_manager=[
            '0'=>'Unassigned',
            '1'=>'Recruitment Manager1',
            '2'=>'Recruitment Manager2',
            '3'=>'Recruitment Manager3'
        ];
        $internal_other_employee=$internal_recruitment_manager;
        $worker_referral=Workers::get()->pluck('fullname','id');

        $english_verbal=[
            '0'=>'0',
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',            
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
            '10'=>'10',
        ];

        $skill_name=[
             
            'Call Center - Cold Calling'=>'Call Center - Cold Calling',
            'Call Center - Customer Service (General/Other)'=>'Call Center - Customer Service (General/Other)',
            'Call Center - Escalations Desk'=>'Call Center - Escalations Desk',
            'Call Center - Tech Support'=>'Call Center - Tech Support',
            'Cold Calling (Non Call Center)'=>'Cold Calling (Non Call Center)',
            'Executive Assistant'=>'Executive Assistant',
            'Foreign Language - French'=>'Foreign Language - French',
            'Foreign Language - Spanish'=>'Foreign Language - Spanish',
            'Graphic Designer'=>'Graphic Designer',
            'Medical Record Collections'=>'Medical Record Collections',
            'Tech Support (Non Call Center)'=>'Tech Support (Non Call Center)',
            'Other/General'  =>'Other/General'     
        ];
        $skill_industry=[
            'Banking'=>'Banking',
            'High Tech'=>'High Tech',
            'Real Estate'=>'Real Estate',
            'Telecom'=>'Telecom',            
            'Other'=>'Other',
        ];
        $years=[
            '0'=>'0',
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',            
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
            '10'=>'10',
            '10+'=>'10+',
        ];
        $months=[
            '0'=>'0',
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',            
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
            '10'=>'10',
            '11'=>'11',
            '12'=>'12',
        ];
        $software_name=[
             
            'Accounting - Quickbooks (Desktop)'=>'Accounting - Quickbooks (Desktop)',
            'Accounting - Quickbooks (Online)'=>'Accounting - Quickbooks (Online)',
            'Accounting - Xero'=>'Accounting - Xero',
            'CRM - Insightly'=>'CRM - Insightly',
            'CRM - Salesforce'=>'CRM - Salesforce',
            'CRM - Zoho'=>'CRM - Zoho',
            'Graphic Design - Adobe Illustrator'=>'Graphic Design - Adobe Illustrator',
            'Marketing - MailChimp'=>'Marketing - MailChimp',
            'Real Estate - Boomtown'=>'Real Estate - Boomtown'
        ];
        $software_industry=$skill_industry;

        $internet_connection_primary=[
            ''=>'',
            'Globe'=>'Globe',
            'PLDT'=>'PLDT',
            'Sky'=>'Sky',
            'Smart'=>'Smart',
            'None'=>'None',            
            'Other'=>'Other',
        ];
        $internet_connection_primary_type=[
            ''=>'',
            'Wired'=>'Wired',
            'Wireless'=>'Wireless',
        ];
        
        $backup_connection=[
            'no'=>'No',
            'yes'=>'Yes',
        ];
        $backup_connection_isp=$internet_connection_primary;
        $backup_connection_type=$internet_connection_primary_type;
       
        $primary_computer_type=[
            ''=>'',
            'Laptop'=>'Laptop',
            'Desktop'=>'Desktop',
        ];
        $primary_computer_age=$years;
        $primary_computer_system=[
            ''=>'',
            'Windows'=>'Windows',
            'Mac'=>'Mac',
        ];


        $backup_computer=[
            'yes'=>'Yes',
            'no'=>'No',
        ];
        $backup_computer_type=[
            ''=>'',
            'Laptop'=>'Laptop',
            'Desktop'=>'Desktop',
        ];
        $backup_computer_age=$years;
        $backup_computer_system=[
            ''=>'',
            'Windows'=>'Windows',
            'Mac'=>'Mac',
        ];
        $clients=$worker->clients();


        $pto_summaries=array();
        $y=date('y',strtotime(\Carbon\Carbon::now()));
        $Year=date('Y',strtotime(Carbon::now())).'-%';
        
        foreach ($clients as $client) {
            $info=$client->assigned_worker_info()->where('workers_id',$worker->id)->first();
            $currentyear=date('Y',strtotime(Carbon::now()));
            $hiredyear=date('Y',strtotime($info->hired_at));
            if ($currentyear==$hiredyear){
                $pto_days_worker_default=$info->ptodays_current_calendar;
            }else{
                $pto_days_worker_default=$info->ptodays_full_calendar;
            }
            //if ($pto_days_worker_default==0 && $info->ptodays_full_calendar==0) $pto_days_worker_default=$client->default_pto_days;
            if ($client->pto_infomation!='yes') continue;
            $pto_summary['title']='20'.$y.' -  PTO Summary: '.$client->client_name;
            $pto_used=$worker->ptos()->where('clients_id',$client->id)->where('status','Approved')->where('date_pto','like',$Year)->get();
            $pto_sum=$worker->ptos()->where('clients_id',$client->id)->where('status','Approved')->where('date_pto','like',$Year)->sum('total_hours');
            $pto_remain=$pto_days_worker_default*24-$pto_sum;
            $pto_remaining=intval($pto_remain/24).' Days, '.($pto_remain-intval($pto_remain/24)*24).' Hours';
            $pto_summary['pto_used']=$pto_used;
            $pto_summary['pto_remaining']=$pto_remaining;
            $pto_summaries[]=$pto_summary;
        }


        $data=[
            'country'=>$country,
            'status'=>$status,
            'philippines_region'=>$philippines_region,
            'candidate_account_manager_id'=>$candidate_account_manager_id,
            'gender'=>$gender,
            'currency_type'=>$currency_type,
            'fulltime_compensation_currency'=>$fulltime_compensation_currency,
            'available_hours'=>$available_hours,
            'outside_brightdrop'=>$outside_brightdrop,
            'hours_outside_perweek'=>$hours_outside_perweek,
            'target_client'=>$target_client,
             
            'worker_source'=>$worker_source,
            'internal_recruitment_manager'=>$internal_recruitment_manager,
            'internal_other_employee'=>$internal_other_employee,
            'worker_referral'=>$worker_referral,
            'english_verbal'=>$english_verbal,
            'english_written'=>$english_verbal,
            'skill_name'=>$skill_name,
            'skill_industry'=>$skill_industry,
            'skill_years'=>$years,
            'skill_months'=>$months,

            'software_name'=>$software_name,
            'software_industry'=>$software_industry,
            'software_years'=>$years,
            'software_months'=>$months,
            'internet_connection_primary'=>$internet_connection_primary,
            'internet_connection_primary_type'=>$internet_connection_primary_type,
            'backup_connection'=>$backup_connection,
            'backup_connection_isp'=>$backup_connection_isp,
            'backup_connection_type'=>$backup_connection_type,

            'primary_computer_type'=>$primary_computer_type,
            'primary_computer_age'=>$primary_computer_age,
            'primary_computer_system'=>$primary_computer_system,

            'backup_computer'=>$backup_computer,
            'backup_computer_type'=>$backup_computer_type,
            'backup_computer_age'=>$backup_computer_age,
            'backup_computer_system'=>$backup_computer_system,

            'special_notes'=>$special_notes,
            'pto_summaries'=>$pto_summaries,
            'clients'=>$clients,
            'worker'=>$worker,


        ];
 
        return view('worker.profile',$data);
    }

    public function update(Request $request)
    {
    
        $worker=Auth::user()->worker();
        $data=$request->all();
        $worker->update($data);
        if (isset($data['first_name']) && isset($data['last_name'])) $worker->fullname=$data['first_name'].' '.$data['last_name'];
        
        $other_document_files=array();

        if (isset($data['other_document_file_label']) && unserialize($worker->other_document_files)) {
            $oldIds = [];
            foreach ($data['other_document_file_label'] as $key => $value) {
                $oldIds[] = $key;
            }
            foreach (unserialize($worker->other_document_files) as $odf) {
                if (in_array($odf['id'], $oldIds)) {
                    $odfUpdated = $odf;
                    $odfUpdated['label'] = $data['other_document_file_label'][$odf['id']];
                    $other_document_files[]=$odfUpdated;
                }
            }
            $worker->other_document_files=serialize($other_document_files);
            $worker->save();
        }

        if ($request->file('other_document_file')) {
            $files = $request->file('other_document_file');
            foreach ($files as $key => $f) {
                $xfilename = $f->getClientOriginalName();
                $xpath = 'attachments/workers/other_document_files/';
                $f->storeAs($xpath,$xfilename);
                $odf['id'] = $key;
                $odf['label'] = $data['other_document_file_label'][$key];
                $odf['filename'] = $xfilename;
                $odf['path'] = $xpath;
                $other_document_files[]=$odf;
            }
            $worker->other_document_files=serialize($other_document_files);
            $worker->save();
        }

        if (count($other_document_files) == 0) {
            $worker->other_document_files=null;
            $worker->save();
        }
        
        if ($request['ica']!=null && $request['ica']!="" ) {
            $f = $request->file('ica');
            $xfilename = $worker->id . "." . $f->guessExtension();
            $xpath = 'attachments/workers/ica/';
            $f->storeAs($xpath,$xfilename);
            $worker->ica = $xpath.$xfilename;
        }
        if ($request['typing_test_file']!=null && $request['typing_test_file']!="" ) {
            $f = $request->file('typing_test_file');
            $xfilename = $worker->id . "." . $f->guessExtension();
            $xpath = 'attachments/workers/typing_test_file/';
            $f->storeAs($xpath,$xfilename);
            $worker->typing_test_file = $xpath.$xfilename;
        }
        if ($request['resume_file']!=null && $request['resume_file']!="" ) {
            $f = $request->file('resume_file');
            $xfilename = $worker->id . "." . $f->guessExtension();
            $xpath = 'attachments/workers/resume_file/';
            $f->storeAs($xpath,$xfilename);
            $worker->resume_file = $xpath.$xfilename;
        }
        if ($request['goverment_id']!=null && $request['goverment_id']!="" ) {
            $f = $request->file('goverment_id');
            $xfilename = $worker->id . "." . $f->guessExtension();
            $xpath = 'attachments/workers/goverment_id/';
            $f->storeAs($xpath,$xfilename);
            $worker->goverment_id = $xpath.$xfilename;
        }

        if ($request['NBI']!=null && $request['NBI']!="" ) {
            $f = $request->file('NBI');
            $xfilename = $worker->id . "." . $f->guessExtension();
            $xpath = 'attachments/workers/NBI/';
            $f->storeAs($xpath,$xfilename);
            $worker->NBI = $xpath.$xfilename;
        }
        if ($request['internet_connection_primary_screenshot']!=null && $request['internet_connection_primary_screenshot']!="" ) {
            $f = $request->file('internet_connection_primary_screenshot');
            $xfilename = $worker->id . "." . $f->guessExtension();
            $xpath = 'attachments/workers/internet_connection_primary_screenshot/';
            $f->storeAs($xpath,$xfilename);
            $worker->internet_connection_primary_screenshot = $xpath.$xfilename;
        }
        if ($request['backup_connection_screenshot']!=null && $request['backup_connection_screenshot']!="" ) {
            $f = $request->file('backup_connection_screenshot');
            $xfilename = $worker->id . "." . $f->guessExtension();
            $xpath = 'attachments/workers/backup_connection_screenshot/';
            $f->storeAs($xpath,$xfilename);
            $worker->backup_connection_screenshot = $xpath.$xfilename;
        }
         

        if(isset($data['skill_name'])){
            $skill=array();
            $skill_id=0;
            foreach ($data['skill_name'] as $key => $value) {
                $skill_id++;
                $skill_row['id']=$skill_id;
                $skill_row['skill_name']=$value;
                $skill_row['skill_industry']=$request->skill_industry[$key];
                $skill_row['skill_years']=$request->skill_years[$key];
                $skill_row['skill_months']=$request->skill_months[$key];
                $skill_row['skill_note']=$request->skill_note[$key];
                $skill[]=$skill_row;
            }
            $skills=serialize($skill);
            $worker->skills=$skills;
        }


        if(isset($data['software_name'])){
            $software=array();
            $software_id=0;
            foreach ($data['software_name'] as $key => $value) {
                $software_id++;
                $software_row['id']=$software_id;
                $software_row['software_name']=$value;
                $software_row['software_industry']=$request->software_industry[$key];
                $software_row['software_years']=$request->software_years[$key];
                $software_row['software_months']=$request->software_months[$key];
                $software_row['software_note']=$request->software_note[$key];
                $software[]=$software_row;
            }
            $softwares=serialize($software);
            $worker->software_knowledge=$softwares;
        }
        $this->updateCandidateData($worker);
        if ($request['video_file']!=null && $request['video_file']!="" ) {
            $f = $request->file('video_file');
            $uploading=$this->uploadVideoFile($worker, $f);
            $generation=$this->generateVideoLink($worker);
            if ($generation!='success'){
                Session::flash('message',"Worker: ".$worker->fullname()."'s profile created Successfully. But could not generate video link because ".$generation);
                return redirect()->route('worker.profile',$worker->id);
            }
        }

        $worker->save();
        $user=$worker->user();
        $user->name=$worker->fullname;
        $user->email=$worker->email_main;
        $user->save();
        $this->updateCandidateData($worker);
        Session::flash('message',"Worker: ".$worker->fullname()."'s profile updated Successfully.");
        return redirect()->route('worker.profile');

    }

    public function updateCandidateData($worker)
    {
        $arrayWorker =  json_decode(json_encode($worker), true);
        $candidates = $worker->candidates();
        foreach ($candidates as $candidate) {
            $candidate->country=$worker->country;
            $candidate->requested_pay=$worker->fulltime_compensation_amount;
            $candidate->worker_currency_type=strtoupper($worker->currency_type);
            $candidate->available_start_date=$worker->available_start_date;
            $candidate->video_profile=$worker->temp_video_link;
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
                'other'=>'Other'
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
            $others=json_decode($candidate->other_columns, true);
            $other_columns=[];
            if ($others) {
                $group = $candidate->group();
                if ($group) {
                    $position = $group->position();
                    if ($position) {
                        $columns = $position->columns();
                        $readonlyColumns = [];
                        foreach ($columns as $col) {
                            if ($col['field_type'] == 'readonly') {
                                $readonlyColumns[$col['field']] = $col['drop_down_options'];
                            }
                        }
                        foreach ($others as $field => $val) {
                            if (isset($readonlyColumns[$field])) {
                                $other_columns[$field]=$arrayWorker[$readonlyColumns[$field]];
                            } else {
                                $other_columns[$field]=$val;
                            }
                        }
                    }
                }
            }
            if (count($other_columns)>0) {
                $candidate->other_columns=json_encode($other_columns);
            }
            $candidate->save();
            foreach ($candidate->hireds() as $hired) {
                $hired->requested_pay = $candidate->requested_pay;
                $hired->save();
            }
        }
    }

    public function assignedWorkerCurrency(Request $request)
    {
        $clients_id=$request->client_id;
        $workers_id=Auth::user()->worker()->id;
        $info=ClientInfoWorkers::where('clients_id',$clients_id)->where('workers_id',$workers_id)->first();
        return isset($info->currency_type) ? $info->currency_type:'';
    }
    
    public function download(Request $request)
    {
        $id=$request->id;
        $filename=$request->filename;
        $array=explode('/',$filename);
        $file=end($array);
        if (!isset($request->type)) {
            if(!Storage::disk()->exists($filename)) {
                Session::flash('message',"No Uploaded File.");
                return redirect()->route('worker.profile',$id);
            };
            $contents = Storage::get($filename);
        }else{
            if(!Storage::disk('public_uploads')->exists($filename)) {
                Session::flash('message',"No Uploaded File.");
                return redirect()->route('worker.profile',$id);
            };
            $contents = Storage::disk('public_uploads')->get($filename);
        }
        $response = Response::make($contents, '200',[
            'Content-Disposition' => 'attachment; filename="'.$file.'"',
            ]);
       
        return $response;
    }

    public function uploadVideoFile($worker, $f){
        $tfilename = $worker->id . "." . $f->guessExtension();
        $tpath= 'workers_video/temp';

        $xfilename = $worker->id . ".mp4";
        $xpath = 'workers_video';
        
        $f->storeAs($tpath,$xfilename,'public_uploads');
        $server_dir=env('SERVER_DIR');
        $uploaded_file=$server_dir.'/public/uploads/'.$tpath.'/'.$tfilename;
        $convert_file=$server_dir.'/public/uploads/'.$xpath.'/'.$xfilename;
        
        exec('ffmpeg -i '.$uploaded_file.' -c:v libx264 '.$convert_file.' -y');
        
        $worker->video_file = $xpath.'/'.$xfilename;
        $worker->save();
    }

    public function generateVideoLink($worker)
    {
        $path=$worker->video_sub_link;
        if (!$path) return 'video sub link field is blank.';
        if (count(Workers::where('video_sub_link',$path)->get())>1) return 'video sub link field was duplicated.';
        $path='video/'.$path;
        if(!is_dir($path)) mkdir($path);
        $mfile=$path.'/index.php';
        $f=fopen($mfile,'w');
        $content='<!DOCTYPE html>'
.'<html>'
.'<head>'
.    '<title>BrightDrop Worker Video Profile</title>'
.    '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">'
.    '<script src="https://code.jquery.com/jquery-2.2.3.min.js"/>'
.    '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>'
.    '<style type="text/css">.bold{font-weight: 600}</style>'
.'</head>'
.'<body>'
.    '<div class="row">'
.        '<div class="col-xs-6 col-xs-offset-3">'
.            '<div class="col-xs-12">'
.                '<center><h3><a>'.$worker->fullname.' Video Profile</a><br></h3></center>'
.            '</div>'
.            '<div class="col-xs-12">'
.                '<center><iframe src="/uploads/'.$worker->video_file.'" width="400" height="300" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe></center>'
 .           '</div><br><br><br>'
 .           '<div class="col-xs-12">'
 .               '<span class="bold">First Name: </span><span>'.$worker->first_name.'</span>'
 .           '</div>'
 .           '<div class="col-xs-12">'
 .               '<span class="bold">Last Name: </span><span>'.$worker->last_name.'</span>'
 .           '</div>'
 .           '<div class="col-xs-12">'
 .               '<br><p class="bold">Writing Sample:</p>'
 .               '<div>'.nl2br($worker->writing_sample).'</div>'
 .           '</div>'
 .       '</div>'
 .   '</div>'
.'</body>'
.'</html>';
        fwrite($f,$content);
        fclose($f);
        $worker->video_link=url('').'/'.$path;
        $worker->save();
        return 'success';
    }
}
