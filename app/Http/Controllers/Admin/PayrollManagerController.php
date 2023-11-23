<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\HolidayDefault;
use App\Admins;
use App\User;
use App\Role;
use App\PayrollManagers;
use App\Payments;
use App\Invoices;
use Auth;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendLinkResetPassword;

class PayrollManagerController extends Controller
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

        return view('admin.payroll.create',$data);
    }
    public function store(Request $request)
    { 
        $data=$request->all();

        if (count(User::where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('admin.payroll.create');
        }
        if (count(PayrollManagers::where('email',$data['email'])->get())>0){
            Session::flash('message',"Payroll Manager email already exists on other Payroll Manager. Please input unique email for new Payroll Manager.");
            return redirect()->route('admin.payroll.create');
        }

        $payroll=PayrollManagers::create();
        $payroll->admins_id=Auth::user()->admin()->id;
        $payroll->status=$data['status'];
        $payroll->first_name=$data['first_name'];
        $payroll->last_name=$data['last_name'];
        $payroll->fullname=$data['first_name'].' '.$data['last_name'];
        $payroll->email=$data['email'];
        $payroll->phone=$data['phone'];
        $payroll->address1=$data['address1'];
        $payroll->address2=$data['address2'];
        $payroll->city=$data['city'];
        $payroll->state=$data['state'];
        $payroll->zip=$data['zip'];
         
        $payroll->updated_at=date('Y-m-d H:i:s');
        $payroll->save();

        $user= User::create([
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['user_email'],
            'password' => bcrypt($data['password']),
        ]);
        $default_role= Role::where('name', 'Payroll')->first();
        $user->attachRole($default_role);
        $payroll->user_id=$user->id;
        $payroll->save();

        Session::flash('message',"New Payroll Manager: ".$payroll->fullname." created successfully.");
        return redirect()->route('admin.payroll.profile',$payroll->id);

    }
    public function profile($id)
    {
        $payroll=PayrollManagers::where('id',$id)->first();
        $payroll->updated_at=date('Y-m-d H:i:s');
        $payroll->save();
        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];

        $data=[
            'status'=>$status,
            'payroll'=>$payroll,
            
        ];
        return view('admin.payroll.profile',$data);
    }

    public function update(Request $request)
    { 
        $data=$request->all();
        $payroll=PayrollManagers::where('id',$data['payroll_id'])->first();

        if (count(User::where('id','!=',$payroll->user_id)->where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('admin.payroll.profile',$payroll->id);
        }
        if (count(PayrollManagers::where('id','!=',$payroll->id)->where('email',$data['email'])->get())>0){
            Session::flash('message',"Payroll Manager email already exists on other Payroll Manager. Please input unique email for this Payroll Manager.");
            return redirect()->route('admin.payroll.profile',$payroll->id);
        }

        $payroll->status=$data['status'];
        $payroll->first_name=$data['first_name'];
        $payroll->last_name=$data['last_name'];
        $payroll->fullname=$data['first_name'].' '.$data['last_name'];
        $payroll->email=$data['email'];
        $payroll->phone=$data['phone'];
        $payroll->address1=$data['address1'];
        $payroll->address2=$data['address2'];
        $payroll->city=$data['city'];
        $payroll->state=$data['state'];
        $payroll->zip=$data['zip'];
        $payroll->updated_at=date('Y-m-d H:i:s');
        $payroll->save();
         
        $user=$payroll->user();
        $user->name=$data['first_name'].' '.$data['last_name'];
        $user->email=$data['user_email'];
        //$user->password=bcrypt($data['password']);
        $user->save();

        Session::flash('message',"Payroll Manager: ".$payroll->fullname."'s profile updated.");
        return redirect()->route('admin.payroll.profile',$payroll->id);

    }

    public function search()
    {
        $status=[
            'all'=>'All',
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
        
        $payrolls=PayrollManagers::where('deleted_at',null);
        if (session()->has('payroll_search.status')){
            if (session('payroll_search.status')!='all'){
                $payrolls=$payrolls->where('status',session('payroll_search.status'));
            }
        }
        
        if (session()->has('payroll_search.first_name')){
            if (session('payroll_search.first_name')!=''){
                $payrolls=$payrolls->where('first_name','like','%'.session('payroll_search.first_name').'%');
            }
        }
        if (session()->has('payroll_search.last_name')){
            if (session('payroll_search.last_name')!=''){
                $payrolls=$payrolls->where('last_name','like','%'.session('payroll_search.last_name').'%');
            }
        }
        if (session()->has('payroll_search.email')){
            if (session('payroll_search.email')!=''){
                $payrolls=$payrolls->where('email','like','%'.session('payroll_search.email').'%');
            }
        }

        if (session()->has('payroll_search.phone')){
            if (session('payroll_search.phone')!=''){
                $payrolls=$payrolls->where('phone','like','%'.session('payroll_search.phone').'%');
            }
        }
        
        if (session()->has('payroll_search.city')){
            if (session('payroll_search.city')!=''){
                $payrolls=$payrolls->where('city','like','%'.session('payroll_search.city').'%');
            }
        }
        if (session()->has('payroll_search.state')){
            if (session('payroll_search.state')!=''){
                $payrolls=$payrolls->where('state','like','%'.session('payroll_search.state').'%');
            }
        }
        if (session()->has('payroll_search.zip')){
            if (session('payroll_search.zip')!=''){
                $payrolls=$payrolls->where('zip','like','%'.session('payroll_search.zip').'%');
            }
        }

        if (session()->has('payroll_search.address1')){
            if (session('payroll_search.address1')!=''){
                $payrolls=$payrolls->where('address1','like','%'.session('payroll_search.address1').'%');
            }
        }
        if (session()->has('payroll_search.address2')){
            if (session('payroll_search.address2')!=''){
                $payrolls=$payrolls->where('address2','like','%'.session('payroll_search.address2').'%');
            }
        }
  
        $payrolls=$payrolls->orderBy('last_name')->get();
 
        $data=[
            'status'=>$status,
            'payrolls'=>$payrolls,
             
        ];

        return view('admin.payroll.search',$data);
    }
    public function setfilter(Request $request)
    {
        session(['payroll_search.status'=>$request->status]);
        session(['payroll_search.first_name'=>$request->first_name]);
        session(['payroll_search.last_name'=>$request->last_name]);
        session(['payroll_search.email'=>$request->email]);
        session(['payroll_search.phone'=>$request->phone]);
        session(['payroll_search.city'=>$request->city]);
        session(['payroll_search.state'=>$request->state]);
        session(['payroll_search.zip'=>$request->zip]);
        session(['payroll_search.address1'=>$request->address1]);
        session(['payroll_search.address2'=>$request->address2]);
        return redirect()->route('admin.payroll.search');
    }
    public function resetfilter(Request $request)
    {
        session()->forget('payroll_search');
        return redirect()->route('admin.payroll.search');
    }

    public function remove(Request $request)
    {
        $payroll=PayrollManagers::where('id',$request->payroll_id)->first();
        $fullname=$payroll->fullname;
        $payroll->user()->delete();
        $payroll->delete();
        Session::flash('message',"Payroll Manager: ".$fullname." removed.");
        return redirect()->route('admin.payroll.search');
    }

    public function sendResetPasswordLink(Request $request)
    {
        $id=$request->id;
        $payroll=PayrollManagers::where('id',$id)->first();
        $payroll->user()->notify(new SendLinkResetPassword());

        Session::flash('message',"Just sent password reset link to ".$payroll->fullname." over email.");
        return redirect()->route('admin.payroll.profile',$id);
    }

    public function updateCommets(Request $request)
    {
        $type=$request->type;
        $pid=$request->pid;
        $comments=$request->comments;
        if($type=='payment'){
            $payment=Payments::where('id',$pid)->first();
            $payment->comments=$comments;
            $payment->save();   
        }else{
            $invoice=Invoices::where('id',$pid)->first();
            $invoice->comments=$comments;
            $invoice->save();   
        }
    }
    
}
