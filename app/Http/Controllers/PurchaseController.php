<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use App\PurchaseDetail;
use App\Deposit;
use App\Workshop;
use App\Vendor;
use App\Transaction;
use App\UserTransaction;
use App\PurchaseCategory;
use App\Tax;
use App\Subpurchase;
use App\TdsReport;
use Auth;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->purchase = new purchase();
    }
    
    public function index()
    {	
    	// if(Auth::user()->user_type==1  || Auth::user()->user_type== 5)
    	// {
		// 	$purchase = Purchase::super_admin_all();
		// }
    	
    	// if(Auth::user()->user_type == 3)
    	// {
		// 	$purchase = $this->purchase->workshop_all();
		// }
		
		// if(Auth::user()->user_type == 4)
    	// {
		// 	$purchase = $this->purchase->user_all();
		// }
		
		$purchase = Purchase::all();
		
        return view('purchase.index')->with('data',$purchase);
   }

    public function create()
    {
	  //   	if(Auth::user()->user_type == 1)
	  //   	{
	  //   		$purchase = Purchase::super_admin_all();
		 //    	return view('purchase.index')->with('purchase',$purchase);
			// }
			// else
	  //   	{
		    	$purchase_category = PurchaseCategory::orderBy('name', 'ASC')->get();
		    	$tax = Tax::all();
			// }

	    	$workshop = Workshop::all();

	    	if(Auth::user()->user_type == 1 || Auth::user()->user_type == 5){
	    	$vendor = Vendor::all();
	    	}
	    	
	    	else{
	    	    $vendor = Vendor::where('location', Auth::user()->workshop_id)->get();
	    	}
	    	
	    	// $balance = $this->purchase->balance(Auth::id());
	    	
			$voucher_no = getLastRow('purchases'); // Calling from  helpers
	    	if(empty($voucher_no)) $voucher_no == 0;
	    	else $voucher_no = $voucher_no->id;
	    	$voucher_no = $voucher_no + 1; 
	    	$voucher_no = 'VS_PUR_'.sprintf("%04d", $voucher_no);
	        return view('purchase.create')->with(array( 'purchase_category' => $purchase_category, 'voucher_no' => $voucher_no, 'workshop' => $workshop, 'vendor' => $vendor, 'tax' => $tax));
    }

    public function store(Request $request)
    {
    	$this->validate($request,[
			'vendor_id'=>'required|max:255',
			'total_amount'=>'required|min:1',
		]);

		if($request->vendor_id === 'please select')  {
			return back()->with('error', 'No Vendor found! Please select vendor.');
		}
	    	
    	$balance = Purchase::balance(Auth::id());

        if($request->created_for){
        	$nameofpayee = $request->created_for;
	    	$balance = Deposit::payeeBalance($request->created_for);
	    	if($balance < $request->total_amount){
	    	    return back()->with('warning', 'Request failed! purchase amount cannot be greater than balance.');
	    	}
        }

		$purchase = new purchase;
		$purchase->mode = $request->mode; // 1-cash, 2-Credit
		$purchase->paid_in  = $request->mode;

		if($balance < $request->total_amount){
			$purchase->mode = 2;
			$purchase->paid_in = 2;
		}

		if($purchase->mode == 1){
			$purchase->paid_by = Auth::id();
		}

		if(Auth::user()->user_type == 5 || Auth::user()->user_type == 1)
		$purchase->location = $request->location;
		else
		$purchase->location = Auth::user()->workshop_id;

		if(!empty($request->file('voucher_img')))
		{
			$image = $request->file('voucher_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/purchases/'), $image_name);
		    $purchase->voucher_img = $image_name;
		}
		
		$vendor = Vendor::find($request->vendor_id);

		$date = $request->invoice_date;
		$purchase->invoice_date = date_format(date_create($date),"Y-m-d");
		$purchase->invoice_no = $request->invoice_no;
		$purchase->created_for = $request->created_for;
		$purchase->vendor_id = $request->vendor_id;
		$purchase->party_name = $vendor->name;
		$purchase->party_gstin = $vendor->gst;
		if($request->party_name){
    		$purchase->party_name = $request->party_name;
    		$purchase->party_gstin = $request->party_gstin;
		}
		$purchase->amount = $request->total_amount;
		$purchase->round_off = $request->round_off;
		$purchase->inv_type = $request->tax_type;			
		$purchase->status = 1;
		$purchase->user_sys = \Request::ip();
		$purchase->updated_by = Auth::id();
		$purchase->created_by = Auth::id();
		
		$result = $purchase->save();
		
		$amount = 0;

		for($i = 0; $i < count($request->cost); $i++){
			$purchase_details = new purchaseDetail;
			$purchase_details->purchase_id = $purchase->id;
			$purchase_details->category1 = $request->type[$i];
			$purchase_details->category2 = $request->category[$i];
			$purchase_details->category3 = $request->purchase_category[$i];
			$purchase_details->description = $request->description[$i];
			$purchase_details->reason = $request->reason[$i];
			$purchase_details->code = $request->code[$i];
			$purchase_details->cost = $request->cost[$i];
			$purchase_details->quantity = $request->quantity[$i];
			$purchase_details->tax_value = $request->tax[$i];
			$purchase_details->sgst = $request->sgst[$i];
			$purchase_details->cgst = $request->cgst[$i];
			$purchase_details->igst = $request->igst[$i];
			$purchase_details->user_sys = \Request::ip();
			$purchase_details->updated_by = Auth::id();
			$purchase_details->created_by = Auth::id();
			$purchase_details->save();
			$amount += ($request->cost[$i]*$request->quantity[$i]) + $request->sgst[$i] +  $request->cgst[$i] + $request->igst[$i];
			
// 			if($vendor->tds_deduction == 1 && $purchase_details->category1 == "Service"){
// 			    $tds_report = new TdsReport;
// 			    $tds_report->purchase_id = $purchase->id;
// 			    $tds_report->purchase_detail_id = $purchase_details->id;
// 			    $tds_report->vendor_id = $vendor->id;
// 			    $tds_report->tds_rate = $purchase_details->id;
// 			    $tds_report->purchase_detail_id = $purchase_details->id;
// 			    $tds_report->updated_by = Auth::id();
//     			$tds_report->created_by = Auth::id();
//     			$tds_report->save();
// 			}
		}
		
		if(!empty($request->file('import_csv_inv')))
		{ 
			$image = $request->file('import_csv_inv');
			$image_name = 'temp.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/csv/'), $image_name);
			// $image_name = '1536057542.csv';
			$url=asset('uploads/csv/'.$image_name);

		    $file = fopen($url,'r');
			$body= '';

			while (!feof($file)) {
			    $content = fgetcsv($file);
			    $count = count($content);
			    $csv[] = $content;
			}

			$amount = purchaseController::exportcsv($csv, $purchase->id);
		}
		
		$purchase = Purchase::find($purchase->id);
		$purchase->voucher_no = 'PLS_ET_'.sprintf("%04d", $purchase->id);
		$purchase->amount = $amount;
		$result = $purchase->save();

		$transaction = new Transaction;
		$transaction->voucher_no = $purchase->voucher_no;
		$transaction->invoice_no = $purchase->invoice_no;
		$transaction->invoice_date = $purchase->invoice_date;
		$transaction->vendor_id = $purchase->vendor_id;
		$transaction->credit = $amount;
		$transaction->txn_type = 10;  	//11-purchase-cash, 1-purchase, 2-Payment
		$transaction->particulars = 'By Sale being invoice number '.$purchase->invoice_no;
		$transaction->user_sys = \Request::ip();
		$transaction->updated_by = Auth::id();
		$transaction->created_by = Auth::id();
		$transaction->save();

		$transaction = Transaction::find($transaction->id);
		$transaction->txn_id = 'PLS_TXN_'.sprintf("%04d", $transaction->id);
		$transaction->save();

		if($purchase->mode == 1){
			// $transaction->txn_type = 11;  	//11-purchase-cash, 1-purchase, 2-Payment
			$transaction2 = new Transaction;
			$transaction2->voucher_no = $purchase->voucher_no;
			$transaction2->invoice_no = $purchase->invoice_no;
			$transaction2->invoice_date = $purchase->invoice_date;
			$transaction2->vendor_id = $purchase->vendor_id;
			$transaction2->debit = $amount;
			$transaction2->txn_type = 11;  	//11-purchase-cash, 1-purchase, 2-Payment
			$transaction2->particulars = 'To Payment being voucher number '.$purchase->voucher_no;
			$transaction2->user_sys = \Request::ip();
			$transaction2->updated_by = Auth::id();
			$transaction2->created_by = Auth::id();
			$transaction2->save();

			$transaction2 = Transaction::find($transaction2->id);
			$transaction2->txn_id = 'PLS_TXN_'.sprintf("%04d", $transaction2->id);
			$transaction2->save();
		}

		//trans table
		if($request->created_for && Auth::user()->user_type==4){
			$user_transaction = new UserTransaction;
			$user_transaction->txn_type = 2;
			$user_transaction->voucher_no = $purchase->voucher_no;
			$user_transaction->debit = $amount;
			$user_transaction->balance = $balance-$amount;
			$user_transaction->created_for = $purchase->created_for;
			$user_transaction->user_sys = \Request::ip();
			$user_transaction->updated_by = Auth::id();
			$user_transaction->created_by = Auth::id();
			$user_transaction->particulars = $request->created_for.' Spent for purchase '.$purchase->invoice_no;
			$result2 = $user_transaction->save();
		}

		if($result){
			return back()->with('success', 'Record added successfully! Your purchase ID:'.$purchase->voucher_no);
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
    }

    public function show($id)
    {
            $purchase = Purchase::find($id);
	        $userdetails = Purchase::find($id)->UserDetails();
	        return view('purchase.show')->with(array('purchase' => $purchase, 'userdetails' => $userdetails));
    }

    public function edit($id)
    {
    	try{
	    	$tax = Tax::all();
	    	$purchase_category = purchaseCategory::orderBy('name', 'ASC')->get();
	        $purchase = Purchase::find($id);
	    	$workshop = Workshop::all();
	    	if(Auth::user()->user_type == 1 || Auth::user()->user_type == 5)
	    	$vendor = Vendor::all();
	    	else
	    	$vendor = Vendor::where('location', Auth::user()->workshop_id)->get();
	        $purchase_details = Purchase::find($id)->purchaseDetails; 
	    	$balance = $this->purchase->balance($purchase->created_by); //dd($balance );
	        return view('purchase.edit')->with(array('purchase' => $purchase, 'purchase_category' => $purchase_category, 'balance' => $balance, 'purchase_details' => $purchase_details, 'tax' => $tax, 'workshop' => $workshop, 'vendor' => $vendor) );
	    }
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
			// 'party_name'=>'required|max:255',
			'total_amount'=>'required|min:1',
		]);

    	if($request->created_for){
        	$nameofpayee = $request->created_for;
	    	$payeeBalance = Deposit::payeeBalance($nameofpayee);
	    	$balance = $payeeBalance;

	    	if($payeeBalance < $request->total_amount){
	    	    return back()->with('warning', 'Request failed! purchase amount cannot be greater than balance.');
	    	}
        }

        $vendor = Vendor::find($request->vendor_id);

		$purchase = Purchase::find($id);

		$purchase_details = Purchase::find($id)->purchaseDetails;

        foreach ($purchase_details as $key => $value) {
        	$purchase_details = purchaseDetail::find($value->id);
    	    $purchase_details->forceDelete($value->id);
        }

		$date = $request->invoice_date;
		$purchase->invoice_date = date_format(date_create($date),"Y-m-d");
		$purchase->invoice_no = $request->invoice_no;
		$purchase->vendor_id = $request->vendor_id;
		$purchase->party_name = $vendor->name;
		$purchase->party_gstin = $vendor->gst;
		$purchase->inv_type = $request->tax_type;
		// $purchase->party_name = $request->party_name;
		// $purchase->party_gstin = $request->party_gstin;
// 		$purchase->paid_in = 2;
		$purchase->amount = $request->total_amount;
		$purchase->round_off = $request->round_off;
		
		if(Auth::user()->user_type == 5 || Auth::user()->user_type == 1)
		$purchase->location = $request->location;
		else
		$purchase->location = Auth::user()->workshop_id;

		if($purchase->mode == 1){
			$purchase->paid_by = Auth::id();
		}

		if(!empty($request->file('voucher_img')))
		{
			$image = $request->file('voucher_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/purchases/'), $image_name);
		    $purchase->voucher_img = $image_name;
		}
		
		$purchase->status = 1;
		$purchase->user_sys = \Request::ip();
		$purchase->updated_by = Auth::id();
		
		$result = $purchase->save();

		$id = $purchase->id;
		$amount = $purchase->amount;
		$detailid = $request->detailid;
		$supply_type = $request->type;
		$supply_category = $request->category;
		$purchase_category = $request->purchase_category;
		$description = $request->description;
		$reason = $request->reason;
		$code = $request->code;
		$cost = $request->cost;
		$quantity = $request->quantity;
		$tax = $request->tax;
		$sgst = $request->sgst;
		$cgst = $request->cgst;
		$igst = $request->igst;
		
		// if(isset($request->delRow))
		// {
		// 	$delRow = $request->delRow;

		// 	for($i = 0; $i < count($delRow); $i++)
		// 	{
		// 		$purchase_details = purchaseDetail::find($delRow[$i]);
	 //    	    $purchase_details->delete($delRow[$i]);
	 //    	}
  //   	}

		for($i = 0; $i < count($cost); $i++){
			$purchase_details = new purchaseDetail;
			$purchase_details->purchase_id = $id;
			$purchase_details->category1 = $supply_type[$i];
			$purchase_details->category2 = $supply_category[$i];
			$purchase_details->category3 = $purchase_category[$i];
			$purchase_details->description = $description[$i];
			$purchase_details->reason = $reason[$i];
			$purchase_details->code = $code[$i];
			$purchase_details->cost = $cost[$i];
			$purchase_details->quantity = $quantity[$i];
			$purchase_details->tax_value = $tax[$i];
			$purchase_details->sgst = $sgst[$i];
			$purchase_details->cgst = $cgst[$i];
			$purchase_details->igst = $igst[$i];
			$purchase_details->user_sys = \Request::ip();
			$purchase_details->updated_by = Auth::id();
			$purchase_details->created_by = Auth::id();
			$purchase_details->save();
			$amount += ($cost[$i]*$quantity[$i]) + $sgst[$i] +  $cgst[$i] + $igst[$i];
		}
		
		if(Auth::user()->user_type==4 && !empty($purchase->created_for)){
			$transaction = UserTransaction::where('voucher_no', $purchase->voucher_no)->first();
			$transaction->debit = $purchase->total_amount;
			$transaction->balance = Deposit::payeeBalance($purchase->created_for);
			$transaction->user_sys = \Request::ip();
			$transaction->updated_by = Auth::id();
			$transaction->particulars = Auth::user()->name.' spent '.$purchase->amount.' to purchase '.$purchase->subject;
			$result2 = $transaction->save();
		}

		$transaction = Transaction::where('voucher_no',$purchase->voucher_no)->where('txn_type','10')->first();
        if (!empty($transaction)){
			
			$transaction->credit = $amount;
			$transaction->updated_by = Auth::id();
			$transaction->save();
		}

        $transaction = Transaction::where('voucher_no',$purchase->voucher_no)->where('txn_type','11')->first();
        if (!empty($transaction)){
			$transaction->debit = $amount;
			$transaction->updated_by = Auth::id();
			$transaction->save();
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
        $purchase = Purchase::find($id);
        $result = $purchase->delete($id);

        $voucher_no = 'PLS_ET_'.sprintf("%04d", $id);
        $transaction = Transaction::where('voucher_no',$voucher_no)->get();
        if (count($transaction)>0){
            foreach($transaction as $key => $value){
                $result = $value->delete($value->id);
            }
        }
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
	}

    public function cancel($id)
    {
    	try{
	        $purchase = Purchase::find($id);
	        $purchase->status = 2;
	        $result = $purchase->save();

	        $voucher_no = 'PLS_ET_'.sprintf("%04d", $id);
	        $transaction = Transaction::where('voucher_no',$voucher_no)->first();
	        if (!empty($transaction))
	        $result = $transaction->delete($voucher_no);
	        
	        if($result){
				return redirect()->back()->with('success', 'Record canceled!');
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
	        $purchase = Purchase::find($id);
	        $purchase->mode = 1;
	        $result = $purchase->save();
	        
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
	        $temp = Purchase::Where('party_name', 'like', '%' . $str . '%')->pluck('party_name');

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
	        $temp = Purchase::where('party_name', $str)->pluck("party_gstin")->first();

	        return json_encode($temp);
	    }
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }
    
    public function uploadCsv(Request $request)
    {  	
    	if(!empty($request->file('import_csv_inv')))
		{ 
			$image = $request->file('import_csv_inv');
			$image_name = 'temp.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/csv/'), $image_name);
			// $image_name = '1536057542.csv';
			$url=asset('uploads/csv/'.$image_name);

		    $file = fopen($url,'r');
			$body= '';

			while (!feof($file)) {
			    $content = fgetcsv($file);
			    $count = count($content);
			    $csv[] = $content;
			}

			for ($i=7; $i < count($csv)-6; $i++) { 
			    
			    $body .= "<tr>";
			    /*
			    $body .= "<td>Material<input name='type[]' class='form-control' type='hidden' value='Material'/></td>";
			    $body .= "<td>Workshop<input name='category[]' class='form-control' type='hidden' value='Workshop'/></td>";
			    $body .= "<td>Spare Parts and Lubricants (Material)<input name='purchase_category[]' class='form-control' type='hidden' value='Spare Parts and Lubricants (Material)'  /></td>";
			    $body .= "<td>Item Purchase<input name='description[]' class='form-control' type='hidden' value='Item Purchase'  /></td>";
			    $body .= "<td>".$csv[$i][2]."<input name='reason[]' class='form-control' type='hidden' value='".$csv[$i][2]."'  /></td>";
			    $body .= "<td>".$csv[$i][1]."<input name='code[]' class='form-control' type='hidden' value='".$csv[$i][1]."'  /></td>";
			    $body .= "<td class='cost_td'>".str_replace(',', '', $csv[$i][13])/$csv[$i][6]."<input name='cost[]' class='form-control cost1' type='hidden' value='".str_replace(',', '', $csv[$i][13])/$csv[$i][6]."'  /><input name='tax[]' class='form-control' type='hidden' value='".$csv[$i][14]."'/></td>";
			    $body .= "<td class='quantity_td'>".$csv[$i][6]."<input name='quantity[]' class='form-control quantity' type='hidden' value='".$csv[$i][6]."'  /></td>";
			    $body .= "<td class='abt_td'>".str_replace(',', '', $csv[$i][13])*str_replace(',', '', $csv[$i][6])/$csv[$i][6]."<input name='abt[]' class='form-control abt' type='hidden' value='".str_replace(',', '', $csv[$i][13])*str_replace(',', '', $csv[$i][6])/$csv[$i][6]."'  /></td>"; 
			    $body .= "<td class='sgst_td'> ".str_replace(',', '', $csv[$i][15])."<input name='sgst[]' class='form-control sgst' type='hidden' value='".str_replace(',', '', $csv[$i][15])."'  />  </td>";
			    $body .= "<td class='tax_amount_td'>".str_replace(',', '', $csv[$i][17])."<input name='cgst[]' class='form-control cgst' type='hidden' value='".str_replace(',', '', $csv[$i][17])."' />  </td>";
			    $body .= "<td class='tax_amount_td'>0 <input name='igst[]' class='form-control igst' type='hidden' value='0'  />  </td>"; 
			    $body .= "<td class='amount_td'> ".str_replace(',', '', $csv[$i][18])." <input name='amount[]' class='form-control unamount' type='hidden' value='".str_replace(',', '', $csv[$i][18])."' /> </td>";
			    $body .= '<td><button type="button" class="btn btn-danger btn-xs m-t-15 waves-effect delete-row"><i class="material-icons">remove_circle</i></button></td>';
			    */
			    
			    $body .= "<td>Material</td>";
			    $body .= "<td>Workshop</td>";
			    $body .= "<td>Spare Parts and Lubricants (Material)</td>";
			    $body .= "<td>Item Purchase</td>";
			    $body .= "<td>".$csv[$i][2]."</td>";
			    $body .= "<td>".$csv[$i][1]."</td>";
			    $body .= "<td class='cost_td'>".str_replace(',', '', $csv[$i][13])/str_replace(',', '', $csv[$i][6])."</td>";
			    $body .= "<td class='quantity_td'>".$csv[$i][6]."</td>";
			    $body .= "<td class='abt_td'>".str_replace(',', '', $csv[$i][13])*str_replace(',', '', $csv[$i][6])/$csv[$i][6]."</td>"; 
			    $body .= "<td class='sgst_td'> ".str_replace(',', '', $csv[$i][15])."</td>";
			    $body .= "<td class='cgst_td'>".str_replace(',', '', $csv[$i][17])."</td>";
			    $body .= "<td class='igst_td'>0 </td>"; 
			    $body .= "<td class='amount_td'> ".str_replace(',', '', $csv[$i][18])."</td>";
			    $body .= "<td></td>";
			    
			    $body .= "</tr>"; 
			} 
			return json_encode($body);			
		}
    }
    
    function exportcsv($data, $id)
    {  	
    	$amount = 0;
		for ($i=7; $i < count($data)-6; $i++) {

			$purchase_details = new purchaseDetail;
			$purchase_details->purchase_id = $id;
			$purchase_details->category1 = 'Material';
			$purchase_details->category2 = 'Workshop';
			$purchase_details->category3 = 'Spare Parts and Lubricants (Material)';
			$purchase_details->description = 'Item Purchase';
			$purchase_details->reason = $data[$i][2];
			$purchase_details->code = $data[$i][1];
			$purchase_details->cost = str_replace(',', '', $data[$i][13])/str_replace(',', '', $data[$i][6]);
			$purchase_details->quantity = str_replace(',', '', $data[$i][6]);
			$purchase_details->tax_value = str_replace(',', '', $data[$i][14]);
			$purchase_details->sgst = str_replace(',', '', $data[$i][15]);
			$purchase_details->cgst = str_replace(',', '', $data[$i][17]);
			$purchase_details->igst = '0';
			$purchase_details->user_sys = \Request::ip();
			$purchase_details->updated_by = Auth::id();
			$purchase_details->created_by = Auth::id();
			$purchase_details->save();
			$amount += ($purchase_details->cost*$purchase_details->quantity) + $purchase_details->sgst +  $purchase_details->cgst + $purchase_details->igst;
		}
	}

}
