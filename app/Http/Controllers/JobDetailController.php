<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobDetail;
use App\PaymentOther;
use Auth;

class JobDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {	
    	
   	}

    public function create()
    {
	  
    }

    public function store(Request $request)
    {
    	$this->validate($request,[
			// 'vendor_id'=>'required|max:255',
			'job_amount'=>'required|min:1',
		]);
	    
		for($i = 0; $i < count($request->job_amount); $i++){
			$job_details = new JobDetail;
			$job_details->parent_id = $request->voucher_no;
			$job_details->job_no = $request->job_no[$i];
			$job_details->receipt_no = $request->receipt_no[$i];
			$job_details->amount = $request->job_amount[$i];
			// $job_details->user_sys = \Request::ip();
			$job_details->updated_by = Auth::id();
			$job_details->created_by = Auth::id();
			$result = $job_details->save();
		}

		$paymentother = PaymentOther::where('uid',$request->voucher_no)->first();
		$paymentother->job_entry = 1;
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
	    $expense = JobDetail::find($id);
	    $userdetails = JobDetail::find($id)->UserDetails();
	    return view('expense.show')->with(array('expense' => $expense, 'userdetails' => $userdetails));
    }

    public function edit($id)
    {
    	try{
	    	$description = Description::all();
	    	$tax = Tax::all();
	    	$expense_category = JobDetailCategory::orderBy('name', 'ASC')->get();
	    	$purchase_category = PurchaseCategory::all();
	        $expense = JobDetail::find($id);
	    	$workshop = Workshop::all();
	        $job_details = JobDetail::find($id)->JobDetails; 
	    	$balance = $this->expense->balance($expense->created_by); 
	        return view('expense.edit')->with(array('expense' => $expense, 'expense_category' => $expense_category, 'purchase_category' => $purchase_category, 'balance' => $balance, 'expense_details' => $expense_details, 'description' => $description, 'tax' => $tax, 'workshop' => $workshop) );
	    }
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
			'party_name'=>'required|max:255',
			'total_amount'=>'required|min:1',
		]);

    	if($request->created_for){
        	$nameofpayee = $request->created_for;
	    	$payeeBalance = Deposit::payeeBalance($nameofpayee);
	    	$balance = $payeeBalance;

	    	if($payeeBalance < $request->total_amount){
	    	    return back()->with('warning', 'Request failed! JobDetail amount cannot be greater than balance.');
	    	}
        }

		$expense = JobDetail::find($id);
		$date = $request->invoice_date;
		$expense->invoice_date = date_format(date_create($date),"Y-m-d");
		$expense->invoice_no = $request->invoice_no;
		$expense->party_name = $request->party_name;
		$expense->party_gstin = $request->party_gstin;
		$expense->paid_in = $request->mode;
		$expense->amount = $request->total_amount;
		$expense->round_off = $request->round_off;
		
		if(Auth::user()->user_type == 1 || Auth::user()->user_type == 5)
		$expense->location = $request->location;
		// else
		// $expense->location = Auth::user()->workshop_id;

		if($expense->mode == 1){
			$expense->paid_by = Auth::id();
		}

		if(!empty($request->file('voucher_img')))
		{
			$image = $request->file('voucher_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/expenses/'), $image_name);
		    $expense->voucher_img = $image_name;
		}
		
		$expense->status = 1;
		$expense->user_sys = \Request::ip();
		$expense->updated_by = Auth::id();
		
		$result = $expense->save();

		$id = $expense->id;

		$amount = $expense->amount;
		$detailid = $request->detailid;
		$supply_type = $request->type;
		$supply_category = $request->category;
		$expense_category = $request->expense_category;
		$description = $request->description;
		$reason = $request->reason;
		$code = $request->code;
		$cost = $request->cost;
		$quantity = $request->quantity;
		$tax = $request->tax;
		$sgst = $request->sgst;
		$cgst = $request->cgst;
		$igst = $request->igst;
		
		if(isset($request->delRow))
		{
			$delRow = $request->delRow;

			for($i = 0; $i < count($delRow); $i++)
			{
				$expense_details = JobDetail::find($delRow[$i]);
	    	    $expense_details->delete($delRow[$i]);
	    	}
    	}

		for($i = 0; $i < count($cost); $i++){
			
			$expense_details = new JobDetail;
			$expense_details->expense_id = $id;
			$expense_details->category1 = $supply_type[$i];
			$expense_details->category2 = $supply_category[$i];
			$expense_details->category3 = $expense_category[$i];
			$expense_details->description = $description[$i];
			$expense_details->reason = $reason[$i];
			$expense_details->code = $code[$i];
			$expense_details->cost = $cost[$i];
			$expense_details->quantity = $quantity[$i];
			$expense_details->tax_value = $tax[$i];
			$expense_details->sgst = $sgst[$i];
			$expense_details->cgst = $cgst[$i];
			$expense_details->igst = $igst[$i];
			$expense_details->user_sys = \Request::ip();
			$expense_details->updated_by = Auth::id();
			$expense_details->created_by = Auth::id();
			$expense_details->save();
			$amount += ($cost[$i]*$quantity[$i]) + $sgst[$i] +  $cgst[$i] + $igst[$i];
		}
		if(Auth::user()->user_type==4 && !empty($expense->created_for)){
			$transaction = UserTransaction::where('voucher_no', $expense->voucher_no)->first();
			$transaction->debit = $expense->total_amount;
			$transaction->balance = Deposit::payeeBalance($expense->created_for);
			$transaction->user_sys = \Request::ip();
			$transaction->updated_by = Auth::id();
			$transaction->particulars = Auth::user()->name.' spent '.$expense->amount.' to purchase '.$expense->subject;
			$result2 = $transaction->save();
		}
		
		if($result){
			return redirect()->back()->with('success', 'Record updated successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }

    public function destroy($id)
    {
    	try{
	        $expense = JobDetail::find($id);
	        $result = $expense->delete($id);
	        
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

    public function cancel($id)
    {
    	try{
	        $expense = JobDetail::find($id);
	        $expense->status = 2;
	        $result = $expense->save();
	        
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

	public function changetopaid($id)
    {
    	try{
	    	//dd($id);
	        $expense = JobDetail::find($id);
	        $expense->mode = 1;
	        $result = $expense->save();
	        
	        if($result){
				return redirect()->back()->with('success', 'Paid successfully!');
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

    public function partyname(Request $request)
    { 
    	try{
    		$str = $request->term;
	        $temp = JobDetail::Where('party_name', 'like', '%' . $str . '%')->pluck('party_name');

	        return json_encode($temp);
	    }
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function partyGSTIN(Request $request)
    { 
    	try{
    		$str = $request->party_name;
	        $temp = JobDetail::where('party_name', $str)->pluck("party_gstin")->first();

	        return json_encode($temp);
	    }
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

}
