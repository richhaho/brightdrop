<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Workers;
use App\Clients;
use App\AccountManagers;
use App\Positions;
use App\Candidates;
use App\Groups;
use App\Globals;
use Carbon\Carbon;
use Auth;


class PositionController extends Controller
{
    public function create()
    {
        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
        $visible_to_client=[
            ''=>'',
            'yes'=>'Yes',
            'no'=>'No',
        ];
        $editable_to_client = $visible_to_client;
        $client_list=Clients::where('deleted_at',null)->orderBy('client_name')->get()->pluck('client_name','id')->prepend('', '')->toArray();

        $groups = [
            [
                'id' => uniqid(),
                'name' => 'Primary Candidates',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_delete' => 0
            ]
        ];

        $columns = [
            [
                'id' => 1,
                'name' => 'Name',
                'field' => 'name',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 0,
                'can_visible_to_client' => 1,
                'can_editable_to_client' => 0,
                'can_delete' => 0,
                'field_type' => 'dropdown',
                'drop_down_options' => '',
                'description' => ''
            ],
            [
                'id' => 2,
                'name' => 'Worker Profile',
                'field' => 'worker_profile',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 1,
                'can_visible_to_client' => 0,
                'can_editable_to_client' => 0,
                'can_delete' => 0,
                'field_type' => 'hyperlink',
                'drop_down_options' => '',
                'description' => ''
            ],
            [
                'id' => 3,
                'name' => 'Email Address',
                'field' => 'email_address',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 1,
                'can_visible_to_client' => 0,
                'can_editable_to_client' => 0,
                'can_delete' => 0,
                'field_type' => 'readonly',
                'drop_down_options' => '',
                'description' => ''
            ],
            [
                'id' => 4,
                'name' => 'Video Profile',
                'field' => 'video_profile',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 1,
                'can_visible_to_client' => 1,
                'can_editable_to_client' => 0,
                'can_delete' => 0,
                'field_type' => 'hyperlink',
                'drop_down_options' => '',
                'description' => ''
            ],
            [
                'id' => 5,
                'name' => 'Country',
                'field' => 'country',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 1,
                'can_visible_to_client' => 1,
                'can_editable_to_client' => 0,
                'can_delete' => 0,
                'field_type' => 'readonly',
                'drop_down_options' => '',
                'description' => ''
            ],
            [
                'id' => 6,
                'name' => 'Requested Pay',
                'field' => 'requested_pay',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 1,
                'can_visible_to_client' => 0,
                'can_editable_to_client' => 0,
                'can_delete' => 0,
                'field_type' => 'readonly',
                'drop_down_options' => '',
                'description' => ''
            ],
            // [
            //     'id' => 7,
            //     'name' => 'Worker - Monthly Rate',
            //     'field' => 'worker_monthly_rate',
            //     'visible_to_brightdrop' => 1,
            //     'visible_to_client' => 0,
            //     'editable_to_client' => 0,
            //     'can_visible_to_brightdrop' => 1,
            //     'can_visible_to_client' => 0,
            //     'can_editable_to_client' => 0,
            //     'can_delete' => 0,
            //     'field_type' => 'text',
            //     'drop_down_options' => '',
            //     'description' => ''
            // ],
            // [
            //     'id' => 8,
            //     'name' => 'Worker - Hourly Rate',
            //     'field' => 'worker_hourly_rate',
            //     'visible_to_brightdrop' => 1,
            //     'visible_to_client' => 0,
            //     'editable_to_client' => 0,
            //     'can_visible_to_brightdrop' => 1,
            //     'can_visible_to_client' => 0,
            //     'can_editable_to_client' => 0,
            //     'can_delete' => 0,
            //     'field_type' => 'text',
            //     'drop_down_options' => '',
            //     'description' => ''
            // ],
            // [
            //     'id' => 9,
            //     'name' => 'Worker - Currency Type',
            //     'field' => 'worker_currency_type',
            //     'visible_to_brightdrop' => 1,
            //     'visible_to_client' => 0,
            //     'editable_to_client' => 0,
            //     'can_visible_to_brightdrop' => 1,
            //     'can_visible_to_client' => 0,
            //     'can_editable_to_client' => 0,
            //     'can_delete' => 0,
            //     'field_type' => 'readonly',
            //     'drop_down_options' => '',
            //     'description' => ''
            // ],
            [
                'id' => 10,
                'name' => 'Billable Rate (USD)',
                'field' => 'client_hourly_rate',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 1,
                'can_visible_to_client' => 1,
                'can_editable_to_client' => 0,
                'can_delete' => 0,
                'field_type' => 'text',
                'drop_down_options' => '',
                'description' => ''
            ],
            [
                'id' => 11,
                'name' => 'Available Start Date',
                'field' => 'available_start_date',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 1,
                'can_visible_to_client' => 1,
                'can_editable_to_client' => 0,
                'can_delete' => 0,
                'field_type' => 'readonly',
                'drop_down_options' => '',
                'description' => ''
            ],
            [
                'id' => 12,
                'name' => 'Worker Source',
                'field' => 'worker_source',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 1,
                'can_visible_to_client' => 0,
                'can_editable_to_client' => 0,
                'can_delete' => 0,
                'field_type' => 'readonly',
                'drop_down_options' => '',
                'description' => ''
            ],
            [
                'id' => 13,
                'name' => 'Internal Notes',
                'field' => 'internal_notes',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 1,
                'can_visible_to_client' => 0,
                'can_editable_to_client' => 0,
                'can_delete' => 0,
                'field_type' => 'text',
                'drop_down_options' => '',
                'description' => ''
            ],
            [
                'id' => 14,
                'name' => 'Shared Notes',
                'field' => 'shared_notes',
                'visible_to_brightdrop' => 1,
                'visible_to_client' => 0,
                'editable_to_client' => 0,
                'can_visible_to_brightdrop' => 1,
                'can_visible_to_client' => 1,
                'can_editable_to_client' => 1,
                'can_delete' => 0,
                'field_type' => 'text',
                'drop_down_options' => '',
                'description' => ''
            ]            
        ];

        $field_types = [
            'dropdown' => 'Drop-down Menu',
            // 'hyperlink' => 'Hyperlink',
            'readonly' => 'Database field',
            'text' => 'Text Field'
        ];

        $data=[
            'status'=>$status,
            'visible_to_client'=>$visible_to_client,
            'editable_to_client'=>$editable_to_client,
            'client_list'=>$client_list,
            'groups'=>$groups,
            'columns'=>$columns,
            'field_types'=>$field_types
        ];

        return view('admin.position.create',$data);
    }
    public function store(Request $request)
    { 
        $data=$request->all();
        $position=Positions::create($data);
        if(isset($data['column_name'])){
            $columns=array();
            $column_id=0;
            foreach ($data['column_name'] as $key => $value) {
                $column_id++;
                $column_row['id']=$column_id;
                $column_row['name']=$request->column_name[$key];
                $field = strtolower($request->column_name[$key]);
                for ($i=32;$i<48;$i++) {
                    $field = str_replace(chr($i), '_', $field);
                }
                for ($i=58;$i<65;$i++) {
                    $field = str_replace(chr($i), '_', $field);
                }
                for ($i=91;$i<97;$i++) {
                    $field = str_replace(chr($i), '_', $field);
                }
                for ($i=123;$i<128;$i++) {
                    $field = str_replace(chr($i), '_', $field);
                }
                
                $column_row['field']=isset($request->field[$key]) ? $request->field[$key] : $field;
                $column_row['visible_to_brightdrop']=isset($request->column_visible_to_brightdrop[$key]) ? 1 : 0;
                $column_row['visible_to_client']=isset($request->column_visible_to_client[$key]) ? 1 : 0;
                $column_row['editable_to_client']=isset($request->column_editable_to_client[$key]) ? 1 : 0;
                $column_row['can_visible_to_brightdrop']=$request->can_visible_to_brightdrop[$key] == '1' ? 1 : 0;
                $column_row['can_visible_to_client']=$request->can_visible_to_client[$key]=='1' ? 1 : 0;
                $column_row['can_editable_to_client']=$request->can_editable_to_client[$key]=='1' ? 1 : 0;
                $column_row['can_delete']=$request->can_delete[$key]=='1' ? 1 : 0;
                $column_row['field_type']=$request->field_type[$key];
                $column_row['drop_down_options']=$request->drop_down_options[$key];
                $column_row['description']=isset($request->description[$key]) ? $request->description[$key] : '';
                
                $columns[]=$column_row;
            }
            $columns=json_encode($columns);
            $position->columns=$columns;
            $position->save();
        }
        if(isset($data['group_name'])){
            foreach ($data['group_name'] as $key => $value) {
                $groupData = [
                    'positions_id' => $position->id,
                    'name' => $request->group_name[$key],
                    'visible_to_brightdrop' => isset($request->group_visible_to_brightdrop[$key]) ? 1 : 0,
                    'visible_to_client' => isset($request->group_visible_to_client[$key]) ? 1 : 0,
                    'editable_to_client' => isset($request->group_editable_to_client[$key]) ? 1 : 0,
                    'can_delete' => $request->group_can_delete[$key] =='1' ? 1 : 0
                ];
                Groups::create($groupData);
            }
        }

        $position->updated_at=date('Y-m-d H:i:s');
        $position->save();
        
        Session::flash('message',"New Position: ".$position->name." was created successfully.");
        return redirect()->route('admin.position.edit',$position->id);

    }
    public function update($id, Request $request)
    {
        $position=Positions::where('id',$id)->first();
        $data=$request->all();
        $position->update($data);

        if(isset($data['column_name'])){
            $columns=array();
            $column_id=0;
            foreach ($data['column_name'] as $key => $value) {
                $column_id++;
                $column_row['id']=$column_id;
                $column_row['name']=$request->column_name[$key];
                $field = strtolower($request->column_name[$key]);
                for ($i=32;$i<48;$i++) {
                    $field = str_replace(chr($i), '_', $field);
                }
                for ($i=58;$i<65;$i++) {
                    $field = str_replace(chr($i), '_', $field);
                }
                for ($i=91;$i<97;$i++) {
                    $field = str_replace(chr($i), '_', $field);
                }
                for ($i=123;$i<128;$i++) {
                    $field = str_replace(chr($i), '_', $field);
                }
                $column_row['field']=isset($request->field[$key]) ? $request->field[$key] : $field;
                $column_row['visible_to_brightdrop']=isset($request->column_visible_to_brightdrop[$key]) ? 1 : 0;
                $column_row['visible_to_client']=isset($request->column_visible_to_client[$key]) ? 1 : 0;
                $column_row['editable_to_client']=isset($request->column_editable_to_client[$key]) ? 1 : 0;
                $column_row['can_visible_to_brightdrop']=$request->can_visible_to_brightdrop[$key] == '1' ? 1 : 0;
                $column_row['can_visible_to_client']=$request->can_visible_to_client[$key]=='1' ? 1 : 0;
                $column_row['can_editable_to_client']=$request->can_editable_to_client[$key]=='1' ? 1 : 0;
                $column_row['can_delete']=$request->can_delete[$key] =='1' ? 1 : 0;
                $column_row['field_type']=$request->field_type[$key];
                $column_row['drop_down_options']=$request->drop_down_options[$key];
                $column_row['description']=isset($request->description[$key]) ? $request->description[$key] : '';
                
                $columns[]=$column_row;
            }
            $columns=json_encode($columns);
            $position->columns=$columns;
            $position->save();
        }
        if(isset($data['group_name'])){
            foreach($position->groups() as $group) {
                $group->delete();
            }
            foreach ($data['group_name'] as $key => $value) {
                $groupData = [
                    'positions_id' => $position->id,
                    'name' => $request->group_name[$key],
                    'visible_to_brightdrop' => isset($request->group_visible_to_brightdrop[$key]) ? 1 : 0,
                    'visible_to_client' => isset($request->group_visible_to_client[$key]) ? 1 : 0,
                    'editable_to_client' => isset($request->group_editable_to_client[$key]) ? 1 : 0,
                    'can_delete' => $request->group_can_delete[$key] == '1' ? 1 : 0
                ];
                $newGroup = Groups::create($groupData);
                if (isset($request->group_id[$key])) {
                    $groupId = $request->group_id[$key];
                    $candidates = Candidates::where('groups_id', $groupId)->get();
                    foreach($candidates as $candidate) {
                        $candidate->groups_id = $newGroup->id;
                        $candidate->save();
                    }
                }
            }
        }

        $position->updated_at=date('Y-m-d H:i:s');
        $position->save();
        $this->updateCandidates($position);
        
        Session::flash('message',"Position: ".$position->name." was updated successfully.");
        return redirect()->route('admin.position.edit',$position->id);
    }

    public function updateCandidates($position)
    {
        $columns = $position->columns();
        foreach ($position->groups() as $group) {
            foreach($group->candidates() as $candidate) {
                $worker = $candidate->worker();
                if (!$worker) continue;
                $arrayWorker =  json_decode(json_encode($worker), true);
                $others=json_decode($candidate->other_columns, true);
                $other_columns=[];
                $others = $others ? $others : [];
                $readonlyColumns = [];
                $normalColumns = [
                    'name',
                    'worker_profile',
                    'email_address',
                    'video_profile',
                    'country',
                    'requested_pay',
                    'client_hourly_rate',
                    'available_start_date',
                    'worker_source',
                    'internal_notes',
                    'shared_notes'
                ];
                foreach ($columns as $col) {
                    if ($col['field_type'] == 'readonly') {
                        $readonlyColumns[$col['field']] = $col['drop_down_options'];
                        if (!in_array($col['field'], $normalColumns)) {
                            $others[$col['field']] = '';
                        }
                    }
                }
                foreach ($others as $field => $val) {
                    if (isset($readonlyColumns[$field])) {
                        $other_columns[$field]=$arrayWorker[$readonlyColumns[$field]];
                    } else {
                        $other_columns[$field]=$val;
                    }
                }
                
                if (count($other_columns)>0) {
                    $candidate->other_columns=json_encode($other_columns);
                    $candidate->save();
                    $candidate;
                }
            }
        }
    }
    
    public function edit($id, Request $request)
    {
        $position=Positions::where('id',$id)->first();
        
        if (count($position)==0){
            Session::flash('message',"This position does not exist.");
            return redirect()->route('admin.position.open');
        }
        $position->updated_at=date('Y-m-d H:i:s');
        $position->save();

        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
        $visible_to_client=[
            ''=>'',
            'yes'=>'Yes',
            'no'=>'No',
        ];
        $editable_to_client = $visible_to_client;
        $client_list=Clients::where('deleted_at',null)->orderBy('client_name')->get()->pluck('client_name','id')->prepend('', '')->toArray();

        $groups = [];
        foreach ($position->groups() as $group) {
            $groups[] = [
                'id' => $group->id,
                'name' => $group->name,
                'visible_to_brightdrop' => $group->visible_to_brightdrop,
                'visible_to_client' => $group->visible_to_client,
                'editable_to_client' => $group->editable_to_client,
                'can_delete' => $group->can_delete
            ];
        }
        $columns = $position->columns();

        $field_types = [
            'dropdown' => 'Drop-down Menu',
            // 'hyperlink' => 'Hyperlink',
            'readonly' => 'Database field',
            'text' => 'Text Field'
        ];

        $data=[
            'position' => $position,
            'status'=>$status,
            'visible_to_client'=>$visible_to_client,
            'editable_to_client'=>$editable_to_client,
            'client_list'=>$client_list,
            'groups'=>$groups,
            'columns'=>$columns,
            'field_types'=>$field_types,
            'from' => $request->from
        ];
        return view('admin.position.edit',$data);
    }

    public function copy($id)
    {
        $position=Positions::where('id',$id)->first();
        $newPosition = $position->replicate();
        $newPosition->name = $position->name. '-copied-'. date('Y-m-d');
        $newPosition->save();
        foreach($position->groups() as $group) {
            $newGroup = $group->replicate();
            $newGroup->positions_id = $newPosition->id;
            $newGroup->save();
        }
        
        Session::flash('message',"Position: ".$position->name." was copied successfully.");
        return redirect()->route('admin.position.edit', $newPosition->id);
    }

    public function open()
    {
        $accountManagers=AccountManagers::where('deleted_at', null)->orderBy('last_name', 'ASC')->get();
        $workers=Workers::where('deleted_at', null)->whereIn('status', ['new_candidate','pre_candidate','available_hired'])->orderBy('first_name', 'ASC')->get();
        $search_client=Clients::where('deleted_at',null)->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('All',0);
        $search_account=$accountManagers->pluck('full_name','id')->prepend('Unassigned Clients','unassigned')->prepend('All',0);
        $data=[
            'search_client' => $search_client,
            'search_account' => $search_account,
            'accountManagers'=>$accountManagers,
            'workers'=>json_encode($workers),
            'workerList'=>$workers->pluck('full_name', 'id')->prepend('','')->toArray(),
            'unassignedWorkerList'=>$workers->where('candidate_account_manager_id', null)->pluck('full_name', 'id')->prepend('','')->toArray()
        ];

        return view('admin.position.open',$data);
    }

    public function past()
    {
        $accountManagers=AccountManagers::where('deleted_at', null)->orderBy('last_name', 'ASC')->get();
        $workers=Workers::where('deleted_at', null)->whereIn('status', ['new_candidate','pre_candidate','available_hired'])->orderBy('first_name', 'ASC')->get();
        $search_client=Clients::where('deleted_at',null)->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('All',0);
        $search_account=$accountManagers->pluck('full_name','id')->prepend('All',0);
        $data=[
            'search_client' => $search_client,
            'search_account' => $search_account,
            'accountManagers'=>$accountManagers,
            'workers'=>json_encode($workers),
            'workerList'=>$workers->pluck('full_name', 'id')->prepend('','')->toArray()
        ];

        return view('admin.position.past',$data);
    }

    public function removePosition(Request $request)
    {
        $position=Positions::where('id',$request->position_id)->first();
        $position->delete();
        Session::flash('message',"Position removed.");
        return redirect()->route('admin.position.open');
    }

    public function setfilter(Request $request)
    {
        return redirect()->route('admin.position.open');
    }
    public function resetfilter(Request $request)
    {
        session()->forget('position_search');
        return redirect()->route('admin.position.open');
    }
 
}
