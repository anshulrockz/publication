<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClaimCategory;
use Auth;

class ClaimCategoryController extends Controller
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
    	$data = ClaimCategory::all();
        return view('claim-category.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('claim-category.create');
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
			'type_of_category'=>'required|max:255',
		]);

		$ClaimCategory = new ClaimCategory;
        $ClaimCategory->name = $request->name;
        $ClaimCategory->type_of_category = $request->type_of_category;
		$ClaimCategory->phone = $request->phone;
		$ClaimCategory->description = $request->description;
		$ClaimCategory->status = 1;
		$ClaimCategory->user_sys = \Request::ip();
		$ClaimCategory->updated_by = Auth::id();
		$ClaimCategory->created_by = Auth::id();
		
		$result = $ClaimCategory->save();
		
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
        $type_of_vehicle = ClaimCategory::where('type_of_category','2')->get();
        return view('claim-category.show')->with('type_of_vehicle', $type_of_vehicle);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $claim_category = ClaimCategory::find($id);
        return view('claim-category.edit')->with(array('claim_category'=> $claim_category));
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

		$ClaimCategory = ClaimCategory::find($id);
		$ClaimCategory->name = $request->name;
        $ClaimCategory->type_of_category = $request->type_of_category;
        $ClaimCategory->description = $request->description;
        $ClaimCategory->status = 1;
        $ClaimCategory->user_sys = \Request::ip();
        $ClaimCategory->updated_by = Auth::id();
        $ClaimCategory->created_by = Auth::id();
        
        $result = $ClaimCategory->save();
    		
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
        try{
            $asset_category = ClaimCategory::find($id);
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
