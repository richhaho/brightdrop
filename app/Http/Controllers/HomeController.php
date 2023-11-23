<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;

use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index() 
    {
         
        if(Auth::user()->hasRole(['Worker'])) {
            Session::put('user_role','Worker');
            return redirect('/worker/logTime');
        }
         
        if(Auth::user()->hasRole(['Contact'])) {
            Session::put('user_role','Contact');
            return redirect('/contact/pastInvoices');
        }
        if(Auth::user()->hasRole(['Account'])) {
            Session::put('user_role','Account');
            return redirect('/accountManager/pendingWorkerApproval');
        }
        if(Auth::user()->hasRole(['Payroll'])) {
            Session::put('user_role','Payroll');
            return redirect('/payrollManager/immediatePayWU');
        }
        if(Auth::user()->hasRole(['Admin'])) {
            Session::put('user_role','Admin');
            return redirect('/admin/immediatePayWU');
        }
 
        
    }
    
}
