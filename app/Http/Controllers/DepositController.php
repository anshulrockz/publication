<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposit;
use App\UserDeposit;
use App\UserReturn;
use App\UserTransaction;
use App\Expense;
use App\User;
use App\Transaction;
use App\Company;
use App\Workshop;
use Auth;

class DepositController extends Controller
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
	    	$deposit = Deposit::all_deposits(); //->where('created_by',Auth::id());
    	
	        if(Auth::user()->user_type==3)
	    	{
		    	$deposit = Deposit::workshop_deposits();
		        return view('deposit.index')->with('deposit',$deposit);
	        }
	        
	    	$deposit = Deposit::all_deposits();
	        return view('deposit.index')->with('deposit',$deposit);
        
        
	        return view('deposit.index')->with('deposit',$deposit);
    	}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function create()
    {
    	if(Auth::user()->user_type==4){
			$voucher_no = UserDeposit::lastid();
			if(empty($voucher_no)) $voucher_no == 0;
	    	else $voucher_no = $voucher_no->id;
	    	$voucher_no = $voucher_no + 1;
	    	$voucher_no = 'SDEP_'.sprintf("%04d", $voucher_no);
		}
		else{
			$voucher_no = Deposit::lastid();
			if(empty($voucher_no)) $voucher_no == 0;
	    	else $voucher_no = $voucher_no->id;
	    	$voucher_no = $voucher_no + 1;
	    	$voucher_no = 'DEP_'.sprintf("%04d", $voucher_no);
	    }
    	
    	
    	try{
    		if(Auth::user()->user_type==1 || Auth::user()->user_type==5)
	    	{
	    		$companies = Company::all();
	        	return view('deposit.create')->with(array('companies' => $companies, 'voucher_no'=> $voucher_no));
			}
			
	    	if(Auth::user()->user_type==3)
	    	{
	    		$users = User::all()->where('workshop_id',Auth::user()->workshop_id)->where('user_type','!=',1)->where('id','!=',Auth::id());
	    		return view('deposit.create')->with(array('users' => $users, 'voucher_no'=> $voucher_no));
			}

			if(Auth::user()->user_type==4)
	    	{
	    		$balance = Expense::balance(Auth::id());
				$users = User::all()->where('workshop_id',Auth::user()->workshop_id)->where('user_type',4)->where('id','!=',Auth::id());
	    		return view('deposit.create')->with(array('users' => $users, 'balance' => $balance, 'voucher_no'=> $voucher_no ));
			}
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
			$deposit = new Deposit;

			if(Auth::user()->user_type==4)
	    	{
	    		$balance = Expense::balance(Auth::id());
		    	if($balance < $request->amount){
		    	    return back()->with('warning', 'Request failed! Amount cannot be greater than balance.');
		    	}

				$deposit = new UserDeposit;
			}

			$mode = $request->mode;
			
			if($mode == 2)
			{
				//$date = $request->txn_date;
				//$deposit->txn_date = date_format(date_create($date),"Y-m-d");
				$deposit->txn_no = $request->txn_no;
			}
			
			elseif($mode == 3)
			{
				$deposit->txn_no = $request->txn_no;
				$deposit->acc_no = $request->acc_no;
				$deposit->ifsc = $request->ifsc;
			}
			
			$date = $request->date;
			$deposit->date = date_format(date_create($date),"Y-m-d");
			$deposit->to_user = $request->name;
			$deposit->amount = $request->amount;
			$deposit->mode = $request->mode;
			$deposit->remark = $request->remarks;
			$deposit->user_sys = \Request::ip();
			$deposit->updated_by = Auth::id();
			$deposit->created_by = Auth::id();
			$result = $deposit->save();
			
			$id = $deposit->id;

			if(Auth::user()->user_type==4)
	    	{
				$deposit = UserDeposit::find($id);
			}
			else
				$deposit = Deposit::find($id);

			$deposit->txn_id = 'DEP_'.$id;
			$result = $deposit->save();
			
			//trans table
			if(Auth::user()->user_type==4){
				$transaction = new UserTransaction;
				$transaction->txn_type = 1;
				$transaction->voucher_no = $deposit->txn_id;
				$transaction->credit = $request->amount;
				$transaction->balance = Deposit::payeeBalance($request->name);
				$transaction->created_for = $request->name;
				$transaction->user_sys = \Request::ip();
				$transaction->updated_by = Auth::id();
				$transaction->created_by = Auth::id();
				$transaction->particulars = Auth::user()->name.' Deposit to '.$request->name;
				$result2 = $transaction->save();
			}
			
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
        try{
        	$deposit = Deposit::find($id);
	        $userdetails = Deposit::find($id)->UserDetails;
	        return view('deposit.show')->with(array('deposit' => $deposit, 'userdetails' => $userdetails));
    	}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function edit($id)
    {
    	try{
    		if(Auth::user()->user_type==4)
	    	{
	    		$deposit = UserDeposit::find($id);
	    	}
	    	else
	    	{
		    	$deposit = Deposit::find($id);
		    	$userdetails = Deposit::find($id)->UserDetails;
		    }

	    	if(Auth::user()->user_type==1 || Auth::user()->user_type==5)
	    	{
	    		$companies = Company::all();
	    		$workshops = Workshop::all()->where('company',Auth::user()->company_id);
	        	return view('deposit.edit')->with(array('userdetails' => $userdetails, 'companies' => $companies, 'workshops' => $workshops, 'deposit' => $deposit,));
			}
			
	    	if(Auth::user()->user_type==3)
	    	{
	    		$workshops = Workshop::all()->where('company',Auth::user()->company_id);
	    		return view('deposit.edit')->with(array('workshops' => $workshops, 'deposit' => $deposit,'userdetails' => $userdetails));
			}

			if(Auth::user()->user_type==4)
	    	{
	    		
	    		return view('deposit.edit')->with(array( 'deposit' => $deposit));
			}

	        return view('deposit.edit')->with('deposit', $deposit);
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
			$deposit = Deposit::find($id);

			if(Auth::user()->user_type==4)
	    	{
				$deposit = UserDeposit::find($id);
			}
			$mode = $request->mode;
			
			if($mode == 2)
			{
				$this->validate($request,[
					'txn_date'=>'required|max:255',
				]);
				
				$date = $request->txn_date;
				$deposit->txn_date = date_format(date_create($date),"Y-m-d");
				$deposit->txn_no = $request->txn_no;
			}
			
			elseif($mode == 3)
			{
				$this->validate($request,[
					'acc_no'=>'required|numeric',
					'txn_date'=>'required|max:255',
				]);
				$date = $request->txn_date;
				$deposit->txn_date = date_format(date_create($date),"Y-m-d");
				$deposit->txn_no = $request->txn_no;
				$deposit->acc_no = $request->acc_no;
				$deposit->ifsc = $request->ifsc;
			}
			
			$date = $request->date;
			$deposit->date = date_format(date_create($date),"Y-m-d");
			$deposit->to_user = $request->name;
			$deposit->amount = $request->amount;
			$deposit->mode = $request->mode;
			$deposit->remark = $request->remarks;
			$deposit->user_sys = \Request::ip();
			$deposit->updated_by = Auth::id();
			$result = $deposit->save();
			
			//trans table
			if(Auth::user()->user_type==4){
				$transaction = UserTransaction::where('voucher_no', $expense->txn_no)->first();
				$transaction->credit = $request->amount;
				$transaction->user_sys = \Request::ip();
				$transaction->updated_by = Auth::id();
				$transaction->particulars = Auth::user()->name.' Deposit '.$request->amount.' to '.$request->name;
				$result2 = $transaction->save();
			}

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
    		if(Auth::user()->user_type==4)
	    	{
				$deposit = UserDeposit::find($id);
			}
			else
	        $deposit = Deposit::find($id);
	    
	        $result = $deposit->delete($id);
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
	
	public function return(Request $request, $id)
    {
    	try{
    		$deposit = UserDeposit::find($id);
	    	$string = $deposit->to_user;
			$return_balance = UserReturn::return_bal($string);
			$return_chk = UserReturn::where('txn_id', $id)->first();
			
			if(count($return_chk)>0){
				return redirect()->back()->with('warning', 'Return already exist! Please deposit more and try to return.');
			}
			
			$return = new UserReturn;
			$return->txn_id = $deposit->id;
			$return->by_user = $deposit->to_user;
			$return->amount = $return_balance;
			$return->mode = 'return';
			$return->user_sys = \Request::ip();
			$return->created_by = Auth::id();
			$return->updated_by = Auth::id();
			$result = $return->save();

			$return = UserReturn::find($return->id);
			$return->voucher_no = 'RTN'.$return->id;
			$result = $return->save();

			$transaction = new UserTransaction;
			$transaction->txn_type = 3;
			$transaction->voucher_no = $return->voucher_no;
			$transaction->debit = $return_balance;
			$transaction->balance = 0;
			$transaction->created_for = $deposit->to_user;
			$transaction->user_sys = \Request::ip();
			$transaction->updated_by = Auth::id();
			$transaction->created_by = Auth::id();
			$transaction->particulars = $deposit->to_user.' Return to '.Auth::user()->name;
			$result2 = $transaction->save();

	        if($result){
				return redirect()->back()->with('success', $return_balance.' returned successfully!');
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

    public function payeename(Request $request)
    {
    	try{
    		$str = $request->term;
	    	$temp = UserDeposit::where('to_user', 'like', '%' . $str . '%')->where('created_by', Auth::id())->pluck('to_user');
	    	return json_encode($temp);
    	}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }
    
    public function payeeBalance(Request $request)
    {
    	try{
    		$nameofpayee = $request->created_for;
	    	$temp = Deposit::payeeBalance($nameofpayee);
	    	return json_encode($temp);
    	}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }
}
