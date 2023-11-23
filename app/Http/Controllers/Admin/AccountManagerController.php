<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Role;
use App\User;
use App\Workers;
use App\Clients;
use App\AccountManagers;
use App\Contacts;
use App\Admin;
use App\HolidaySchedule;
use App\HolidayDefault;
use Carbon\Carbon;
use Auth;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendLinkResetPassword; 


class AccountManagerController extends Controller
{
    public function create()
    {
        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
 
        $data=[
            'status'=>$status,
        ];

        return view('admin.accountmanager.create',$data);
    }
    public function store(Request $request)
    { 
        $data=$request->all();
        if (count(User::where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('admin.accountmanager.create');
        }
        if (count(AccountManagers::where('email',$data['email'])->get())>0){
            Session::flash('message',"Account Manager email already exists on other Account Manager. Please input unique email for new Account Manager.");
            return redirect()->route('admin.accountmanager.create');
        }

        $accountmanager=AccountManagers::create();
        $accountmanager->admins_id=Auth::user()->admin()->id;
        $accountmanager->status=$data['status'];
        $accountmanager->first_name=$data['first_name'];
        $accountmanager->last_name=$data['last_name'];
        $accountmanager->fullname=$data['first_name'].' '.$data['last_name'];
        $accountmanager->email=$data['email'];
        $accountmanager->phone=$data['phone'];
        $accountmanager->address1=$data['address1'];
        $accountmanager->address2=$data['address2'];
        $accountmanager->city=$data['city'];
        $accountmanager->state=$data['state'];
        $accountmanager->zip=$data['zip'];
        $accountmanager->updated_at=date('Y-m-d H:i:s');
        $accountmanager->save();

        $user= User::create([
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['user_email'],
            'password' => bcrypt($data['password']),
        ]);
        $default_role= Role::where('name', 'Account')->first();
        $user->attachRole($default_role);
        $accountmanager->user_id=$user->id;
        $accountmanager->save();

        Session::flash('message',"New Account Manager created successfully.");
        return redirect()->route('admin.accountmanager.profile',$accountmanager->id);

    }
    public function profile($id)
    {
        $accountmanager=AccountManagers::where('id',$id)->first();
        $accountmanager->updated_at=date('Y-m-d H:i:s');
        $accountmanager->save();
        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
        $clients=$accountmanager->clients()->get();
        $worker_ids=array();
        foreach ($clients as $client) {
            $worker_id=$client->workers()->pluck('id')->toArray();
            $worker_ids=array_unique(array_merge($worker_ids,$worker_id));
        }
        $workers=Workers::whereIn('id',$worker_ids)->where('deleted_at',null)->get();

        $data=[
            'status'=>$status,
            'accountmanager'=>$accountmanager,
            'clients'=>$clients,
            'workers'=>$workers,
        ];
        return view('admin.accountmanager.profile',$data);
    }

    public function update(Request $request)
    { 
        $data=$request->all();
        $accountmanager=AccountManagers::where('id',$data['accountmanager_id'])->first();

        if (count(User::where('id','!=',$accountmanager->user_id)->where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('admin.accountmanager.profile',$accountmanager->id);
        }
        if (count(AccountManagers::where('id','!=',$accountmanager->id)->where('email',$data['email'])->get())>0){
            Session::flash('message',"Account Manager email already exists on other Account Manager. Please input unique email for this Account Manager.");
            return redirect()->route('admin.accountmanager.profile',$accountmanager->id);
        }

        $accountmanager->admins_id=Auth::user()->admin()->id;
        $accountmanager->status=$data['status'];
        $accountmanager->first_name=$data['first_name'];
        $accountmanager->last_name=$data['last_name'];
        $accountmanager->fullname=$data['first_name'].' '.$data['last_name'];
        $accountmanager->email=$data['email'];
        $accountmanager->phone=$data['phone'];
        $accountmanager->address1=$data['address1'];
        $accountmanager->address2=$data['address2'];
        $accountmanager->city=$data['city'];
        $accountmanager->state=$data['state'];
        $accountmanager->zip=$data['zip'];
        $accountmanager->updated_at=date('Y-m-d H:i:s');
        $accountmanager->save();
         
        $user=$accountmanager->user();
        $user->name=$data['first_name'].' '.$data['last_name'];
        $user->email=$data['user_email'];
        //$user->password=bcrypt($data['password']);
        $user->save();

        Session::flash('message',$accountmanager->fullname."'s profile updated.");
        return redirect()->route('admin.accountmanager.profile',$accountmanager->id);

    }

    public function search()
    {
        $status=[
            'all'=>'All',
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
        
        $accountmanagers=Auth::user()->admin()->accountmanagers()->where('deleted_at',null);
        if (session()->has('accountmanager_search.status')){
            if (session('accountmanager_search.status')!='all'){
                $accountmanagers=$accountmanagers->where('status',session('accountmanager_search.status'));
            }
        }
        
        if (session()->has('accountmanager_search.first_name')){
            if (session('accountmanager_search.first_name')!=''){
                $accountmanagers=$accountmanagers->where('first_name','like','%'.session('accountmanager_search.first_name').'%');
            }
        }
        if (session()->has('accountmanager_search.last_name')){
            if (session('accountmanager_search.last_name')!=''){
                $accountmanagers=$accountmanagers->where('last_name','like','%'.session('accountmanager_search.last_name').'%');
            }
        }
        if (session()->has('accountmanager_search.email')){
            if (session('accountmanager_search.email')!=''){
                $accountmanagers=$accountmanagers->where('email','like','%'.session('accountmanager_search.email').'%');
            }
        }

        if (session()->has('accountmanager_search.phone')){
            if (session('accountmanager_search.phone')!=''){
                $accountmanagers=$accountmanagers->where('phone','like','%'.session('accountmanager_search.phone').'%');
            }
        }
        
        if (session()->has('accountmanager_search.city')){
            if (session('accountmanager_search.city')!=''){
                $accountmanagers=$accountmanagers->where('city','like','%'.session('accountmanager_search.city').'%');
            }
        }
        if (session()->has('accountmanager_search.state')){
            if (session('accountmanager_search.state')!=''){
                $accountmanagers=$accountmanagers->where('state','like','%'.session('accountmanager_search.state').'%');
            }
        }
        if (session()->has('accountmanager_search.zip')){
            if (session('accountmanager_search.zip')!=''){
                $accountmanagers=$accountmanagers->where('zip','like','%'.session('accountmanager_search.zip').'%');
            }
        }

        if (session()->has('accountmanager_search.address1')){
            if (session('accountmanager_search.address1')!=''){
                $accountmanagers=$accountmanagers->where('address1','like','%'.session('accountmanager_search.address1').'%');
            }
        }
        if (session()->has('accountmanager_search.address2')){
            if (session('accountmanager_search.address2')!=''){
                $accountmanagers=$accountmanagers->where('address2','like','%'.session('accountmanager_search.address2').'%');
            }
        }
  
        $accountmanagers=$accountmanagers->orderBy('last_name')->get();
 
        $data=[
            'status'=>$status,
            'accountmanagers'=>$accountmanagers,
             
        ];

        return view('admin.accountmanager.search',$data);
    }
    public function setfilter(Request $request)
    {
        session(['accountmanager_search.status'=>$request->status]);
        session(['accountmanager_search.first_name'=>$request->first_name]);
        session(['accountmanager_search.last_name'=>$request->last_name]);
        session(['accountmanager_search.email'=>$request->email]);
        session(['accountmanager_search.phone'=>$request->phone]);
        session(['accountmanager_search.city'=>$request->city]);
        session(['accountmanager_search.state'=>$request->state]);
        session(['accountmanager_search.zip'=>$request->zip]);
        session(['accountmanager_search.address1'=>$request->address1]);
        session(['accountmanager_search.address2'=>$request->address2]);
        return redirect()->route('admin.accountmanager.search');
    }
    public function resetfilter(Request $request)
    {
        session()->forget('accountmanager_search');
        return redirect()->route('admin.accountmanager.search');
    }

    public function remove(Request $request)
    {
        $accountmanager=AccountManagers::where('id',$request->accountmanager_id)->first();
        $fullname=$accountmanager->fullname;
        $accountmanager->user()->delete();
        $accountmanager->delete();
        Session::flash('message',"Account Manager: ".$fullname." removed.");
        return redirect()->route('admin.accountmanager.search');
    }

    public function sendResetPasswordLink(Request $request)
    {
        $id=$request->id;
        $accountmanager=AccountManagers::where('id',$id)->first();
        $accountmanager->user()->notify(new SendLinkResetPassword());

        Session::flash('message',"Just sent password reset link to ".$accountmanager->fullname." over email.");
        return redirect()->route('admin.accountmanager.profile',$id);
    }
 
}
