<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\PurchaseCategory;
use Auth;
use DataTables;

class ItemController extends Controller
{
	private $title = 'items';

    public function __construct()
    {
		$this->middleware('auth');
		
	}
    
    public function index()
    {	
		$item = Item::all();
		return view('items.index')->with(['data' => $item, 'title' => $this->title]);		
   	}

    public function create()
    {
	    	$purchase_category = PurchaseCategory::all();
			
			$uid = getLastRow('items');
	    	if(empty($uid)) $uid == 0;
	    	else $uid = $uid->id;
			$uid = $uid + 1;
			
	    	$uid = rand(1000, 9999).'_'.sprintf("%04d", $uid);

	        return view('items.create')->with(array( 'voucher_no' => $uid, 'purchase_category' => $purchase_category));
    }

    public function store(Request $request)
    {
    	$this->validate($request,[
			'from_unit'=>'required|min:1',
			'to_unit'=>'required|min:1',
		]);

		$uid = Item::last_id();
    	if(empty($uid)) $uid == 0;
    	else $uid = $uid->id;
    	$uid = $uid + 1;
    	$uid = 'PLS_CHALN_'.sprintf("%04d", $uid);
		$request->uid = $uid;
		$result = Item::create($request->all()) ;

		if(!empty($request->file('voucher_img')))
		{
			$image = $request->file('voucher_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/Items/'), $image_name);
		    $challan->voucher_img = $image_name;
		}
		
		for($i = 0; $i < count($request->item_code); $i++){
			$result2 = ItemDetail::create([
				'challan_id' => $result->id,
				'item_code' => $request->item_code[$i],
				'item_name' => $request->item_name[$i],
				'item_qty' 	=> $request->item_qty[$i],
				'description' => $request->description[$i],
			]);
		}

		if($result){
			return back()->with('success', 'Record added successfully! Your Item ID:'.$result->uid);
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
    }

    public function show($id)
    {	
		// try{
			$id = preg_replace("/[^0-9]/", '', $id);
			$challan = Item::find($id);
			$challan_details = Item::find($id)->ItemDetails;
			
			return view('items.show')->with(['challan' => $challan, 'challan_details' => $challan_details]);
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
	    	$Item_category = ItemCategory::orderBy('name', 'ASC')->get();
	        $Item = Item::find($id);
	    	$workshop = Workshop::all();
	    	if(Auth::user()->user_type == 1 || Auth::user()->user_type == 5)
	    	$vendor = Vendor::all();
	    	else
	    	$vendor = Vendor::where('location', Auth::user()->workshop_id)->get();
	        $Item_details = Item::find($id)->ItemDetails; 
	    	$balance = $this->Item->balance($Item->created_by); //dd($balance );
	        return view('items.edit')->with(array('Item' => $Item, 'Item_category' => $Item_category, 'balance' => $balance, 'Item_details' => $Item_details, 'description' => $description, 'tax' => $tax, 'workshop' => $workshop, 'vendor' => $vendor) );
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
	    	    return back()->with('warning', 'Request failed! Item amount cannot be greater than balance.');
	    	}
        }

		
        

        $vendor = Vendor::find($request->vendor_id);

		$Item = Item::find($id);

		$Item_details = Item::find($id)->ItemDetails;

        foreach ($Item_details as $key => $value) {
        	$Item_details = ItemDetail::find($value->id);
    	    $Item_details->forceDelete($value->id);
        }

		$date = $request->invoice_date;
		$Item->invoice_date = date_format(date_create($date),"Y-m-d");
		$Item->invoice_no = $request->invoice_no;
		$Item->vendor_id = $request->vendor_id;
		$Item->party_name = $vendor->name;
		$Item->party_gstin = $vendor->gst;
		$Item->inv_type = $request->tax_type;
		// $Item->party_name = $request->party_name;
		// $Item->party_gstin = $request->party_gstin;
		$Item->paid_in = 2;
		$Item->amount = $request->total_amount;
		$Item->round_off = $request->round_off;
		
		if(Auth::user()->user_type == 5 || Auth::user()->user_type == 1)
		$Item->location = $request->location;
		else
		$Item->location = Auth::user()->workshop_id;

		if($Item->mode == 1){
			$Item->paid_by = Auth::id();
		}

		if(!empty($request->file('voucher_img')))
		{
			$image = $request->file('voucher_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/Items/'), $image_name);
		    $Item->voucher_img = $image_name;
		}
		
		$Item->status = 1;
		$Item->user_sys = \Request::ip();
		$Item->updated_by = Auth::id();
		
		$result = $Item->save();

		$id = $Item->id;
		$amount = $Item->amount;
		$detailid = $request->detailid;
		$supply_type = $request->type;
		$supply_category = $request->category;
		$Item_category = $request->Item_category;
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
		// 		$Item_details = ItemDetail::find($delRow[$i]);
	 //    	    $Item_details->delete($delRow[$i]);
	 //    	}
  	//   	}

		for($i = 0; $i < count($cost); $i++){
			$Item_details = new ItemDetail;
			$Item_details->Item_id = $id;
			$Item_details->category1 = $supply_type[$i];
			$Item_details->category2 = $supply_category[$i];
			$Item_details->category3 = $Item_category[$i];
			$Item_details->description = $description[$i];
			$Item_details->reason = $reason[$i];
			$Item_details->code = $code[$i];
			$Item_details->cost = $cost[$i];
			$Item_details->quantity = $quantity[$i];
			$Item_details->tax_value = $tax[$i];
			$Item_details->sgst = $sgst[$i];
			$Item_details->cgst = $cgst[$i];
			$Item_details->igst = $igst[$i];
			$Item_details->user_sys = \Request::ip();
			$Item_details->updated_by = Auth::id();
			$Item_details->created_by = Auth::id();
			$Item_details->save();
			$amount += ($cost[$i]*$quantity[$i]) + $sgst[$i] +  $cgst[$i] + $igst[$i];
		}
		
		if(Auth::user()->user_type==4 && !empty($Item->created_for)){
			$transaction = UserTransaction::where('voucher_no', $Item->voucher_no)->first();
			$transaction->debit = $Item->total_amount;
			$transaction->balance = Deposit::payeeBalance($Item->created_for);
			$transaction->user_sys = \Request::ip();
			$transaction->updated_by = Auth::id();
			$transaction->particulars = Auth::user()->name.' spent '.$Item->amount.' to purchase '.$Item->subject;
			$result2 = $transaction->save();
		}

		$transaction = Transaction::where('voucher_no',$Item->voucher_no)->first();
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
        $Item = Item::find($id);
        $result = $Item->delete($id);

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
	        $Item = Item::find($id);
	        $Item->status = 2;
	        $result = $Item->save();

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
	        $Item = Item::find($id);
	        $Item->mode = 1;
	        $result = $Item->save();
	        
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
	        $temp = Item::Where('party_name', 'like', '%' . $str . '%')->pluck('party_name');

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
	        $temp = Item::where('party_name', $str)->pluck("party_gstin")->first();

	        return json_encode($temp);
	    }
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function getPosts()
    { 
        $list = Item::super_admin_all();
        return \DataTables::of(Item::super_admin_all())
        		->addColumn('action', function ($list) {
                	$_global = Auth::user();
                	$action ="";
        			if($list->status==1){
                        $action .= '<a href="'. url('/Items/'.$list->id.'/edit').'" class="btn btn-xs btn-info"> <i class="material-icons">edit</i> </a>';
                        
                        
	                		$date1=date_create($list->created_at);
							$date2=date_create(date("y-m-d H:i:s"));
							$diff=date_diff($date2,$date1);
							$days = $diff->format("%a");
                		
                		if($days<1)
                        $action .= '<a href="'. url('/Items/cancel/'.$list->id).'
                    " class="btn btn-xs btn-warning"> <i class="material-icons">cancel</i> </a>';
                        
                    }
                    if(Auth::user()->user_type==1){
                    $action .= '<form style="display: inline;" method="post" action="'. route('Items.destroy',$list->id).'">
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

			$Item_details = new ItemDetail;
			$Item_details->Item_id = $id;
			$Item_details->category1 = 'Material';
			$Item_details->category2 = 'Workshop';
			$Item_details->category3 = $data[$i];
			$Item_details->description = 'Item Purchase';
			$Item_details->reason = $data[$i][2];
			$Item_details->code = $data[$i][1];
			$Item_details->cost = str_replace(',', '', $csv[$i][13]);
			$Item_details->quantity = str_replace(',', '', $data[$i][6]);
			$Item_details->tax_value = str_replace(',', '', $data[$i][14]);
			$Item_details->sgst = str_replace(',', '', $data[$i][15]);
			$Item_details->cgst = str_replace(',', '', $data[$i][17]);
			$Item_details->igst = '0';
			$Item_details->user_sys = \Request::ip();
			$Item_details->updated_by = Auth::id();
			$Item_details->created_by = Auth::id();
			$Item_details->save();
			$amount += ($Item_details->cost*$Item_details->quantity) + $Item_details->sgst +  $Item_details->cgst + $Item_details->igst;
		}
	}

}
