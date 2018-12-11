<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentOther;
use App\Bank;
use App\JobDetail;
use App\Workshop;
use Auth;

class ReceivedPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index() 
    { 
    	$paymentother = PaymentOther::all_payment_others(); 
		$bank = Bank::all();
	    return view('received-payment.index')->with(array('bank' => $bank, 'data' => $paymentother));
    }

    public function create()
    {
		$location = Workshop::all();
		$bank = Bank::all();
		$voucher_no = PaymentOther::lastid();
    	if(empty($voucher_no->id)) $voucher_no = 0;
    	else $voucher_no = $voucher_no->id;
    	$voucher_no = $voucher_no + 1;
    	$voucher_no = 'PLS_REC_'.sprintf("%04d", $voucher_no);

		return view('received-payment.create')->with(array('uid' => $voucher_no, 'workshop' => $location, 'bank' => $bank ));
	}

    public function store(Request $request)
    {
    	$this->validate($request,[
			// 'date'=>'required|max:255',
			'amount'=>'required|numeric',
		]);
		
    	$voucher_no = PaymentOther::lastid();
    	if(empty($voucher_no->id)) $voucher_no = 0;
    	else $voucher_no = $voucher_no->id;
    	$voucher_no = $voucher_no + 1;
    	$voucher_no = 'PLS_REC_'.sprintf("%04d", $voucher_no);

		$paymentother = new PaymentOther;
		$paymentother->uid = $voucher_no;
		$paymentother->amount = $request->amount;
		$paymentother->mode = $request->mode; //1-cash, 2- cheque, 3-Card ,4-Neft
		// $paymentother->payment_status = $request->mode; //0-pending, 1-recieved, 2-not recieved, 4-Cheque Not deposited
		
		if ($request->mode==1 || $request->mode==3) {
			$paymentother->payment_status = 1;
		}
		elseif ($request->mode==2) {
			$paymentother->payment_status = 4;
		}
		elseif ($request->mode==4) {
			$paymentother->payment_status = 0;
		}

		$paymentother->remark = $request->remarks;
		$paymentother->bank_id = $request->company_bank;

		if(Auth::user()->user_type == 5 || Auth::user()->user_type == 1)
		$paymentother->location_id = $request->location_id;
		else
		$paymentother->location_id = Auth::user()->workshop_id;

		$date = $request->date;
		$paymentother->date_deposit = date_format(date_create($date),"Y-m-d");

		$paymentother->card_no = $request->card_no;
		$paymentother->actual_amt = $request->actual_amt;
		$paymentother->party_name = $request->party_name;
		$paymentother->ref_no = $request->ref_no;
		$paymentother->cheque_bank = $request->cheque_bank;
		$paymentother->remark = $request->remarks;
		$paymentother->user_sys = \Request::ip();
		$paymentother->updated_by = Auth::id();
		$paymentother->created_by = Auth::id();

		if(!empty($request->file('voucher_img')))
		{
			$image = $request->file('voucher_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/received-payments/'), $image_name);
		    $paymentother->document = $image_name;
		}

		$result = $paymentother->save();
		
		if($result){
			return back()->with('success', 'Record added successfully!');
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
    }

    public function show($id)
    {
    	$paymentother = PaymentOther::find($id);
        $job_details = JobDetail::where('parent_id', $paymentother->uid)->get();
        return view('received-payment.show')->with(array('job_details' => $job_details, 'paymentother' => $paymentother));
    }

    public function edit($id)
    {
    	$paymentother = PaymentOther::find($id); 
		$bank = Bank::all();
		$location = Workshop::all(); 
	    return view('received-payment.edit')->with(array('paymentother' => $paymentother, 'workshop' => $location, 'bank' => $bank ));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
			'amount'=>'required|numeric',
			'mode'=>'required|max:255',
		]);
		
		$paymentother = PaymentOther::find($id);

		$paymentother->amount = $request->amount;
		$paymentother->mode = $request->mode; //1-cash, 2- cheque, 3-Card ,4-Neft
		// $paymentother->payment_status = $request->mode; //0-pending, 1-recieved, 2-not recieved, 4-Cheque Not deposited
		
		if ($request->mode==1 || $request->mode==3) {
			$paymentother->payment_status = 1;
		}
		elseif ($request->mode==2) {
			$paymentother->payment_status = 4;
		}
		elseif ($request->mode==4) {
			$paymentother->payment_status = 0;
		}

		$paymentother->remark = $request->remarks;
		$paymentother->bank_id = $request->company_bank;

		if(Auth::user()->user_type == 5 || Auth::user()->user_type == 1)
		$paymentother->location_id = $request->location_id;
		else
		$paymentother->location_id = Auth::user()->workshop_id;

		$date = $request->date;
		$paymentother->date_deposit = date_format(date_create($date),"Y-m-d");

		$paymentother->card_no = $request->card_no;
		$paymentother->actual_amt = $request->actual_amt;
		$paymentother->party_name = $request->party_name;
		$paymentother->ref_no = $request->ref_no;
		$paymentother->cheque_bank = $request->cheque_bank;
		$paymentother->remark = $request->remarks;
		$paymentother->user_sys = \Request::ip();
		$paymentother->updated_by = Auth::id();

		if(!empty($request->file('voucher_img')))
		{
			$image = $request->file('voucher_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/received-payments/'), $image_name);
		    $paymentother->document = $image_name;
		}

		$result = $paymentother->save();
		
		//trans table
		if(Auth::user()->user_type==4){
			$transaction = UserTransaction::where('voucher_no', $paymentother->txn_no)->first();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$paymentother = PaymentOther::find($id);
	    
        $result = $paymentother->delete($id);
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
	
    public function changeStatus(Request $request)
    {
    	$paymentother = PaymentOther::where('uid',$request->voucher_no)->first();

        $date = $request->date;
		$paymentother->date_received = date_format(date_create($date),"Y-m-d");

        $paymentother->payment_status = $request->group1;
        $paymentother->narrator = $request->narrator;
        $paymentother->amount_received = $request->amount;
        $paymentother->updated_by = Auth::id();
        $result = $paymentother->save();

		if($result){
			return back()->with('success', 'Request completed successfully!');
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
    }

    public function chequeStatus($request)
    {
    	$paymentother = PaymentOther::where('id',$request)->first();
		$paymentother->payment_status = 0;
        $paymentother->updated_by = Auth::id();
        $result = $paymentother->save();

		if($result){
			return back()->with('success', 'Request completed successfully!');
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
    }
}
