<?php

namespace App\Http\Controllers\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;




class AccountManagerController extends Controller
{
     
    public function createAdjustmentOneTime()
    {
        return view('account.AccountManager_createAdjustmentOneTime');
    }
    public function createAdjustmentRecurring()
    {
        return view('account.AccountManager_createAdjustmentRecurring');
    }

 
    
}
