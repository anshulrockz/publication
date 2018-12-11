<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Challan;
use App\ChallanDetail;
use App\Workshop;
use Auth;
use DataTables;

class ChallanController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    
    public function index()
    {	
    	$Challan = Challan::all();
		return view('Challan.index')->with('data',$Challan);		
   }

    public function create()
    {
	    	$workshop = Workshop::all();
			
			$uid = Challan::last_id();
	    	if(empty($uid)) $uid == 0;
	    	else $uid = $uid->id;
	    	$uid = $uid + 1;
	    	$uid = 'PLS_CHALN_'.sprintf("%04d", $uid);

	        return view('Challan.create')->with(array( 'voucher_no' => $uid, 'workshop' => $workshop));
    }

    public function store(Request $request)
    {
    	$this->validate($request,[
			'from_unit'=>'required|min:1',
			'to_unit'=>'required|min:1',
		]);

		$uid = Challan::last_id();
    	if(empty($uid)) $uid == 0;
    	else $uid = $uid->id;
    	$uid = $uid + 1;
    	$uid = 'PLS_CHALN_'.sprintf("%04d", $uid);
		$request->uid = 'PLS_CHALN_0001';
		$result = Challan::create($request->all()) ;

		if(!empty($request->file('voucher_img')))
		{
			$image = $request->file('voucher_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/Challans/'), $image_name);
		    $challan->voucher_img = $image_name;
		}
		
		for($i = 0; $i < count($request->item_code); $i++){
			$result2 = ChallanDetail::create([
				'challan_id' => $result->id,
				'item_code' => $request->item_code[$i],
				'item_name' => $request->item_name[$i],
				'item_qty' 	=> $request->item_qty[$i],
				'description' => $request->description[$i],
			]);
		}

		if($result){
			return back()->with('success', 'Record added successfully! Your Challan ID:'.$result->uid);
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
    }

    public function show($id)
    {	
		// try{
			$id = preg_replace("/[^0-9]/", '', $id);
			$challan = Challan::find($id);
			$challan_details = Challan::find($id)->ChallanDetails;
			
			return view('Challan.show')->with(['challan' => $challan, 'challan_details' => $challan_details]);
		// }
    	// catch(\Exception $e){
		// 	$error = $e->getMessage();
		//     return back()->with('error', 'Something went wrong! Please contact admin');
		// }
	}

    public function edit($id)
    {
    	try{
	    	$description = Description::all();
	    	$tax = Tax::all();
	    	$Challan_category = ChallanCategory::orderBy('name', 'ASC')->get();
	        $Challan = Challan::find($id);
	    	$workshop = Workshop::all();
	    	if(Auth::user()->user_type == 1 || Auth::user()->user_type == 5)
	    	$vendor = Vendor::all();
	    	else
	    	$vendor = Vendor::where('location', Auth::user()->workshop_id)->get();
	        $Challan_details = Challan::find($id)->ChallanDetails; 
	    	$balance = $this->Challan->balance($Challan->created_by); //dd($balance );
	        return view('Challan.edit')->with(array('Challan' => $Challan, 'Challan_category' => $Challan_category, 'balance' => $balance, 'Challan_details' => $Challan_details, 'description' => $description, 'tax' => $tax, 'workshop' => $workshop, 'vendor' => $vendor) );
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
	    	    return back()->with('warning', 'Request failed! Challan amount cannot be greater than balance.');
	    	}
        }

		
        

        $vendor = Vendor::find($request->vendor_id);

		$Challan = Challan::find($id);

		$Challan_details = Challan::find($id)->ChallanDetails;

        foreach ($Challan_details as $key => $value) {
        	$Challan_details = ChallanDetail::find($value->id);
    	    $Challan_details->forceDelete($value->id);
        }

		$date = $request->invoice_date;
		$Challan->invoice_date = date_format(date_create($date),"Y-m-d");
		$Challan->invoice_no = $request->invoice_no;
		$Challan->vendor_id = $request->vendor_id;
		$Challan->party_name = $vendor->name;
		$Challan->party_gstin = $vendor->gst;
		$Challan->inv_type = $request->tax_type;
		// $Challan->party_name = $request->party_name;
		// $Challan->party_gstin = $request->party_gstin;
		$Challan->paid_in = 2;
		$Challan->amount = $request->total_amount;
		$Challan->round_off = $request->round_off;
		
		if(Auth::user()->user_type == 5 || Auth::user()->user_type == 1)
		$Challan->location = $request->location;
		else
		$Challan->location = Auth::user()->workshop_id;

		if($Challan->mode == 1){
			$Challan->paid_by = Auth::id();
		}

		if(!empty($request->file('voucher_img')))
		{
			$image = $request->file('voucher_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/Challans/'), $image_name);
		    $Challan->voucher_img = $image_name;
		}
		
		$Challan->status = 1;
		$Challan->user_sys = \Request::ip();
		$Challan->updated_by = Auth::id();
		
		$result = $Challan->save();

		$id = $Challan->id;
		$amount = $Challan->amount;
		$detailid = $request->detailid;
		$supply_type = $request->type;
		$supply_category = $request->category;
		$Challan_category = $request->Challan_category;
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
		// 		$Challan_details = ChallanDetail::find($delRow[$i]);
	 //    	    $Challan_details->delete($delRow[$i]);
	 //    	}
  	//   	}

		for($i = 0; $i < count($cost); $i++){
			$Challan_details = new ChallanDetail;
			$Challan_details->Challan_id = $id;
			$Challan_details->category1 = $supply_type[$i];
			$Challan_details->category2 = $supply_category[$i];
			$Challan_details->category3 = $Challan_category[$i];
			$Challan_details->description = $description[$i];
			$Challan_details->reason = $reason[$i];
			$Challan_details->code = $code[$i];
			$Challan_details->cost = $cost[$i];
			$Challan_details->quantity = $quantity[$i];
			$Challan_details->tax_value = $tax[$i];
			$Challan_details->sgst = $sgst[$i];
			$Challan_details->cgst = $cgst[$i];
			$Challan_details->igst = $igst[$i];
			$Challan_details->user_sys = \Request::ip();
			$Challan_details->updated_by = Auth::id();
			$Challan_details->created_by = Auth::id();
			$Challan_details->save();
			$amount += ($cost[$i]*$quantity[$i]) + $sgst[$i] +  $cgst[$i] + $igst[$i];
		}
		
		if(Auth::user()->user_type==4 && !empty($Challan->created_for)){
			$transaction = UserTransaction::where('voucher_no', $Challan->voucher_no)->first();
			$transaction->debit = $Challan->total_amount;
			$transaction->balance = Deposit::payeeBalance($Challan->created_for);
			$transaction->user_sys = \Request::ip();
			$transaction->updated_by = Auth::id();
			$transaction->particulars = Auth::user()->name.' spent '.$Challan->amount.' to purchase '.$Challan->subject;
			$result2 = $transaction->save();
		}

		$transaction = Transaction::where('voucher_no',$Challan->voucher_no)->first();
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
        $Challan = Challan::find($id);
        $result = $Challan->delete($id);

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
	        $Challan = Challan::find($id);
	        $Challan->status = 2;
	        $result = $Challan->save();

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
	        $Challan = Challan::find($id);
	        $Challan->mode = 1;
	        $result = $Challan->save();
	        
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
	        $temp = Challan::Where('party_name', 'like', '%' . $str . '%')->pluck('party_name');

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
	        $temp = Challan::where('party_name', $str)->pluck("party_gstin")->first();

	        return json_encode($temp);
	    }
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function getPosts()
    { 
        $list = Challan::super_admin_all();
        return \DataTables::of(Challan::super_admin_all())
        		->addColumn('action', function ($list) {
                	$_global = Auth::user();
                	$action ="";
        			if($list->status==1){
                        $action .= '<a href="'. url('/Challans/'.$list->id.'/edit').'" class="btn btn-xs btn-info"> <i class="material-icons">edit</i> </a>';
                        
                        
	                		$date1=date_create($list->created_at);
							$date2=date_create(date("y-m-d H:i:s"));
							$diff=date_diff($date2,$date1);
							$days = $diff->format("%a");
                		
                		if($days<1)
                        $action .= '<a href="'. url('/Challans/cancel/'.$list->id).'
                    " class="btn btn-xs btn-warning"> <i class="material-icons">cancel</i> </a>';
                        
                    }
                    if(Auth::user()->user_type==1){
                    $action .= '<form style="display: inline;" method="post" action="'. route('Challans.destroy',$list->id).'">
                        '. csrf_field().method_field('DELETE') .'
                        <button onclick="return confirm(\'Are you sure you want to delete?\');" type="submit" class="btn btn-xs btn-danger" title="Cancel"><i class="material-icons">delete</i></button>
                    </form>';
                    }

                    return "$action";
        		})
        		->editColumn('status', function ($list) {
        			$status = "";
        			if($list->status==1) $status .= 'Active';
                	else $status .= 'Cancelled';
                	return $status;
           		})
           		->editColumn('amount', function ($list) {
        			$status = "";
        			if($list->round_off==1) return round(($list->cost+$list->cgst+$list->sgst+$list->igst),0);
        			else return ($list->cost+$list->cgst+$list->sgst+$list->igst);
           		})
           		->editColumn('invoice_date', function ($list) {
        			return date_format(date_create($list->invoice_date),"d/m/Y");
           		})
           		->removeColumn('location')
           		->removeColumn('user_sys')
           		->removeColumn('created_by')
           		->removeColumn('updated_by')
           		->removeColumn('created_at')
           		->removeColumn('updated_at')
           		->removeColumn('deleted_at')
           		->removeColumn('paid_in')
           		->removeColumn('paid_by')
           		->removeColumn('party_gstin')
        		->make(true);
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
			    
			    $body .= '<tr>';

			    $body .= "<td>Material</td>";
			    $body .= "<td>Workshop</td>";
			    $body .= "<td>Spare Parts and Lubricants (Material)</td>";
			    $body .= "<td>Item Purchase</td>";
			    $body .= "<td>".$csv[$i][2]."</td>";
			    $body .= "<td>".$csv[$i][1]."</td>";
			    $body .= "<td class='cost_td'>".str_replace(',', '', $csv[$i][13])."</td>";
			    $body .= "<td class='quantity_td'>".$csv[$i][6]."</td>";
			    $body .= "<td class='abt_td'>".str_replace(',', '', $csv[$i][13])*str_replace(',', '', $csv[$i][6])."</td>"; 
			    $body .= "<td class='sgst_td'> ".str_replace(',', '', $csv[$i][15])."</td>";
			    $body .= "<td class='tax_amount_td'>".str_replace(',', '', $csv[$i][17])."</td>";
			    $body .= "<td class='tax_amount_td'>0 </td>"; 
			    $body .= "<td class='amount_td'> ".str_replace(',', '', $csv[$i][18])."</td>";
			    $body .= '<td></td>';

			    $body .= "</tr>"; 
			} 
			return json_encode($body);			
		}
    }

    public function exportcsv($data, $id)
    {  	
    	$amount = 0;
		for ($i=7; $i < count($data)-6; $i++) {

			$Challan_details = new ChallanDetail;
			$Challan_details->Challan_id = $id;
			$Challan_details->category1 = 'Material';
			$Challan_details->category2 = 'Workshop';
			$Challan_details->category3 = $data[$i];
			$Challan_details->description = 'Item Purchase';
			$Challan_details->reason = $data[$i][2];
			$Challan_details->code = $data[$i][1];
			$Challan_details->cost = str_replace(',', '', $csv[$i][13]);
			$Challan_details->quantity = str_replace(',', '', $data[$i][6]);
			$Challan_details->tax_value = str_replace(',', '', $data[$i][14]);
			$Challan_details->sgst = str_replace(',', '', $data[$i][15]);
			$Challan_details->cgst = str_replace(',', '', $data[$i][17]);
			$Challan_details->igst = '0';
			$Challan_details->user_sys = \Request::ip();
			$Challan_details->updated_by = Auth::id();
			$Challan_details->created_by = Auth::id();
			$Challan_details->save();
			$amount += ($Challan_details->cost*$Challan_details->quantity) + $Challan_details->sgst +  $Challan_details->cgst + $Challan_details->igst;
		}
	}

}
