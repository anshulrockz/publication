<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseCategory;
use App\Department;
use Auth;

class PurchaseCategoryController extends Controller
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
    	$purchase_categories = PurchaseCategory::all();
        return view('purchase_category.index')->with('purchase_categories', $purchase_categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	// $department = Department::all();
        return view('purchase_category.create'); //->with('department', $department);
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
		
		$purchase_categories = new PurchaseCategory;
		$purchase_categories->name = $request->name;
		$purchase_categories->description = $request->description;
		$purchase_categories->status = 1;
		$purchase_categories->user_sys = \Request::ip();
		$purchase_categories->updated_by = Auth::id();
		$purchase_categories->created_by = Auth::id();
		
		$result = $purchase_categories->save();
		
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
        $purchase_categories = PurchaseCategory::find($id);
        return view('purchase_category.show')->with('purchase_categories', $purchase_categories);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase_categories = PurchaseCategory::find($id);
        $department = Department::all();
        return view('purchase_category.edit')->with(array('purchase_categories'=> $purchase_categories,'department'=> $department));
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
			'department'=>'required|max:255'
		]);
		
		$purchase_categories = PurchaseCategory::find($id);
		$purchase_categories->department_id = $request->department;
		$purchase_categories->name = $request->name;
		$purchase_categories->description = $request->description;
		$purchase_categories->status = 1;
		$purchase_categories->user_sys = \Request::ip();
		$purchase_categories->updated_by = Auth::id();
		
		$result = $purchase_categories->save();
		
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
        $purchase_categories = PurchaseCategory::find($id);
        $result = $purchase_categories->delete($id);
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
}
