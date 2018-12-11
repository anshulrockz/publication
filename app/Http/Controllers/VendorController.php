<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use App\State;
use App\Workshop;
use Auth;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
    	$vendor = Vendor::all();
        return view('vendor.index')->with('vendor',$vendor);
    }
    
    public function create()
    {
    	$state = State::orderBy('name')->get();
    	$location = Workshop::orderBy('name')->get();

    	$voucher_no = Vendor::lastid('vendors');
    	if(empty($voucher_no)) $voucher_no == 0;
    	else $voucher_no = $voucher_no->id;
    	$voucher_no = $voucher_no + 1;
    	$voucher_no = 'PLS_VEN_'.date('ym').sprintf("%03d", $voucher_no);

        return view('vendor.create')->with(array('state' => $state, 'location' => $location, 'voucher_no' => $voucher_no, ));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
			'name'=>'required|max:255',
			'email'=>'required|max:255',
			'gstin'=>'required',
// 			'acc_no'=>'numeric'
		]);
		
		$vendor = new Vendor;

		if(Auth::user()->user_type == 3 || Auth::user()->user_type == 4)
		$request->location = Auth::user()->workshop_id;
		
		if(!empty($request->file('doc_img')))
		{
			$image = $request->file('doc_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/vendor/'), $image_name);
		    $vendor->doc_img = $image_name;
		}

		$vendor->name = $request->name;
		$vendor->gst = $request->gstin;
		$vendor->contact_person = $request->contact_person;
		$vendor->pan = $request->pan;
		$vendor->tds_deduction = $request->tds_deduction;
		$vendor->tds_rate = $request->tds_rate;
		$vendor->state_code = $request->state_code;
		$vendor->email = $request->email;
		$vendor->mobile = $request->mobile;
		$vendor->location = $request->location;
		$vendor->address = $request->address;
		$vendor->bank_name = $request->bank_name;
		$vendor->acc_no = $request->acc_no;
		$vendor->ifsc = $request->ifsc;
		$vendor->branch = $request->branch;
		$vendor->status = 1;
		$vendor->user_sys = \Request::ip();
		$vendor->updated_by = Auth::id();
		$vendor->created_by = Auth::id();
		
		$result = $vendor->save();

		$vendor = Vendor::find($vendor->id);
		$vendor->uid = 'PLS_VEN_'.sprintf("%04d", $vendor->id);
		$result = $vendor->save();
		
		if($result){
			return back()->with('success', 'Record added successfully!');
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
    }

    public function show($id)
    {
        $vendor = Vendor::find($id);
        return view('vendor.show')->with('vendor', $vendor);
    }

    public function edit($id)
    {
    	$state = State::orderBy('name')->get();
    	$location = Workshop::orderBy('name')->get();
        $vendor = Vendor::find($id);
        return view('vendor.edit')->with(array('vendor' => $vendor, 'state' => $state, 'location' => $location, ));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
			'name'=>'required|max:255',
			'email'=>'required|max:255',
			// 'uid'=>'required|max:255',
			'gstin'=>'required',
		]);

		$vendor = Vendor::find($id); 
		
		if(Auth::user()->user_type == 3 || Auth::user()->user_type == 4)
		$request->location = Auth::user()->workshop_id;

		if(!empty($request->file('doc_img')))
		{
			$image = $request->file('doc_img');
			$image_name = time().'.'.$image->getClientOriginalExtension();
			$image->move(public_path('uploads/vendor/'), $image_name);
		    $vendor->doc_img = $image_name;
		}
        
        $vendor->uid = 'PLS_VEN_'.sprintf("%04d", $id);
		$vendor->name = $request->name;
		$vendor->gst = $request->gstin;
		$vendor->contact_person = $request->contact_person;
		$vendor->pan = $request->pan;
		$vendor->tds_deduction = $request->tds_deduction;
		$vendor->tds_rate = $request->tds_rate;
		$vendor->state_code = $request->state_code;
		$vendor->email = $request->email;
		$vendor->mobile = $request->mobile;
		$vendor->location = $request->location;
		$vendor->address = $request->address;
		$vendor->bank_name = $request->bank_name;
		$vendor->acc_no = $request->acc_no;
		$vendor->ifsc = $request->ifsc;
		$vendor->branch = $request->branch;
		$vendor->status = 1;
		$vendor->user_sys = \Request::ip();
		$vendor->updated_by = Auth::id();
		
		$result = $vendor->save();
		
		if($result){
			return redirect()->back()->with('success', 'Record updated successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }

    public function destroy($id)
    {
    // 	$count1 = User::all()->where('Vendor_id',$id)->count();
    // 	$count2 = Workshop::all()->where('vendor',$id)->count();
    // 	if($count1==0 && $count2==0){
			$vendor = Vendor::find($id);
        	$result = $vendor->delete($id);
        	
        	if($result){
				return redirect()->back()->with('success', 'Record deleted successfully!');
			}
			else{
				return redirect()->back()->with('error', 'Something went wrong!');
			}
// 		}
        
    }
    
    public function id_ajax(Request $request)
    {
		$location = $request->id;
		$location = Vendor::where('location',$location)->get();
		print_r(json_encode($location));
	}
}
