<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SharedBalance;
use App\Expense;
use App\User;
use Auth;

class SharedBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
		try{
			$shared_balance = SharedBalance::all_shared_balances(); //->where('created_by',Auth::id());
        	return view('shared-balance.index')->with('shared_balance',$shared_balance);
    	}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function create()
    {
    	try{
	    	$balance = Expense::balance(Auth::id());
			$users = User::all()->where('workshop_id',Auth::user()->workshop_id)->where('user_type',4)->where('id','!=',Auth::id());
			return view('shared-balance.create')->with(array('users' => $users,'balance' => $balance, ));
		}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$this->validate($request,[
			'name'=>'required|max:255',
			'date'=>'required|max:255',
			'amount'=>'required|numeric',
			'mode'=>'required|max:255',
		]);
		try{
			$shared_balance = new SharedBalance;
			$mode = $request->mode;
			
			if($mode == 2)
			{
				$this->validate($request,[
					'txn_date'=>'required|max:255',
				]);
				
				$date = $request->txn_date;
				$shared_balance->txn_date = date_format(date_create($date),"Y-m-d");
				$shared_balance->txn_no = $request->txn_no;
			}
			
			elseif($mode == 3)
			{
				$date = $request->txn_date;
				$shared_balance->txn_date = date_format(date_create($date),"Y-m-d");
				$shared_balance->txn_no = $request->txn_no;
				$shared_balance->acc_no = $request->acc_no;
				$shared_balance->ifsc = $request->ifsc;
			}
			
			$date = $request->date;
			$shared_balance->date = date_format(date_create($date),"Y-m-d");
			$shared_balance->to_user = $request->name;
			$shared_balance->amount = $request->amount;
			$shared_balance->mode = $request->mode;
			$shared_balance->remark = $request->remarks;
			$shared_balance->user_sys = \Request::ip();
			$shared_balance->updated_by = Auth::id();
			$shared_balance->created_by = Auth::id();
			$result = $shared_balance->save();
			
			$id = $shared_balance->id;
			$shared_balance = SharedBalance::find($id);
			$shared_balance->txn_id = 'DPO'.$id;
			$result = $shared_balance->save();
			
			if($result){
				return back()->with('success', 'Record added successfully!');
			}
			else{
				return back()->with('error', 'Something went wrong!');
			}
		}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function show($id)
    {
        $shared_balance = SharedBalance::find($id);
        $userdetails = SharedBalance::find($id)->UserDetails;
        return view('shared-balance.show')->with(array('shared_balance' => $shared_balance, 'userdetails' => $userdetails));
    }

    public function edit($id)
    {
    	try{
    		$shared_balance = SharedBalance::find($id);
	    	$userdetails = SharedBalance::find($id)->UserDetails;
	    	
	    	if(Auth::user()->user_type==1 || Auth::user()->user_type==5)
	    	{
	    		$companies = Company::all();
	    		$workshops = Workshop::all()->where('company',Auth::user()->company_id);
	        	return view('shared-balance.edit')->with(array('userdetails' => $userdetails, 'companies' => $companies, 'workshops' => $workshops, 'shared_balance' => $shared_balance,));
			}
			
	    	if(Auth::user()->user_type==3)
	    	{
	    		$workshops = Workshop::all()->where('company',Auth::user()->company_id);
	    		return view('shared-balance.edit')->with(array('workshops' => $workshops, 'shared_balance' => $shared_balance,'userdetails' => $userdetails));
			}
	        $shared_balance = SharedBalance::find($id);
	        return view('shared-balance.edit')->with('shared_balance', $shared_balance);
    	}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
			'name'=>'required|max:255',
			'amount'=>'required|numeric',
			'mode'=>'required|max:255',
		]);
		
		try{
			$shared_balance = SharedBalance::find($id);
			$mode = $request->mode;
			
			if($mode == 2)
			{
				$this->validate($request,[
					'txn_date'=>'required|max:255',
				]);
				
				$date = $request->txn_date;
				$shared_balance->txn_date = date_format(date_create($date),"Y-m-d");
				$shared_balance->txn_no = $request->txn_no;
			}
			
			elseif($mode == 3)
			{
				$this->validate($request,[
					'acc_no'=>'required|numeric',
					'txn_date'=>'required|max:255',
				]);
				$date = $request->txn_date;
				$shared_balance->txn_date = date_format(date_create($date),"Y-m-d");
				$shared_balance->txn_no = $request->txn_no;
				$shared_balance->acc_no = $request->acc_no;
				$shared_balance->ifsc = $request->ifsc;
			}
			
			$date = $request->date;
			$shared_balance->date = date_format(date_create($date),"Y-m-d");
			$shared_balance->to_user = $request->name;
			$shared_balance->amount = $request->amount;
			$shared_balance->mode = $request->mode;
			$shared_balance->remark = $request->remarks;
			$shared_balance->user_sys = \Request::ip();
			$shared_balance->updated_by = Auth::id();
			$result = $shared_balance->save();
			
			if($result){
				return redirect()->back()->with('success', 'Record updated successfully!');
			}
			else{
				return redirect()->back()->with('error', 'Something went wrong!');
			}
		}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
        	$shared_balance = SharedBalance::find($id);
	        $result = $shared_balance->delete($id);
	        if($result){
				return redirect()->back()->with('success', 'Record deleted successfully!');
			}
			else{
				return redirect()->back()->with('error', 'Something went wrong!');
			}
		}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }
}
