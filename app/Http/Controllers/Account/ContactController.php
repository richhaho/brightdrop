<?php

namespace App\Http\Controllers\Account;

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
use App\HolidaySchedule;
use App\HolidayDefault;
use Carbon\Carbon;
use Auth;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendLinkResetPassword;

class ContactController extends Controller
{
    public function create()
    {
        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
        $country=[
            'US'=>'United States',
            'Other'=>'Other',
        ];
        $same_as_client=[
            'yes'=>'Yes',
            'no'=>'No',
        ];
        $clients_list=Auth::user()->accountmanager()->clients()->get()->pluck('client_name','id');
 
        $data=[
            'status'=>$status,
            'country'=>$country,
            'same_as_client'=>$same_as_client,
            'clients_list'=>$clients_list,
        ];

        return view('account.contact.create',$data);
    }
    public function store(Request $request)
    { 
        $data=$request->all();

        if (count(User::where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('account.contact.create');
        }
        if (count(Contacts::where('email',$data['email'])->get())>0){
            Session::flash('message',"Contact email already exists on other Contact. Please input unique email for new Contact.");
            return redirect()->route('account.worker.create');
        }

        $contact=Contacts::create();
        //$contact->clients_id=$data['clients_list'];
        $contact->account_managers_id=Auth::user()->accountmanager()->id;
        $contact->admins_id=Auth::user()->accountmanager()->admin()->id;
        $contact->status=$data['status'];
        $contact->first_name=$data['first_name'];
        $contact->last_name=$data['last_name'];
        $contact->fullname=$data['first_name'].' '.$data['last_name'];
        $contact->email=$data['email'];
        $contact->phone=$data['phone'];
        $contact->same_as_client=$data['same_as_client'];
        
        if ($data['country']=="US"){
            $contact->address1=$data['address1'];
            $contact->address2=$data['address2'];
            $contact->city=$data['city'];
            $contact->state=$data['state'];
            $contact->zip=$data['zip'];
        }else{
            $contact->country_other=$data['country_other'];
            $contact->full_address_other=$data['full_address_other'];
        }
         
        $contact->updated_at=date('Y-m-d H:i:s');
        $contact->save();

        $user= User::create([
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['user_email'],
            'password' => bcrypt($data['password']),
        ]);
        $default_role= Role::where('name', 'Contact')->first();
        $user->attachRole($default_role);
        $contact->user_id=$user->id;
        $contact->save();

        Session::flash('message',"New Contact created successfully.");
        return redirect()->route('account.contact.create');

    }
    public function profile($id)
    {
        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
        $country=[
            'US'=>'United States',
            'Other'=>'Other',
        ];
        $same_as_client=[
            'yes'=>'Yes',
            'no'=>'No',
        ];
        
        $contact=Contacts::where('id',$id)->first();
        $client=$contact->client();
        $contact->updated_at=date('Y-m-d H:i:s');
        $contact->save();
 
        $data=[
            'status'=>$status,
            'country'=>$country,
            'same_as_client'=>$same_as_client,
            'client'=>$client,
            'contact'=>$contact,
        ];
        return view('account.contact.profile',$data);
    }

    public function update(Request $request)
    { 
        $data=$request->all();

        $contact=Contacts::where('id',$data['contact_id'])->first();

        if (count(User::where('id','!=',$contact->user_id)->where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('account.contact.profile',$contact->id);
        }
        if (count(Contacts::where('id','!=',$contact->id)->where('email',$data['email'])->get())>0){
            Session::flash('message',"Contact email already exists on other Contact. Please input unique email for this Contact.");
            return redirect()->route('account.contact.profile',$contact->id);
        }
        //$contact->clients_id=$data['clients_list'];
        $contact->account_managers_id=Auth::user()->accountmanager()->id;
        $contact->admins_id=Auth::user()->accountmanager()->admin()->id;
        $contact->status=$data['status'];
        $contact->first_name=$data['first_name'];
        $contact->last_name=$data['last_name'];
        $contact->fullname=$data['first_name'].' '.$data['last_name'];
        $contact->email=$data['email'];
        $contact->phone=$data['phone'];
        $contact->same_as_client=$data['same_as_client'];
        
        $client=$contact->client();
        
        if ($data['same_as_client']=='yes' && count($client)>0){
            
                $contact->country=$client->country;
                $contact->address1=$client->address1;
                $contact->address2=$client->address2;
                $contact->city=$client->city;
                $contact->state=$client->state;
                $contact->zip=$client->zip;
                $contact->country_other=$client->country_other;
                $contact->full_address_other=$client->address_foreign;
            
        }else{
            $contact->country=$data['country'];
            if ($data['country']=="US"){
                $contact->address1=$data['address1'];
                $contact->address2=$data['address2'];
                $contact->city=$data['city'];
                $contact->state=$data['state'];
                $contact->zip=$data['zip'];
            }else{
                $contact->country_other=$data['country_other'];
                $contact->full_address_other=$data['full_address_other'];
            }
        }
        
        $contact->updated_at=date('Y-m-d H:i:s');
        $contact->save();
        $user=$contact->user();
        $user->name=$data['first_name'].' '.$data['last_name'];
        $user->email=$data['user_email'];
        //$user->password=bcrypt($data['password']);
        $user->save();


        Session::flash('message',"Contact: ".$contact->fullname()."'s profile updated.");
        return redirect()->route('account.contact.profile',$contact->id);

    }

    public function search()
    {
        $status=[
            'all'=>'All',
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
        $clients_list=Clients::where('deleted_at',null)->get()->pluck('client_name','id')->prepend('All',0);

        $contacts=Auth::user()->accountmanager()->contacts()->where('deleted_at',null);
        if (session()->has('contact_search.status')){
            if (session('contact_search.status')!='all'){
                $contacts=$contacts->where('status',session('contact_search.status'));
            }
        }
        if (session()->has('contact_search.clients_id')){
            if (session('contact_search.clients_id')!='0'){
                $contacts=$contacts->where('clients_id',session('contact_search.clients_id'));
            }
        }
        if (session()->has('contact_search.first_name')){
            if (session('contact_search.first_name')!=''){
                $contacts=$contacts->where('first_name','like','%'.session('contact_search.first_name').'%');
            }
        }
        if (session()->has('contact_search.last_name')){
            if (session('contact_search.last_name')!=''){
                $contacts=$contacts->where('last_name','like','%'.session('contact_search.last_name').'%');
            }
        }
        if (session()->has('contact_search.email')){
            if (session('contact_search.email')!=''){
                $contacts=$contacts->where('email','like','%'.session('contact_search.email').'%');
            }
        }

        if (session()->has('contact_search.phone')){
            if (session('contact_search.phone')!=''){
                $contacts=$contacts->where('phone','like','%'.session('contact_search.phone').'%');
            }
        }
        if (session()->has('contact_search.country')){
            if (session('contact_search.country')!=''){
                $contacts=$contacts->where('country','like','%'.session('contact_search.country').'%');
            }
        }
        if (session()->has('contact_search.city')){
            if (session('contact_search.city')!=''){
                $contacts=$contacts->where('city','like','%'.session('contact_search.city').'%');
            }
        }
        if (session()->has('contact_search.state')){
            if (session('contact_search.state')!=''){
                $contacts=$contacts->where('state','like','%'.session('contact_search.state').'%');
            }
        }
        if (session()->has('contact_search.zip')){
            if (session('contact_search.zip')!=''){
                $contacts=$contacts->where('zip','like','%'.session('contact_search.zip').'%');
            }
        }

        if (session()->has('contact_search.address')){
            if (session('contact_search.address')!=''){
                $contacts=$contacts->where('address1','like','%'.session('contact_search.address').'%')->orwhere('address2','like','%'.session('contact_search.address').'%');
            }
        }
  
        $contacts=$contacts->orderBy('last_name')->get();
 
        $data=[
            'status'=>$status,
            'contacts'=>$contacts,
            'clients_list'=>$clients_list,
        ];

        return view('account.contact.search',$data);
    }
    public function setfilter(Request $request)
    {
        session(['contact_search.status'=>$request->status]);
        session(['contact_search.clients_id'=>$request->clients_list]);

        session(['contact_search.first_name'=>$request->first_name]);
        session(['contact_search.last_name'=>$request->last_name]);
        session(['contact_search.email'=>$request->email]);
        session(['contact_search.phone'=>$request->phone]);
        
        session(['contact_search.country'=>$request->country]);
        session(['contact_search.city'=>$request->city]);
        session(['contact_search.state'=>$request->state]);
        session(['contact_search.zip'=>$request->zip]);
        session(['contact_search.address'=>$request->address]);
        return redirect()->route('account.contact.search');
    }
    public function resetfilter(Request $request)
    {
        session()->forget('contact_search');
        return redirect()->route('account.contact.search');
    }

    public function removeContact(Request $request)
    {
        $contact=Contacts::where('id',$request->contact_id)->first();
        $contact->user()->delete();
        $contact->delete();
        Session::flash('message',"Contact removed.");
        return redirect()->route('account.contact.search');
    }




    public function special_contact(Request $request){
        $id=$request->id;
        $contact=Contacts::where('id',$id)->first();
        return response()->json($contact);
    }

    public function sendResetPasswordLink(Request $request)
    {
        $id=$request->id;
        $contact=Contacts::where('id',$id)->first();
        $contact->user()->notify(new SendLinkResetPassword());

        Session::flash('message',"Just sent password reset link to ".$contact->fullname." over email.");
        return redirect()->route('account.contact.profile',$id);
    }
 
}
