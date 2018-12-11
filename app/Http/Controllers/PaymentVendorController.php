<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentVendor;
use App\Vendor;
use App\Bank;
use App\Transaction;
use App\Company;
use App\Workshop;
use Auth;

class PaymentVendorController extends Controller
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
    	$deposit = Transaction::all_details(); 
    	return view('payment-vendor.index')->with('deposit',$deposit);  	
    }

    public function create()
    {
    	$voucher_no = PaymentVendor::lastid();
    	if(empty($voucher_no)) $voucher_no == 0;
    	else $voucher_no = $voucher_no->id;
    	$voucher_no = $voucher_no + 1;
    	$voucher_no = 'PLS_PAY_'.sprintf("%04d", $voucher_no);

    	$bank = Bank::all();

    	if (isset($_GET['id'])) {
			$vendor_id = $_GET['id'];
    		$vendor = Vendor::where('id',$vendor_id)->get();
    	}
    	elseif (Auth::user()->user_type == 1) {
    		$vendor = Vendor::all();
    	}
    	else{
    		$vendor = Vendor::where('location', Auth::user()->workshop_id)->get();;
    	}

    	return view('payment-vendor.create')->with(array('vendor' => $vendor, 'voucher_no' => $voucher_no, 'bank' => $bank, ));
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
		
		$formInfo = new PaymentVendor;

		if(!empty($request->file('voucher_img')))
		{
			$image = $request->file('voucher_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/paymentvendor/'), $image_name);
		    $expense->voucher_img = $image_name;
		}

		$date = $request->date;
		$formInfo->date = date_format(date_create($date),"Y-m-d");
		$formInfo->voucher_no = $request->voucher_no;
		$formInfo->created_for = $request->name;
		$formInfo->amount = $request->amount;
		$formInfo->epay_no = $request->epay_no;
		$formInfo->bank_id = $request->bank;
		$formInfo->mode = $request->mode;
		$formInfo->txn_no = $request->txn_no;
		$formInfo->remark = $request->remarks;
		$formInfo->user_sys = \Request::ip();
		$formInfo->updated_by = Auth::id();
		$formInfo->created_by = Auth::id(); 
		$result = $formInfo->save();

		$transaction = new Transaction;
		$transaction->txn_type = 2;  	//1- Expense, 2- Payment
		$transaction->voucher_no = $formInfo->voucher_no;
		$transaction->invoice_date = $formInfo->date;
		$transaction->vendor_id = $formInfo->created_for;
		$transaction->debit = $formInfo->amount;
		if ($formInfo->mode==4) {
			$transaction->particulars = 'Discount being voucher no '.$formInfo->voucher_no;
		  //  $transaction->debit = $formInfo->amount;
		}
		elseif($formInfo->mode==5){
			$transaction->particulars = 'To TDS Deducted against voucher no '.$formInfo->voucher_no;
		  //  $transaction->debit = $formInfo->amount;
		}
		else{
			$transaction->particulars = 'To Payment being voucher no '.$formInfo->voucher_no;
		  //  $transaction->debit = $formInfo->amount;
		}
		$transaction->user_sys = \Request::ip();
		$transaction->updated_by = Auth::id();
		$transaction->created_by = Auth::id();
		$result2 = $transaction->save();

		$transaction = Transaction::find($transaction->id);
		$transaction->txn_id = 'PLS_TXN_'.date('ym').sprintf("%04d", $transaction->id);
		$result = $transaction->save();
		
		if($result){
			return back()->with('success', 'Record added successfully!');
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
	}

    public function show($id)
    {
    	$transaction = Transaction::where('vendor_id',$id)->orderBy('invoice_date', 'asc')->get(); 
        $vendor = Vendor::find($id);
        $balance = 0;
        return view('payment-vendor.show')->with(array('transaction' => $transaction, 'vendor' => $vendor,'balance' => $balance));
	}

    public function edit($id)
    {
    	try{
    		if(Auth::user()->user_type==4)
	    	{
	    		$deposit = BankPaymentVendor::find($id);
	    	}
	    	else
	    	{
		    	$deposit = PaymentVendor::find($id);
		    	$userdetails = PaymentVendor::find($id)->BankDetails;
		    }

	    	if(Auth::user()->user_type==1 || Auth::user()->user_type==5)
	    	{
	    		$companies = Company::all();
	    		$workshops = Workshop::all()->where('company',Auth::user()->company_id);
	        	return view('payment-vendor.edit')->with(array('userdetails' => $userdetails, 'companies' => $companies, 'workshops' => $workshops, 'deposit' => $deposit,));
			}
			
	    	if(Auth::user()->user_type==3)
	    	{
	    		$workshops = Workshop::all()->where('company',Auth::user()->company_id);
	    		return view('payment-vendor.edit')->with(array('workshops' => $workshops, 'deposit' => $deposit,'userdetails' => $userdetails));
			}

			if(Auth::user()->user_type==4)
	    	{
	    		
	    		return view('payment-vendor.edit')->with(array( 'deposit' => $deposit));
			}

	        return view('payment-vendor.edit')->with('deposit', $deposit);
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
			$deposit = PaymentVendor::find($id);

			if(Auth::user()->user_type==4)
	    	{
				$deposit = BankPaymentVendor::find($id);
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
				$transaction = BankTransaction::where('voucher_no', $formInfo->txn_no)->first();
				$transaction->credit = $request->amount;
				$transaction->user_sys = \Request::ip();
				$transaction->updated_by = Auth::id();
				$transaction->particulars = Auth::user()->name.' PaymentVendor '.$request->amount.' to '.$request->name;
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

    public function destroy($id)
    {
    	$data = Transaction::find($id);
        $result = $data->delete($id);
        
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
    
    public function printTables(Request $request, $id){

		$start = $request->start;
		$end = $request->end;
		$statement = "";

		$from = date_format(date_create($start),"Y-m-d");
		$to = date_format(date_create($end),"Y-m-d");
        
        if(!empty($start) || !empty($end)){
            $transaction = Transaction::where('vendor_id',$id)
                            ->whereBetween('invoice_date', [$from, $to])
                            ->orderBy('invoice_date', 'asc')
							->get();
        }
		else{
			$transaction = Transaction::where('vendor_id',$id)
                            ->orderBy('invoice_date', 'asc')
							->get();
		}
        
        $from = date_format(date_create($start),"d/m/Y");
		$to = date_format(date_create($end),"d/m/Y");
        
        $start = !empty($start)? $from : date_format(date_create($transaction[0]->invoice_date), "d/m/Y") ;
        $end = !empty($end)? $to : date("d/m/Y") ;
        $statement = $start." To ".$end;
		$vendor = Vendor::find($id);
		$balance = 0;
        return view('payment-vendor.printout')->with(array('transaction' => $transaction, 'vendor' => $vendor,'balance' => $balance, 'start' => $start, 'end' => $end, 'statement' => $statement));
	}
}
