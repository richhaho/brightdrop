<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Workers;
use App\Clients;
use App\ClientInfoWorkers;
use App\AccountManagers;
use App\Admins;
use App\PayrollManagers;

use App\SpecialNotes;
use App\Role;
use App\Globals;

use Carbon\Carbon;
use Auth;
use App\Payments;
use Storage;
use Response;
use Mail;
use App\Mail\WorkerVideoProfileClient;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendLinkResetPassword;


class WorkerController extends Controller
{
    public function create()
    {
        $status=[
            'new_candidate'=>'Candidate - New',
            'pre_candidate'=>'Candidate - Previous Hire (See Notes)',
            'available_hired'=>'Hired - Available',
            'not_available_hired'=>'Hired - Not Available',
            'not_available_see_notes'=>'Not Available (See Notes)',
            'disqualfied'=>'Disqualified (See Notes)'
        ];
        $country=[
            ''=>'',
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
            ''=>'',
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
        $target_client=Clients::where('deleted_at',null)->pluck('client_name','id')->prepend('','');
        $worker_source=[
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
        $internal_recruitment_manager=[
            '0'=>'Unassigned',
            '1'=>'Recruitment Manager1',
            '2'=>'Recruitment Manager2',
            '3'=>'Recruitment Manager3'
        ];
        $internal_other_employee['']='';
        $AC=AccountManagers::where('deleted_at',null)->get();
        foreach ($AC as $row) {
            $internal_other_employee[$row->fullname]=$row->fullname;
        }
        $AM=Admins::where('deleted_at',null)->get();
        foreach ($AM as $row) {
            $internal_other_employee[$row->fullname]=$row->fullname;
        }
        $PM=PayrollManagers::where('deleted_at',null)->get();
        foreach ($PM as $row) {
            $internal_other_employee[$row->fullname]=$row->fullname;
        }
        $worker_referral=Workers::where('deleted_at',null)->get()->pluck('fullname','id');

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
            ''=>'', 
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
            ''=>'', 
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
            ''=>'', 
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
            'no'=>'No',
            'yes'=>'Yes',
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
        $clients=Clients::where('deleted_at',null)->get();
         
        $global_values=Globals::first()->values;
        if (unserialize($global_values)){
            foreach (unserialize($global_values) as $other){
                if (strnatcasecmp($other['fieldname'],'Skill Name')==0){
                    $skill_name=array();
                    $skill_name['']='';
                    foreach (explode(',', $other['value']) as $value) {
                        $skill_name[trim($value)]=trim($value);
                    }
                }

                if (strnatcasecmp($other['fieldname'],'Industry')==0){
                    $skill_industry=array();
                    $skill_industry['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $skill_industry[trim($value)]=trim($value);
                    }
                    $software_industry=$skill_industry;
                }

                if (strnatcasecmp($other['fieldname'],'Software Name')==0){
                    $software_name=array();
                    $software_name['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $software_name[trim($value)]=trim($value);
                    }
                }
                if (strnatcasecmp($other['fieldname'],'Primary Connection - ISP')==0){
                    $internet_connection_primary=array();
                    $internet_connection_primary['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $internet_connection_primary[trim($value)]=trim($value);
                    }
                }
                 
            }    
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

            'clients'=>$clients,

        ];

        return view('admin.worker.create',$data);
    }
    public function store(Request $request)
    {
        $data=$request->all();

        if (count(User::where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('admin.worker.create');
        }
        if (count(Workers::where('email_veem',$data['email_veem'])->get())>0){
            Session::flash('message',"Veem email already exists on other worker. Please input unique veem email for new worker.");
            return redirect()->route('admin.worker.create');
        }
        if (count(Workers::where('email_main',$data['email_main'])->get())>0){
            Session::flash('message',"Main email already exists on other worker. Please input unique main email for new worker.");
            return redirect()->route('admin.worker.create');
        }

        $worker=Workers::create($data);
        $user= User::create([
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['user_email'],
            'password' => bcrypt($data['password']),
            'worker_id' => $worker->id,
        ]);
        $default_role= Role::where('name', 'Worker')->first();
        $user->attachRole( $default_role);
        $worker->user_id=$user->id;
        $worker->account_managers_id=0;
        $worker->fullname=$data['first_name'].' '.$data['last_name'];
        $worker->save();

        $other_document_files=array();
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
        
        if(isset($data['target_client'])){
            $target_clients=array();
            foreach ($data['target_client'] as $key => $value) {
                $target_clients[]=$value;
            }
            $worker->target_client=implode(",",array_unique($target_clients));
            $worker->save();
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


        $worker->updated_at=date('Y-m-d H:i:s');
        $worker->save();

        
        if(isset($data['candiate_notes'])){
            foreach ($data['candiate_notes'] as $key => $value) {
                $special_note=SpecialNotes::create();
                $special_note->workers_id=$worker->id;
                $special_note->account_managers_id=0;
                $special_note->admins_id=Auth::user()->admin()->id;
                $special_note->inputbyname=Auth::user()->admin()->fullname;
                $special_note->inputby='admin';
                $special_note->note=$value;
                $special_note->date=date('Y-m-d',strtotime(Carbon::now()));

                $special_note->save();
            }
        }

        if ($request['video_file']!=null && $request['video_file']!="" ) {
            $f = $request->file('video_file');
            $uploading=$this->uploadVideoFile($worker, $f);
            $generation=$this->generateVideoLink($worker);
            if ($generation!='success'){
                Session::flash('message',"Worker: ".$worker->fullname()."'s profile created Successfully. But could not generate video link because ".$generation);
                return redirect()->route('admin.worker.profile',$worker->id);
            }
        }
        $worker->save();

        Session::flash('message',"New worker: ".$worker->fullname()."'s profile created Successfully.");
        return redirect()->route('admin.worker.profile',$worker->id);

    }
    public function profile($id)
    {
        $worker=Workers::where('id',$id)->first();
        $special_notes=$worker->specialCandiateNotes()->get();
        $worker->updated_at=date('Y-m-d H:i:s');
        $worker->save();
        $status=[
            'new_candidate'=>'Candidate - New',
            'pre_candidate'=>'Candidate - Previous Hire (See Notes)',
            'available_hired'=>'Hired - Available',
            'not_available_hired'=>'Hired - Not Available',
            'not_available_see_notes'=>'Not Available (See Notes)',
            'disqualfied'=>'Disqualified (See Notes)'
        ];
        $country=[
            ''=>'',
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
            ''=>'',
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
        $target_client=Clients::where('deleted_at',null)->pluck('client_name','id')->prepend('','');
        $worker_source=[
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
        $internal_recruitment_manager=[
            '0'=>'Unassigned',
            '1'=>'Recruitment Manager1',
            '2'=>'Recruitment Manager2',
            '3'=>'Recruitment Manager3'
        ];
        $internal_other_employee['']='';
        $AC=AccountManagers::where('deleted_at',null)->get();
        foreach ($AC as $row) {
            $internal_other_employee[$row->fullname]=$row->fullname;
        }
        $AM=Admins::where('deleted_at',null)->get();
        foreach ($AM as $row) {
            $internal_other_employee[$row->fullname]=$row->fullname;
        }
        $PM=PayrollManagers::where('deleted_at',null)->get();
        foreach ($PM as $row) {
            $internal_other_employee[$row->fullname]=$row->fullname;
        }
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
            ''=>'', 
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
            ''=>'',
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
            ''=>'', 
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
        $clients=$worker->activeClients();


        $pto_summaries=array();
        $y=date('y',strtotime(\Carbon\Carbon::now()));
        $Year=date('Y',strtotime(Carbon::now())).'-%';
        foreach ($clients as $client) {
            $info=$client->assigned_worker_info()->where('workers_id',$worker->id)->first();
            if(!$info) continue;
            $currentyear=date('Y',strtotime(Carbon::now()));
            $hiredyear=date('Y',strtotime($info->hired_at));
            if ($currentyear==$hiredyear){
                $pto_days_worker_default=$info->ptodays_current_calendar;
            }else{
                $pto_days_worker_default=$info->ptodays_full_calendar;
            }

            if ($client->pto_infomation!='yes') continue;

            $pto_summary['title']='20'.$y.' -  PTO Summary: '.$client->client_name;
            $pto_timesheets=\App\TimeSheets::where(function($q) {
                $q->where('pto_time_hours', '>', 0)->orwhere('pto_time_hours_updated', '>', 0);
            })->where('clients_id', $client->id)->where('workers_id', $worker->id)->where('date', 'like', $Year)->get();
            $pto_used = array();
            $pto_sum = 0;
            foreach ($pto_timesheets as $pto_timesheet) {
                if ($pto_timesheet->status=='approved' && $pto_timesheet->pto_time_hours>0 && $pto_timesheet->pto_time_hours_updated===null) {
                    $pto_used[]=[
                        'date' => $pto_timesheet->date,
                        'hours' => $pto_timesheet->pto_time_hours
                    ];
                    $pto_sum+=$pto_timesheet->pto_time_hours;
                }
                if ($pto_timesheet->pto_time_hours_updated>0) {
                    $pto_used[]=[
                        'date' => $pto_timesheet->date,
                        'hours' => $pto_timesheet->pto_time_hours_updated
                    ];
                    $pto_sum+=$pto_timesheet->pto_time_hours_updated;
                }
            }
            $pto_remain=$pto_days_worker_default*8-$pto_sum;
            $pto_remaining=intval($pto_remain/8).' Days, '.($pto_remain-intval($pto_remain/8)*8).' Hours';
            $pto_summary['pto_used']=$pto_used;
            $pto_summary['pto_remaining']=$pto_remaining;
            $pto_summaries[]=$pto_summary;
        }

        $global_values=Globals::first()->values;
        if (unserialize($global_values)){
            foreach (unserialize($global_values) as $other){
                if (strnatcasecmp($other['fieldname'],'Skill Name')==0){
                    $skill_name=array();
                    $skill_name['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $skill_name[trim($value)]=trim($value);
                    }
                }

                if (strnatcasecmp($other['fieldname'],'Industry')==0){
                    $skill_industry=array();
                    $skill_industry['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $skill_industry[trim($value)]=trim($value);
                    }
                    $software_industry=$skill_industry;
                }

                if (strnatcasecmp($other['fieldname'],'Software Name')==0){
                    $software_name=array();
                    $software_name['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $software_name[trim($value)]=trim($value);
                    }
                }
                if (strnatcasecmp($other['fieldname'],'Primary Connection - ISP')==0){
                    $internet_connection_primary=array();
                    $internet_connection_primary['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $internet_connection_primary[trim($value)]=trim($value);
                    }
                }
                
            }    
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

        return view('admin.worker.profile',$data);
    }

    public function update(Request $request)
    {
        $data=$request->all();

        $worker=Workers::where('id',$request->worker_id)->first();


        if (count(User::where('id','!=',$worker->user_id)->where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('admin.worker.profile',$worker->id);
        }
        if (count(Workers::where('id','!=',$worker->id)->where('email_veem',$data['email_veem'])->get())>0){
            Session::flash('message',"Veem email already exists on other worker. Please input unique veem email for this worker.");
            return redirect()->route('admin.worker.profile',$worker->id);
        }
        if (count(Workers::where('id','!=',$worker->id)->where('email_main',$data['email_main'])->get())>0){
            Session::flash('message',"Main email already exists on other worker. Please input unique main email for this worker.");
            return redirect()->route('admin.worker.profile',$worker->id);
        }

        $worker->update($data);
        $worker->fullname=$data['first_name'].' '.$data['last_name'];
        $user=$worker->user();
        $user->name=$data['first_name'].' '.$data['last_name'];
        $user->email=$data['user_email'];
        //$user->password=bcrypt($data['password']);
        $user->save();
        
        $other_document_files=array();

        if (isset($data['other_document_file_label']) && unserialize($worker->other_document_files)) {
            $oldIds = [];
            foreach ($data['other_document_file_label'] as $key => $value) {
                $oldIds[] = $key;
            }
            foreach (unserialize($worker->other_document_files) as $odf) {
                if (in_array($odf['id'], $oldIds) && isset($data['other_document_file_label'][$odf['id']])) {
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
        $worker->target_client='';
        if(isset($data['target_client'])){
            $target_clients=array();
            foreach ($data['target_client'] as $key => $value) {
                $target_clients[]=$value;
            }
            $worker->target_client=implode(",",array_unique($target_clients));
            $worker->save();
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


        $worker->updated_at=date('Y-m-d H:i:s');
        $worker->save();
        $note_ids=array();
        if(isset($data['candiate_notes_origin'])){
            foreach ($data['candiate_notes_origin'] as $key => $value) {
                $note_ids[]=$key;
                $special_note=SpecialNotes::where('id',$key)->first();
                if($special_note->admins_id!=Auth::user()->admin()->id) continue;
                if ($special_note->note==$value) continue;
                $special_note->note=$value;
                $special_note->date=date('Y-m-d',strtotime(Carbon::now()));
                $special_note->save();
            }
        }
        $special_notes=$worker->specialCandiateNotes()->whereNotIn('id',$note_ids)->get();
        foreach ($special_notes as $special_note) {
            $special_note->delete();
        }

        if(isset($data['candiate_notes'])){
            foreach ($data['candiate_notes'] as $key => $value) {
                $special_note=SpecialNotes::create();
                $special_note->workers_id=$worker->id;
                $special_note->admins_id=Auth::user()->admin()->id;
                $special_note->inputbyname=Auth::user()->admin()->fullname;
                $special_note->inputby='admin';
                $special_note->note=$value;
                $special_note->date=date('Y-m-d',strtotime(Carbon::now()));

                $special_note->save();
            }
        }
        $this->updateCandidateData($worker);
        if ($request['video_file']!=null && $request['video_file']!="" ) {
            $f = $request->file('video_file');
            $uploading=$this->uploadVideoFile($worker, $f);
            $generation=$this->generateVideoLink($worker);
            if ($generation!='success'){
                Session::flash('message',"Worker: ".$worker->fullname()."'s profile updated Successfully. But could not generate video link because ".$generation);
                return redirect()->route('admin.worker.profile',$worker->id);
            }
        } else {
            if ($worker->video_file){
                $generation=$this->generateVideoLink($worker);
                if ($generation!='success'){
                    Session::flash('message',"Worker: ".$worker->fullname()."'s profile updated Successfully. But could not generate video link because ".$generation);
                    return redirect()->route('admin.worker.profile',$worker->id);
                }
            }
        }
        $worker->save();
        $this->updateCandidateData($worker);
        Session::flash('message',"Worker: ".$worker->fullname()."'s profile updated Successfully.");
        return redirect()->route('admin.worker.profile',$worker->id);

    }

    public function updateCandidateData($worker)
    {
        $arrayWorker =  json_decode(json_encode($worker), true);
        $candidates = $worker->candidates();
        foreach ($candidates as $candidate) {
            $candidate->email_address=$worker->email_main;
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


    public function search()
    {
        $status_list=[
            ''=>'',
            'All Available Candidates' => 'All Available Candidates',
                'new_candidate'=>'&nbsp;&nbsp;&nbsp;&nbsp;Candidate - New',
                'pre_candidate'=>'&nbsp;&nbsp;&nbsp;&nbsp;Candidate - Previous Hire (See Notes)',
                'available_hired_candidate'=>'&nbsp;&nbsp;&nbsp;&nbsp;Active - Available',
            // 'All Hired Workers' => 'All Hired Workers',
            //     'available_hired'=>'&nbsp;&nbsp;&nbsp;&nbsp;Hired - Available',
            //     'not_available_hired'=>'&nbsp;&nbsp;&nbsp;&nbsp;Hired - Not Available',
            'not_available_see_notes'=>'Not Available (See Notes)',
            'disqualfied'=>'Disqualified (See Notes)',
        ];
        $status=[
            ''=>'',
            'new_candidate'=>'Candidate - New',
            'available_hired_candidate'=>'Active - Available',
            'disqualfied'=>'Disqualified (See Notes)',
            'pre_candidate'=>'Candidate - Previous Hire (See Notes)',
            'available_hired'=>'Hired - Available',
            'not_available_see_notes'=>'Not Available (See Notes)',
            'not_available_hired'=>'Hired - Not Available'
        ];
        $country=[
            ''=>'',
            'Mexico'=>'Mexico',
            'Nicaragua'=>'Nicaragua',
            'Philippines'=>'Philippines',
            'USA'=>'USA'
        ];
        $candidate_account_manager_id=AccountManagers::where('deleted_at',null)->get()->pluck('fullname','id')->prepend('','');
        $gender=[
            ''=>'',
            'Male'=>'Male',
            'Female'=>'Female'
        ];
        $fulltime_compensation_currency=[
            ''=>'',
            'mxn'=>'MXN',
            'php'=>'PHP',
            'usd'=>'USD'
        ];
        $available_hours=[
            ''=>'',
            '0'=>'0',
            '10'=>'10',
            '20'=>'20',
            '30'=>'30',
            '40'=>'40',            
        ];
        $target_client=Clients::where('deleted_at',null)->pluck('client_name','id')->prepend('','');
        $worker_source=[
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
        $internal_recruitment_manager=[
            '0'=>'Unassigned',
            '1'=>'Recruitment Manager1',
            '2'=>'Recruitment Manager2',
            '3'=>'Recruitment Manager3'
        ];
        $english_verbal=[
            ''=>'',
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
            ''=>'',
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
            ''=>'',
            'Banking'=>'Banking',
            'High Tech'=>'High Tech',
            'Real Estate'=>'Real Estate',
            'Telecom'=>'Telecom',            
            'Other'=>'Other',
        ];
        $years=[
            ''=>'',
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
            ''=>'',
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
        $software_name=[
            ''=>'',
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

        $workers=Workers::where('deleted_at',null);


        if (session()->has('worker_search.status')){
            if (session('worker_search.status')!=''){
                if (session('worker_search.status')=='All Available Candidates') {
                    $workers=$workers->whereIn('status',['new_candidate', 'pre_candidate', 'available_hired_candidate', 'available_hired']);
                } else if (session('worker_search.status')=='All Hired Workers') {
                    $workers=$workers->whereIn('status',['available_hired', 'not_available_hired']);
                } else if (session('worker_search.status')=='available_hired' || session('worker_search.status')=='available_hired_candidate') {
                    $workers=$workers->whereIn('status',['available_hired', 'available_hired_candidate']);
                } else {
                    $workers=$workers->where('status',session('worker_search.status'));
                }
            }
        }

        if (session()->has('worker_search.country')){
            if (session('worker_search.country')!=''){
                $workers=$workers->where('country',session('worker_search.country'));
            }
        }
        if (session()->has('worker_search.gender')){
            if (session('worker_search.gender')!=''){
                $workers=$workers->where('gender',session('worker_search.gender'));
            }
        }
        if (session()->has('worker_search.candidate_account_manager_id')){
            if (session('worker_search.candidate_account_manager_id')!=''){
                $workers=$workers->where('candidate_account_manager_id',session('worker_search.candidate_account_manager_id'));
            }
        }

        if (session()->has('worker_search.legal_name')){
            if (session('worker_search.legal_name')!=''){
                $workers=$workers->where('legal_name','like','%'.session('worker_search.legal_name').'%');
            }
        }

        if (session()->has('worker_search.first_name')){
            if (session('worker_search.first_name')!=''){
                $workers=$workers->where('first_name','like','%'.session('worker_search.first_name').'%');
            }
        }
        if (session()->has('worker_search.last_name')){
            if (session('worker_search.last_name')!=''){
                $workers=$workers->where('last_name','like','%'.session('worker_search.last_name').'%');
            }
        }
        if (session()->has('worker_search.email_main')){
            if (session('worker_search.email_main')!=''){
                $workers=$workers->where('email_main','like','%'.session('worker_search.email_main').'%');
            }
        }
        if (session()->has('worker_search.email_veem')){
            if (session('worker_search.email_veem')!=''){
                $workers=$workers->where('email_veem','like','%'.session('worker_search.email_veem').'%');
            }
        }
        if (session()->has('worker_search.phone')){
            if (session('worker_search.phone')!=''){
                $workers=$workers->where('phone','like','%'.session('worker_search.phone').'%');
            }
        }
        if (session()->has('worker_search.skype')){
            if (session('worker_search.skype')!=''){
                $workers=$workers->where('skype','like','%'.session('worker_search.skype').'%');
            }
        }
        if (session()->has('worker_search.address')){
            if (session('worker_search.address')!=''){
                $workers=$workers->where('address1','like','%'.session('worker_search.address').'%')->orwhere('city','like','%'.session('worker_search.address').'%');
            }
        }
        if (session()->has('worker_search.birthday')){
            if (session('worker_search.birthday')!=''){
                $workers=$workers->where('birthday','like','%'.session('worker_search.birthday').'%');
            }
        }
        
        if (session()->has('worker_search.available_start_date')){
            if (session('worker_search.available_start_date')!=''){
                $workers=$workers->where('available_start_date','like','%'.session('worker_search.available_start_date').'%');
            }
        }
        if (session()->has('worker_search.outside_brightdrop')){
            if (session('worker_search.outside_brightdrop')!=''){
                $workers=$workers->where('outside_brightdrop','like','%'.session('worker_search.outside_brightdrop').'%');
            }
        }
        if (session()->has('worker_search.home_based_experience')){
            if (session('worker_search.home_based_experience')!=''){
                $workers=$workers->where('home_based_experience','like','%'.session('worker_search.home_based_experience').'%');
            }
        }
        if (session()->has('worker_search.available_hours')){
            if (session('worker_search.available_hours')!=''){
                $workers=$workers->where('available_hours','like','%'.session('worker_search.available_hours').'%');
            }
        }
        if (session()->has('worker_search.long_term_work_issues')){
            if (session('worker_search.long_term_work_issues')!=''){
                $workers=$workers->where('long_term_work_issues','like','%'.session('worker_search.long_term_work_issues').'%');
            }
        }
        if (session()->has('worker_search.us_business_hours')){
            if (session('worker_search.us_business_hours')!=''){
                $workers=$workers->where('us_business_hours','like','%'.session('worker_search.us_business_hours').'%');
            }
        }
        if (session()->has('worker_search.reliable_quiet_workspace')){
            if (session('worker_search.reliable_quiet_workspace')!=''){
                $workers=$workers->where('reliable_quiet_workspace','like','%'.session('worker_search.reliable_quiet_workspace').'%');
            }
        }
        if (session()->has('worker_search.fulltime_compensation_amount')){
            if (session('worker_search.fulltime_compensation_amount')!=''){
                $workers=$workers->where('fulltime_compensation_amount','like','%'.session('worker_search.fulltime_compensation_amount').'%');
            }
        }
        if (session()->has('worker_search.speaks_spanish')){
            if (session('worker_search.speaks_spanish')!=''){
                $workers=$workers->where('speaks_spanish','like','%'.session('worker_search.speaks_spanish').'%');
            }
        }
        if (session()->has('worker_search.temp_video_link')){
            if (session('worker_search.temp_video_link')!=''){
                $workers=$workers->where('temp_video_link','like','%'.session('worker_search.temp_video_link').'%');
            }
        }
        if (session()->has('worker_search.typing_test_number_of_errors')){
            if (session('worker_search.typing_test_number_of_errors')!=''){
                $workers=$workers->where('typing_test_number_of_errors','like','%'.session('worker_search.typing_test_number_of_errors').'%');
            }
        }
        if (session()->has('worker_search.typing_test_wpm')){
            if (session('worker_search.typing_test_wpm')!=''){
                $workers=$workers->where('typing_test_wpm','like','%'.session('worker_search.typing_test_wpm').'%');
            }
        }
        if (session()->has('worker_search.worker_source')){
            if (session('worker_search.worker_source')!=''){
                $workers=$workers->where('worker_source','like','%'.session('worker_search.worker_source').'%');
            }
        }
        
        $workers=$workers->orderBy('last_name')->get();

        session(['hired_workers' => null]);
        session(['candidate_workers' => $workers]);

        $data=[
            'country'=>$country,
            'status'=>$status,
            'status_list'=>$status_list,
            'candidate_account_manager_id'=>$candidate_account_manager_id,
            'gender'=>$gender,
            'fulltime_compensation_currency'=>$fulltime_compensation_currency,
            'available_hours'=>$available_hours,
            'target_client'=>$target_client,
            
            'worker_source'=>$worker_source,
            'internal_recruitment_manager'=>$internal_recruitment_manager,
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
            'workers'=>$workers,
            'worker_emails' => count($workers) ? implode(',', $workers->pluck('email_main')->toArray()) : '',
            'worker_ids' => count($workers) ? implode(',', $workers->pluck('id')->toArray()) : ''
        ];
        return view('admin.worker.search',$data);
    }

    public function searchHired()
    {
        $status_list=[
            'active'=>'Active',
            'inactive'=>'Inactive',
            ''=>'All Workers',
        ];
        $status=[
            ''=>'',
            'new_candidate'=>'Candidate - New',
            'available_hired_candidate'=>'Active - Available',
            'disqualfied'=>'Disqualified (See Notes)',
            'pre_candidate'=>'Candidate - Previous Hire (See Notes)',
            'available_hired'=>'Hired - Available',
            'not_available_see_notes'=>'Not Available (See Notes)',
            'not_available_hired'=>'Hired - Not Available'
        ];
        $country=[
            ''=>'',
            'Mexico'=>'Mexico',
            'Nicaragua'=>'Nicaragua',
            'Philippines'=>'Philippines',
            'USA'=>'USA'
        ];
        $account_manager_id=AccountManagers::where('deleted_at',null)->get()->pluck('fullname','id')->prepend('All Account Managers','');
        $account_manager = AccountManagers::where('id', session('worker_search_hired.account_manager_id'))->first();
        $clients_id = $account_manager ? $account_manager->clients()->pluck('client_name','id')->prepend('All Clients','') : Clients::where('deleted_at',null)->get()->pluck('client_name','id')->prepend('All Clients','');

        $clients = $account_manager ? $account_manager->clients() : Clients::where('deleted_at',null);
        if (session('worker_search_hired.clients_id')) {
            $clients = $clients->where('id', session('worker_search_hired.clients_id'));
        }
        $clients = $clients->get();

        $gender=[
            ''=>'',
            'Male'=>'Male',
            'Female'=>'Female'
        ];
        $fulltime_compensation_currency=[
            ''=>'',
            'mxn'=>'MXN',
            'php'=>'PHP',
            'usd'=>'USD'
        ];
        $available_hours=[
            ''=>'',
            '0'=>'0',
            '10'=>'10',
            '20'=>'20',
            '30'=>'30',
            '40'=>'40',            
        ];
        $target_client=Clients::where('deleted_at',null)->pluck('client_name','id')->prepend('','');
        $worker_source=[
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
        $internal_recruitment_manager=[
            '0'=>'Unassigned',
            '1'=>'Recruitment Manager1',
            '2'=>'Recruitment Manager2',
            '3'=>'Recruitment Manager3'
        ];
        $english_verbal=[
            ''=>'',
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
            ''=>'',
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
            ''=>'',
            'Banking'=>'Banking',
            'High Tech'=>'High Tech',
            'Real Estate'=>'Real Estate',
            'Telecom'=>'Telecom',            
            'Other'=>'Other',
        ];
        $years=[
            ''=>'',
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
            ''=>'',
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
        $software_name=[
            ''=>'',
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

        $result = [];
        $worker_emails = [];
        $worker_ids = [];
        foreach ($clients as $client) {
            $search = [
                'status' => session('worker_search_hired.status'),
                'target_hours_week' => session('worker_search_hired.target_hours_week'),
                'client_billable_rate_regular' => session('worker_search_hired.client_billable_rate_regular'),
                'client_billable_rate_overtime' => session('worker_search_hired.client_billable_rate_overtime'),
                'worker_pay_houly_rate_regular' => session('worker_search_hired.worker_pay_houly_rate_regular'),
                'worker_pay_houly_rate_overtime' => session('worker_search_hired.worker_pay_houly_rate_overtime'),
                'ptodays_full_calendar' => session('worker_search_hired.ptodays_full_calendar'),
                'ptodays_current_calendar' => session('worker_search_hired.ptodays_current_calendar'),
                'worker_pto_hourly_rate' => session('worker_search_hired.worker_pto_hourly_rate'),
                'worker_holiday_hourly_rate' => session('worker_search_hired.worker_holiday_hourly_rate')
            ];
            $workers=$client->assignedWorkersObject($search);

            if (session()->has('worker_search_hired.country')){
                if (session('worker_search_hired.country')!=''){
                    $workers=$workers->where('country',session('worker_search_hired.country'));
                }
            }
            if (session()->has('worker_search_hired.gender')){
                if (session('worker_search_hired.gender')!=''){
                    $workers=$workers->where('gender',session('worker_search_hired.gender'));
                }
            }
            
            if (session()->has('worker_search_hired.fulltime_compensation_currency')){
                if (session('worker_search_hired.fulltime_compensation_currency')!=''){
                    $workers=$workers->where('fulltime_compensation_currency',session('worker_search_hired.fulltime_compensation_currency'));
                }
            }
            if (session()->has('worker_search_hired.available_hours')){
                if (session('worker_search_hired.available_hours')!=''){
                    $workers=$workers->where('available_hours',session('worker_search_hired.available_hours'));
                }
            }
            if (session()->has('worker_search_hired.target_client')){
                if (session('worker_search_hired.target_client')!=''){
                    $workers=$workers->where('target_client','like','%'.session('worker_search_hired.target_client').'%');
                }
            }

            if (session()->has('worker_search_hired.available_start_date')){
                if (session('worker_search_hired.available_start_date')!=''){
                    $workers=$workers->where('available_start_date',session('worker_search_hired.available_start_date'));
                }
            }

            if (session()->has('worker_search_hired.legal_name')){
                if (session('worker_search_hired.legal_name')!=''){
                    $workers=$workers->where('legal_name','like','%'.session('worker_search_hired.legal_name').'%');
                }
            }

            if (session()->has('worker_search_hired.first_name')){
                if (session('worker_search_hired.first_name')!=''){
                    $workers=$workers->where('first_name','like','%'.session('worker_search_hired.first_name').'%');
                }
            }
            if (session()->has('worker_search_hired.last_name')){
                if (session('worker_search_hired.last_name')!=''){
                    $workers=$workers->where('last_name','like','%'.session('worker_search_hired.last_name').'%');
                }
            }
            if (session()->has('worker_search_hired.email_main')){
                if (session('worker_search_hired.email_main')!=''){
                    $workers=$workers->where('email_main','like','%'.session('worker_search_hired.email_main').'%');
                }
            }
            if (session()->has('worker_search_hired.email_veem')){
                if (session('worker_search_hired.email_veem')!=''){
                    $workers=$workers->where('email_veem','like','%'.session('worker_search_hired.email_veem').'%');
                }
            }
            if (session()->has('worker_search_hired.phone')){
                if (session('worker_search_hired.phone')!=''){
                    $workers=$workers->where('phone','like','%'.session('worker_search_hired.phone').'%');
                }
            }
            if (session()->has('worker_search_hired.skype')){
                if (session('worker_search_hired.skype')!=''){
                    $workers=$workers->where('skype','like','%'.session('worker_search_hired.skype').'%');
                }
            }
            if (session()->has('worker_search_hired.address')){
                if (session('worker_search_hired.address')!=''){
                    $workers=$workers->where('address1','like','%'.session('worker_search_hired.address').'%')->orwhere('city','like','%'.session('worker_search_hired.address').'%');
                }
            }
            if (session()->has('worker_search_hired.birthday')){
                if (session('worker_search_hired.birthday')!=''){
                    $workers=$workers->where('birthday','like','%'.session('worker_search_hired.birthday').'%');
                }
            }
            if (session()->has('worker_search_hired.fulltime_compensation_amount')){
                if (session('worker_search_hired.fulltime_compensation_amount')!=''){
                    $workers=$workers->where('fulltime_compensation_amount','like','%'.session('worker_search_hired.fulltime_compensation_amount').'%');
                }
            }

            
            
            if (session()->has('worker_search_hired.worker_hourly_rate_currency_type')){
                if (session('worker_search_hired.worker_hourly_rate_currency_type')!=''){
                    $workers=$workers->where('currency_type','like','%'.session('worker_search_hired.worker_hourly_rate_currency_type').'%');
                }
            }
            if (session()->has('worker_search_hired.worker_pto_currency_type')){
                if (session('worker_search_hired.worker_pto_currency_type')!=''){
                    $workers=$workers->where('currency_type','like','%'.session('worker_search_hired.worker_pto_currency_type').'%');
                }
            }
            if (session()->has('worker_search_hired.worker_holiday_currency_type')){
                if (session('worker_search_hired.worker_holiday_currency_type')!=''){
                    $workers=$workers->where('currency_type','like','%'.session('worker_search_hired.worker_holiday_currency_type').'%');
                }
            }
            
        
            $workers=$workers->orderBy('last_name')->get();
            $worker_email = $workers->pluck('email_main')->toArray();
            $worker_emails = array_merge( $worker_emails, $worker_email );
            $worker_id = $workers->pluck('id')->toArray();
            $worker_ids = array_merge( $worker_ids, $worker_id );
            foreach($workers as $worker) {
                $worker->client_name = $client->client_name;
                $account=$client->accountmanager();
                $worker->account_manager_name = $account ? $account->full_name : '';
                $workerInfo = $client->assignedWorkerObject($worker);
                $worker->worker_status = $workerInfo ? $workerInfo->status : null;
                $worker->target_hours_week = $workerInfo ? $workerInfo->target_hours_week : null;
                $worker->client_billable_rate_regular = $workerInfo ? $workerInfo->client_billable_rate_regular : null;
                $worker->client_billable_rate_overtime = $workerInfo ? $workerInfo->client_billable_rate_overtime : null;
                $worker->client_billable_currency_type = 'USD';
                $worker->worker_pay_houly_rate_regular = $workerInfo ? $workerInfo->worker_pay_houly_rate_regular : null;
                $worker->worker_pay_houly_rate_overtime = $workerInfo ? $workerInfo->worker_pay_houly_rate_overtime : null;
                $worker->worker_hourly_rate_currency_type = $worker->currency_type;
                $worker->ptodays_full_calendar = $workerInfo ? $workerInfo->ptodays_full_calendar : null;
                $worker->ptodays_current_calendar = $workerInfo ? $workerInfo->ptodays_current_calendar : null;
                $worker->worker_pto_hourly_rate = $workerInfo ? $workerInfo->worker_pto_hourly_rate : null;
                $worker->worker_pto_currency_type = $worker->currency_type;
                $worker->worker_holiday_hourly_rate = $workerInfo ? $workerInfo->worker_holiday_hourly_rate : null;
                $worker->worker_holiday_currency_type =$worker->currency_type;

                $result[] = $worker;
            }
        }
        session(['hired_workers' => $result]);
        session(['candidate_workers' => null]);

        $data=[
            'country'=>$country,
            'status'=>$status,
            'status_list'=>$status_list,
            'account_manager_id'=>$account_manager_id,
            'clients_id'=>$clients_id,
            'gender'=>$gender,
            'fulltime_compensation_currency'=>$fulltime_compensation_currency,
            'available_hours'=>$available_hours,
            'target_client'=>$target_client,
            
            'worker_source'=>$worker_source,
            'internal_recruitment_manager'=>$internal_recruitment_manager,
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
            'workers'=>$result,
            'worker_emails' => count($worker_emails) ? implode(',', $worker_emails) : '',
            'worker_ids' => count($worker_ids) ? implode(',', $worker_ids) : ''
        ];
        return view('admin.worker.searchHired',$data);
    }

    public function removeWorker(Request $request)
    {
        $worker=Workers::where('id',$request->worker_id)->first();
        $worker->user()->delete();
        $worker->delete();
        Session::flash('message',"The worker removed.");
        return redirect()->route('admin.worker.search');
    }

    

    public function setfilter(Request $request)
    {
        session(['worker_search.first_name'=>$request->first_name]);
        session(['worker_search.last_name'=>$request->last_name]);
        session(['worker_search.email_main'=>$request->email_main]);
        session(['worker_search.status'=>$request->status]);
        session(['worker_search.country'=>$request->country]);
        session(['worker_search.candidate_account_manager_id'=>$request->candidate_account_manager_id]);
        
        session(['worker_search.currency_type'=>$request->currency_type]);
        session(['worker_search.birthday'=>$request->birthday]);
        session(['worker_search.email_veem'=>$request->email_veem]);
        session(['worker_search.legal_name'=>$request->legal_name]);
        session(['worker_search.gender'=>$request->gender]);
        session(['worker_search.phone'=>$request->phone]);
        session(['worker_search.address'=>$request->address]);
        session(['worker_search.skype'=>$request->skype]);

        session(['worker_search.available_start_date'=>$request->available_start_date]);
        session(['worker_search.outside_brightdrop'=>$request->outside_brightdrop]);
        session(['worker_search.home_based_experience'=>$request->home_based_experience]);
        session(['worker_search.available_hours'=>$request->available_hours]);
        session(['worker_search.long_term_work_issues'=>$request->long_term_work_issues]);
        session(['worker_search.us_business_hours'=>$request->us_business_hours]);
        session(['worker_search.reliable_quiet_workspace'=>$request->reliable_quiet_workspace]);
        session(['worker_search.fulltime_compensation_amount'=>$request->fulltime_compensation_amount]);
        session(['worker_search.speaks_spanish'=>$request->speaks_spanish]);
        session(['worker_search.temp_video_link'=>$request->temp_video_link]);
        session(['worker_search.typing_test_number_of_errors'=>$request->typing_test_number_of_errors]);
        session(['worker_search.typing_test_wpm'=>$request->typing_test_wpm]);
        session(['worker_search.worker_source'=>$request->worker_source]);


        session(['worker_search.currency_type_checkbox'=>$request->currency_type_checkbox]);
        session(['worker_search.birthday_checkbox'=>$request->birthday_checkbox]);
        session(['worker_search.email_veem_checkbox'=>$request->email_veem_checkbox]);
        session(['worker_search.legal_name_checkbox'=>$request->legal_name_checkbox]);
        session(['worker_search.gender_checkbox'=>$request->gender_checkbox]);
        session(['worker_search.phone_checkbox'=>$request->phone_checkbox]);
        session(['worker_search.address_checkbox'=>$request->address_checkbox]);
        session(['worker_search.skype_checkbox'=>$request->skype_checkbox]);

        session(['worker_search.available_start_date_checkbox'=>$request->available_start_date_checkbox]);
        session(['worker_search.outside_brightdrop_checkbox'=>$request->outside_brightdrop_checkbox]);
        session(['worker_search.home_based_experience_checkbox'=>$request->home_based_experience_checkbox]);
        session(['worker_search.available_hours_checkbox'=>$request->available_hours_checkbox]);
        session(['worker_search.long_term_work_issues_checkbox'=>$request->long_term_work_issues_checkbox]);
        session(['worker_search.us_business_hours_checkbox'=>$request->us_business_hours_checkbox]);
        session(['worker_search.reliable_quiet_workspace_checkbox'=>$request->reliable_quiet_workspace_checkbox]);
        session(['worker_search.fulltime_compensation_amount_checkbox'=>$request->fulltime_compensation_amount_checkbox]);
        session(['worker_search.speaks_spanish_checkbox'=>$request->speaks_spanish_checkbox]);
        session(['worker_search.temp_video_link_checkbox'=>$request->temp_video_link_checkbox]);
        session(['worker_search.typing_test_number_of_errors_checkbox'=>$request->typing_test_number_of_errors_checkbox]);
        session(['worker_search.typing_test_wpm_checkbox'=>$request->typing_test_wpm_checkbox]);
        session(['worker_search.worker_source_checkbox'=>$request->worker_source_checkbox]);
        
        return redirect()->route('admin.worker.search');
    }
    public function resetfilter(Request $request)
    {
        session()->forget('worker_search');
        return redirect()->route('admin.worker.search');
    }

    public function setfilterHired(Request $request)
    {
        session(['worker_search_hired.status'=>$request->status]);
        session(['worker_search_hired.country'=>$request->country]);
        session(['worker_search_hired.gender'=>$request->gender]);
        session(['worker_search_hired.account_manager_id'=>$request->account_manager_id]);
        session(['worker_search_hired.clients_id'=>$request->clients_id]);
        session(['worker_search_hired.fulltime_compensation_currency'=>$request->fulltime_compensation_currency]);
        session(['worker_search_hired.available_hours'=>$request->available_hours]);
        session(['worker_search_hired.target_client'=>$request->target_client]);
        session(['worker_search_hired.available_start_date'=>$request->available_start_date]);
        session(['worker_search_hired.worker_source'=>$request->worker_source]);

        session(['worker_search_hired.first_name'=>$request->first_name]);
        session(['worker_search_hired.last_name'=>$request->last_name]);
        session(['worker_search_hired.email_main'=>$request->email_main]);
        
        session(['worker_search_hired.currency_type'=>$request->currency_type]);
        session(['worker_search_hired.birthday'=>$request->birthday]);
        session(['worker_search_hired.email_veem'=>$request->email_veem]);
        session(['worker_search_hired.legal_name'=>$request->legal_name]);
        session(['worker_search_hired.gender'=>$request->gender]);
        session(['worker_search_hired.phone'=>$request->phone]);
        session(['worker_search_hired.address'=>$request->address]);
        session(['worker_search_hired.skype'=>$request->skype]);

        session(['worker_search_hired.target_hours_week'=>$request->target_hours_week]);
        session(['worker_search_hired.client_billable_rate_regular'=>$request->client_billable_rate_regular]);
        session(['worker_search_hired.client_billable_rate_overtime'=>$request->client_billable_rate_overtime]);
        session(['worker_search_hired.client_billable_currency_type'=>$request->client_billable_currency_type]);
        session(['worker_search_hired.worker_pay_houly_rate_regular'=>$request->worker_pay_houly_rate_regular]);
        session(['worker_search_hired.worker_pay_houly_rate_overtime'=>$request->worker_pay_houly_rate_overtime]);
        session(['worker_search_hired.worker_hourly_rate_currency_type'=>$request->worker_hourly_rate_currency_type]);
        session(['worker_search_hired.ptodays_full_calendar'=>$request->ptodays_full_calendar]);
        session(['worker_search_hired.ptodays_current_calendar'=>$request->ptodays_current_calendar]);
        session(['worker_search_hired.worker_pto_hourly_rate'=>$request->worker_pto_hourly_rate]);
        session(['worker_search_hired.worker_pto_currency_type'=>$request->worker_pto_currency_type]);
        session(['worker_search_hired.worker_holiday_hourly_rate'=>$request->worker_holiday_hourly_rate]);
        session(['worker_search_hired.worker_holiday_currency_type'=>$request->worker_holiday_currency_type]);


        session(['worker_search_hired.currency_type_checkbox'=>$request->currency_type_checkbox]);
        session(['worker_search_hired.birthday_checkbox'=>$request->birthday_checkbox]);
        session(['worker_search_hired.email_veem_checkbox'=>$request->email_veem_checkbox]);
        session(['worker_search_hired.legal_name_checkbox'=>$request->legal_name_checkbox]);
        session(['worker_search_hired.gender_checkbox'=>$request->gender_checkbox]);
        session(['worker_search_hired.phone_checkbox'=>$request->phone_checkbox]);
        session(['worker_search_hired.address_checkbox'=>$request->address_checkbox]);
        session(['worker_search_hired.skype_checkbox'=>$request->skype_checkbox]);

        session(['worker_search_hired.target_hours_week_checkbox'=>$request->target_hours_week_checkbox]);
        session(['worker_search_hired.client_billable_rate_regular_checkbox'=>$request->client_billable_rate_regular_checkbox]);
        session(['worker_search_hired.client_billable_rate_overtime_checkbox'=>$request->client_billable_rate_overtime_checkbox]);
        session(['worker_search_hired.client_billable_currency_type_checkbox'=>$request->client_billable_currency_type_checkbox]);
        session(['worker_search_hired.worker_pay_houly_rate_regular_checkbox'=>$request->worker_pay_houly_rate_regular_checkbox]);
        session(['worker_search_hired.worker_pay_houly_rate_overtime_checkbox'=>$request->worker_pay_houly_rate_overtime_checkbox]);
        session(['worker_search_hired.worker_hourly_rate_currency_type_checkbox'=>$request->worker_hourly_rate_currency_type_checkbox]);
        session(['worker_search_hired.ptodays_full_calendar_checkbox'=>$request->ptodays_full_calendar_checkbox]);
        session(['worker_search_hired.ptodays_current_calendar_checkbox'=>$request->ptodays_current_calendar_checkbox]);
        session(['worker_search_hired.worker_pto_hourly_rate_checkbox'=>$request->worker_pto_hourly_rate_checkbox]);
        session(['worker_search_hired.worker_pto_currency_type_checkbox'=>$request->worker_pto_currency_type_checkbox]);
        session(['worker_search_hired.worker_holiday_hourly_rate_checkbox'=>$request->worker_holiday_hourly_rate_checkbox]);
        session(['worker_search_hired.worker_holiday_currency_type_checkbox'=>$request->worker_holiday_currency_type_checkbox]);

        return redirect()->route('admin.worker.searchHired');
    }
    public function resetfilterHired(Request $request)
    {
        session()->forget('worker_search_hired');
        return redirect()->route('admin.worker.searchHired');
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

    public function sendVideoProfile(Request $request)
    {
        $id=$request->id;
        $worker=Workers::where('id',$id)->first();
        $link=$worker->video_link;
        if (!$link){
            Session::flash('message',"Video Link was not populated yet. Please upload video file.");
            return redirect()->route('admin.worker.profile',$worker->id);
        }
        $data=$request->all();
        if (!isset($data['client_email'])){
            Session::flash('message',"No emails to send.");
            return redirect()->route('admin.worker.profile',$worker->id);
        }
        $mailto=array();
        foreach ($data['client_email'] as $key => $value) {
            $mailto[]= $value;
        }
        if (count($mailto)>0) Mail::to($mailto)->send(new WorkerVideoProfileClient($worker,$link));

        Session::flash('message',"Just sent ".$worker->fullname()."'s video profile to clients.");
        return redirect()->route('admin.worker.profile',$worker->id);
    }


    public function sendResetPasswordLink(Request $request)
    {
        $id=$request->id;
        $worker=Workers::where('id',$id)->first();
        $worker->user()->notify(new SendLinkResetPassword());

        Session::flash('message',"Just sent password reset link to ".$worker->fullname()." over email.");
        return redirect()->route('admin.worker.profile',$worker->id);
    }







    public function downloadSummaries($id)
    {
        $payment=Payments::where('id',$id)->first();
        $contents = Storage::get($payment->payment_summary_report_file);
        $response = Response::make($contents, '200',[
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="payment_sumary.pdf"',
            ]);
       
        return $response;
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
                return redirect()->route('admin.worker.profile',$id);
            };
            $contents = Storage::get($filename);
        }else{
            if(!Storage::disk('public_uploads')->exists($filename)) {
                Session::flash('message',"No Uploaded File.");
                return redirect()->route('admin.worker.profile',$id);
            };
            $contents = Storage::disk('public_uploads')->get($filename);
        }
        $response = Response::make($contents, '200',[
            'Content-Disposition' => 'attachment; filename="'.$file.'"',
            ]);
       
        return $response;
    }



    public function special_worker(Request $request){
        $id=$request->id;
        $worker=Workers::where('id',$id)->first();
        return response()->json($worker);
    }

    public function download_CSV_Data(Request $request)
    {
        $hired_workers = session('hired_workers');
        $candidate_workers = session('candidate_workers');
        $result = $hired_workers ? $hired_workers : $candidate_workers;
        $account_managers=AccountManagers::get()->pluck('fullname','id')->toArray();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=worker-data.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $columns = [
            'status',
            'last_name',
            'first_name',
            'email_main',
            'country',
            'candidate_account_manager'
        ];
        if ($hired_workers) {
            $columns = [
                'worker_status',
                'account_manager_name',
                'client_name',
                'last_name',
                'first_name',
                'email_main',
                'country'
            ];
            if(session('worker_search_hired.currency_type_checkbox')) {
                $columns[] = 'currency_type';
            }
            if(session('worker_search_hired.birthday_checkbox')) {
                $columns[] = 'birthday';
            }
            if(session('worker_search_hired.email_veem_checkbox')) {
                $columns[] = 'email_veem';
            }
            if(session('worker_search_hired.legal_name_checkbox')) {
                $columns[] = 'legal_name';
            }
            if(session('worker_search_hired.gender_checkbox')) {
                $columns[] = 'gender';
            }
            if(session('worker_search_hired.phone_checkbox')) {
                $columns[] = 'phone';
            }
            if(session('worker_search_hired.address_checkbox')) {
                $columns[] = 'address';
            }
            if(session('worker_search_hired.skype_checkbox')) {
                $columns[] = 'skype';
            }


            if(session('worker_search_hired.target_hours_week_checkbox')) {
                $columns[] = 'target_hours_week';
            }
            if(session('worker_search_hired.client_billable_rate_regular_checkbox')) {
                $columns[] = 'client_billable_rate_regular';
            }
            if(session('worker_search_hired.client_billable_rate_overtime_checkbox')) {
                $columns[] = 'client_billable_rate_overtime';
            }
            if(session('worker_search_hired.client_billable_currency_type_checkbox')) {
                $columns[] = 'client_billable_currency_type';
            }
            if(session('worker_search_hired.worker_pay_houly_rate_regular_checkbox')) {
                $columns[] = 'worker_pay_houly_rate_regular';
            }
            if(session('worker_search_hired.worker_pay_houly_rate_overtime_checkbox')) {
                $columns[] = 'worker_pay_houly_rate_overtime';
            }
            if(session('worker_search_hired.worker_hourly_rate_currency_type_checkbox')) {
                $columns[] = 'worker_hourly_rate_currency_type';
            }
            if(session('worker_search_hired.ptodays_full_calendar_checkbox')) {
                $columns[] = 'ptodays_full_calendar';
            }
            if(session('worker_search_hired.ptodays_current_calendar_checkbox')) {
                $columns[] = 'ptodays_current_calendar';
            }
            if(session('worker_search_hired.worker_pto_hourly_rate_checkbox')) {
                $columns[] = 'worker_pto_hourly_rate';
            }
            if(session('worker_search_hired.worker_pto_currency_type_checkbox')) {
                $columns[] = 'worker_pto_currency_type';
            }
            if(session('worker_search_hired.worker_holiday_hourly_rate_checkbox')) {
                $columns[] = 'worker_holiday_hourly_rate';
            }
            if(session('worker_search_hired.worker_holiday_currency_type_checkbox')) {
                $columns[] = 'worker_holiday_currency_type';
            }
        } else if ($candidate_workers) {
            if(session('worker_search.currency_type_checkbox')) {
                $columns[] = 'currency_type';
            }
            if(session('worker_search.birthday_checkbox')) {
                $columns[] = 'birthday';
            }
            if(session('worker_search.email_veem_checkbox')) {
                $columns[] = 'email_veem';
            }
            if(session('worker_search.legal_name_checkbox')) {
                $columns[] = 'legal_name';
            }
            if(session('worker_search.gender_checkbox')) {
                $columns[] = 'gender';
            }
            if(session('worker_search.phone_checkbox')) {
                $columns[] = 'phone';
            }
            if(session('worker_search.address_checkbox')) {
                $columns[] = 'address';
            }
            if(session('worker_search.skype_checkbox')) {
                $columns[] = 'skype';
            }
            if(session('worker_search.available_start_date_checkbox')) {
                $columns[] = 'available_start_date';
            }
            if(session('worker_search.outside_brightdrop_checkbox')) {
                $columns[] = 'outside_brightdrop';
            }
            if(session('worker_search.home_based_experience_checkbox')) {
                $columns[] = 'home_based_experience';
            }
            if(session('worker_search.available_hours_checkbox')) {
                $columns[] = 'available_hours';
            }
            if(session('worker_search.long_term_work_issues_checkbox')) {
                $columns[] = 'long_term_work_issues';
            }
            if(session('worker_search.us_business_hours_checkbox')) {
                $columns[] = 'us_business_hours';
            }
            if(session('worker_search.reliable_quiet_workspace_checkbox')) {
                $columns[] = 'reliable_quiet_workspace';
            }
            if(session('worker_search.fulltime_compensation_amount_checkbox')) {
                $columns[] = 'fulltime_compensation_amount';
            }
            if(session('worker_search.speaks_spanish_checkbox')) {
                $columns[] = 'speaks_spanish';
            }
            if(session('worker_search.temp_video_link_checkbox')) {
                $columns[] = 'temp_video_link';
            }
            if(session('worker_search.typing_test_number_of_errors_checkbox')) {
                $columns[] = 'typing_test_number_of_errors';
            }
            if(session('worker_search.typing_test_wpm_checkbox')) {
                $columns[] = 'typing_test_wpm';
            }
            if(session('worker_search.worker_source_checkbox')) {
                $columns[] = 'worker_source';
            }
        }

        $callback = function() use ($result, $columns, $account_managers)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($result as $row) {
                $rowArray = $row->toArray();
                $workerData = [];
                foreach($columns as $col) {
                    if ($col == 'candidate_account_manager') {
                        $account_manager_id = $rowArray['candidate_account_manager_id'];
                        $colData = isset($account_managers[$account_manager_id]) ? $account_managers[$account_manager_id] : '';
                        $workerData[] = $colData;
                        continue;
                    }
                    $colData = $rowArray[$col];
                    if ($col == 'skills') {
                        $colArray = unserialize($colData);
                        $colData = '';
                        if ($colArray) {
                            foreach ($colArray as $skill) {
                                $name = $skill['skill_name'];
                                $industry = $skill['skill_industry'];
                                $years = $skill['skill_years'];
                                $months = $skill['skill_months'];
                                $colData .= "Name: $name Industry: $industry Years: $years Months: $months ; ";
                            }
                        }
                    }
                    if ($col == 'software_knowledge') {
                        $colArray = unserialize($colData);
                        $colData = '';
                        if ($colArray) {
                            foreach ($colArray as $software) {
                                $name = $software['software_name'];
                                $industry = $software['software_industry'];
                                $years = $software['software_years'];
                                $months = $software['software_months'];
                                $colData .= "Name: $name Industry: $industry Years: $years Months: $months ; ";
                            }
                        }
                    }
                    $workerData[] = $colData;
                }
                fputcsv($file, $workerData);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
 
}
