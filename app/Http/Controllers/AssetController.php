<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset;
use App\Vehicle;
use App\AssetCategory;
use App\SubAsset;
use App\Workshop;
use Auth;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
    	try{
	    	$asset = Asset::user_all();
			return view('asset.index')->with('asset',$asset);
    	}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
	}

    public function create()
    {
    	try{
    		$asset_category = AssetCategory::all();
	    	$location = Workshop::all();
	    	if(Auth::user()->user_type == 3)
	    	$location = Workshop::all()->where('id',Auth::user()->workshop_id);
	    	$voucher_no = Asset::lastid();
	    	if(empty($voucher_no->id)) $voucher_no = 0;
	    	else $voucher_no = $voucher_no->id;
	    	$voucher_no = $voucher_no + 1;
	    	$voucher_no = 'ASO'.$voucher_no;
	        return view('asset.create')->with(array('asset_category' => $asset_category, 'location' => $location, 'voucher_no' => $voucher_no));
		}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function store(Request $request)
    {
        $this->validate($request,[
			'amount'=>'required|numeric',
		]);

		try{
			$asset = new Asset;
			
			$main_category_name = AssetCategory::where('id', $request->asset_category)->pluck('name');
			$asset->main_category = $main_category_name[0];
			if(!empty($request->sub_asset))
			{
				$category_name = SubAsset::where('id', $request->sub_asset)->pluck('name');
				$asset->sub_category = $category_name[0];
			}
			if(!empty($request->file('voucher_img')))
			{
				$image = $request->file('voucher_img');
				$image_name = time().'.'.$image->getClientOriginalExtension();
				$image->move(public_path('uploads/assets/'), $image_name);
			    $asset->voucher_img = $image_name;
			}

			$date = $request->expiry;
			$asset->expiry = date_format(date_create($date),"Y-m-d");

			$date = $request->invoice_date;
			$asset->invoice_date = date_format(date_create($date),"Y-m-d");

			//$asset->main_category = $request->asset_category;
			$asset->voucher_no = Asset::ajax_voucher_no($main_category_name[0]);
			$asset->invoice_no = $request->invoice_no;
			$asset->location = $request->location;
			$asset->party_name = $request->party_name;
			$asset->party_gstin = $request->party_gstin;
			$asset->make = $request->make;
			$asset->model = $request->model;
			$asset->mfg = $request->mfg;
			$asset->amount = $request->amount;
			$asset->remarks = $request->remarks;
			$asset->status = $request->status;
			$asset->user_sys = \Request::ip();
			$asset->updated_by = Auth::id();
			$asset->created_by = Auth::id();
			$result = $asset->save();
			
			$id = $asset->id;
			$voucher_no = $asset->voucher_no;
			// $asset = Asset::find($id);
			// $asset->voucher_no = 'ASO'.$id;
			// $result = $asset->save();

			if($main_category_name[0] == 'Vehicle' || $main_category_name[0] == 'vehicle')
			{
				$vehicle = new Vehicle;
				$date = $request->insurance;
				$vehicle->insurance = date_format(date_create($date),"Y-m-d");
				$date = $request->puc;
				$vehicle->puc = date_format(date_create($date),"Y-m-d");
				$vehicle->registration = $request->registration;
				$vehicle->voucher_no = $voucher_no;
				$vehicle->asset_id = $id;
				$result2 = $vehicle->save();
			}
			
			if($result){
				return back()->with('success', 'Record added successfully! Your asset ID:'.$asset->voucher_no);
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
        $asset = Asset::find($id);
        return view('asset.show')->with('asset', $asset);
    }

    public function edit($id)
    {
    	try{
	    	$asset_category = AssetCategory::all();
	    	$location = Workshop::all();
	    	if(Auth::user()->user_type == 3)
	    	$location = Workshop::all()->where('id',Auth::user()->workshop_id);
	        $asset = Asset::find($id);
	        $vehicle = Vehicle::where('voucher_no',$asset->voucher_no)->first();
	        return view('asset.edit')->with(array('asset' => $asset,'location' => $location, 'asset_category' => $asset_category, 'vehicle' => $vehicle));
		}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
			'amount'=>'required|numeric',
		]);
		try{
			$asset = Asset::find($id); 

			if(!empty($request->file('voucher_img')))
			{
				$image = $request->file('voucher_img');
				$image_name = time().'.'.$image->getClientOriginalExtension();
				$image->move(public_path('uploads/assets/'), $image_name);
			    $asset->voucher_img = $image_name;
			}
			
			$date = $request->expiry;
			$asset->expiry = date_format(date_create($date),"Y-m-d");

			$date = $request->invoice_date;
			$asset->invoice_date = date_format(date_create($date),"Y-m-d");
			
			/* $main_category_name = AssetCategory::where('id', $request->asset_category)->pluck('name');
			$asset->main_category = $main_category_name[0]; 
			if(!empty($request->sub_asset))
			{
				// $category_name = SubAsset::where('id', $request->sub_asset)->pluck('name');
				// $asset->sub_category = $category_name[0];
				$category = SubAsset::find($request->sub_asset);
				if(count($category)>0)
				$asset->sub_category = $category->name;
				else
					$asset->sub_category = "";
			}else
					$asset->sub_category = "";*/

			$asset->invoice_no = $request->invoice_no;
			$asset->location = $request->location;
			$asset->party_name = $request->party_name;
			$asset->party_gstin = $request->party_gstin;
			$asset->make = $request->make;
			$asset->model = $request->model;
			$asset->mfg = $request->mfg;
			$asset->amount = $request->amount;
			$asset->remarks = $request->remarks;
			$asset->status = $request->status;
			$asset->user_sys = \Request::ip();
			$asset->updated_by = Auth::id();
			$result = $asset->save(); 
			
			if($asset->main_category == 'Vehicle' || $asset->main_category == 'vehicle')
			{
				$vehicle = Vehicle::where('voucher_no',$asset->voucher_no)->first();
				if(empty($vehicle))
					$vehicle = new VehicleNew;
				$vehicle->asset_id = $asset->id;
				$vehicle->registration = $request->registration;
				$vehicle->make = $request->vehicle_make;
				$date = $request->insurance;
				$vehicle->insurance = date_format(date_create($date),"Y-m-d");
				$date = $request->puc;
				$vehicle->puc = date_format(date_create($date),"Y-m-d");
				$result2 = $vehicle->save();
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
    	try{
	        $sub_expense = Asset::find($id);
	        $result = $sub_expense->delete($id);
	        
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

    public static function ajax_voucher_no(Request $request)
	{
		try{
			return Asset::ajax_voucher_no($request->category);
		
		}
    	catch(\Exception $e){
			$error = $e->getMessage();
		    return back()->with('error', 'Something went wrong! Please contact admin');
		}
	}
}
