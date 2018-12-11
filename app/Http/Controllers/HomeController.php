<?php

/***************************************************
 ******* Developed By:- Anshul Agrawal *************
 ******* Email:- anshul.agrawal889@gmail.com *******
 ******* Phone:- 9720044889 ************************
 ***************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Workshop;
use App\Expense;
use App\Deposit;
use App\Asset;
use App\AssetNew;
use Auth;

class HomeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
       
          $workshops = Workshop::all();

          return view('home')->with(['workshops' => $workshops]);
    }
}
