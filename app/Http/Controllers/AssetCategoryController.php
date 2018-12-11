<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetCategory;
use Auth;

class AssetCategoryController extends Controller
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
        	$asset_category = AssetCategory::all();
            return view('asset_category.index')->with('asset_category', $asset_category);
        }
        catch(\Exception $e){
            $error = $e->getMessage();
            return back()->with('error', 'Something went wrong! Please contact admin');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            return view('asset_category.create');
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
		]);
		try{
    		$asset_category = new AssetCategory;
    		$asset_category->name = $request->name;
    		$asset_category->description = $request->description;
    		$asset_category->status = 1;
    		$asset_category->user_sys = \Request::ip();
    		$asset_category->updated_by = Auth::id();
    		$asset_category->created_by = Auth::id();
    		
    		$result = $asset_category->save();
    		
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $asset_category = AssetCategory::find($id);
            return view('asset_category.show')->with('asset_category', $asset_category);
    
        }
        catch(\Exception $e){
            $error = $e->getMessage();
            return back()->with('error', 'Something went wrong! Please contact admin');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $asset_category = AssetCategory::find($id);
            return view('asset_category.edit')->with(array('asset_category'=> $asset_category));
        }
        catch(\Exception $e){
            $error = $e->getMessage();
            return back()->with('error', 'Something went wrong! Please contact admin');
        }
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
		]);
        try{	
    		$asset_category = AssetCategory::find($id);
    		$asset_category->name = $request->name;
    		$asset_category->description = $request->description;
    		$asset_category->status = 1;
    		$asset_category->user_sys = \Request::ip();
    		$asset_category->updated_by = Auth::id();
    		
    		$result = $asset_category->save();
    		
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
            $asset_category = AssetCategory::find($id);
            $result = $asset_category->delete($id);
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
}
