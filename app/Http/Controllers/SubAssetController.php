<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetCategory;
use App\SubAsset;
use Auth;

class SubAssetController extends Controller
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
    	$sub_asset = SubAsset::asset_categoriesJoin();
        return view('sub_asset_category.index')->with('sub_asset', $sub_asset);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$asset_category = AssetCategory::all();
        return view('sub_asset_category.create')->with('asset_category', $asset_category);
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
			'asset_category'=>'required|max:255',
			'name'=>'required|max:255'
		]);
		
		$sub_asset = new SubAsset;
		$sub_asset->asset_category = $request->asset_category;
		$sub_asset->name = $request->name;
		$sub_asset->description = $request->description;
		$sub_asset->status = 1;
		$sub_asset->user_sys = \Request::ip();
		$sub_asset->updated_by = Auth::id();
		$sub_asset->created_by = Auth::id();
		
		$result = $sub_asset->save();
		
		if($result){
			return back()->with('success', 'Record added successfully!');
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sub_asset = SubAsset::find($id);
        return view('sub_asset_category.show')->with('sub_asset', $sub_asset);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $asset_category = AssetCategory::all();
        $sub_asset = SubAsset::find($id);
        return view('sub_asset_category.edit')->with(array('sub_asset' => $sub_asset, 'asset_category' => $asset_category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
			'name'=>'required|max:255',
			'asset_category'=>'required|max:255'
		]);
		
		$sub_asset = SubAsset::find($id);
		$sub_asset->asset_category = $request->asset_category;
		$sub_asset->name = $request->name;
		$sub_asset->description = $request->description;
		$sub_asset->status = 1;
		$sub_asset->user_sys = \Request::ip();
		$sub_asset->updated_by = Auth::id();
		
		$result = $sub_asset->save();
		
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
        $sub_asset = SubAsset::find($id);
        $result = $sub_asset->delete($id);
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
    
    public function id_ajax(Request $request)
    {
		$id = $request->id;
		$list = SubAsset::where('asset_category',$id)->get();//->pluck('name','id');
		print_r(json_encode($list));
	}
}
