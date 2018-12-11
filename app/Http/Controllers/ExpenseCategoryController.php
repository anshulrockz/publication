<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExpenseCategory;
use App\Department;
use Auth;

class ExpenseCategoryController extends Controller
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
    	$expense_category = ExpenseCategory::all();
        return view('expense_category.index')->with('expense_category', $expense_category);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	
        return view('expense_category.create');
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
		
		$expense_category = new ExpenseCategory;
        $expense_category->supply_type = $request->expense_category;
        $expense_category->supply_category = $request->sub_expenses;
		$expense_category->name = $request->name;
		$expense_category->description = $request->description;
		$expense_category->status = 1;
		$expense_category->user_sys = \Request::ip();
		$expense_category->updated_by = Auth::id();
		$expense_category->created_by = Auth::id();
		
		$result = $expense_category->save();
		
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
        $expense_category = ExpenseCategory::find($id);
        return view('expense_category.show')->with('expense_category', $expense_category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense_category = ExpenseCategory::find($id);
        return view('expense_category.edit')->with(array('expense_category'=> $expense_category));
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
		
		$expense_category = ExpenseCategory::find($id);
		$expense_category->supply_type = $request->expense_category;
        $expense_category->supply_category = $request->sub_expenses;
        $expense_category->name = $request->name;
        $expense_category->description = $request->description;
        $expense_category->status = 1;
        $expense_category->user_sys = \Request::ip();
        $expense_category->updated_by = Auth::id();
        $expense_category->created_by = Auth::id();
        
		$result = $expense_category->save();
		
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
        $expense_category = ExpenseCategory::find($id);
        $result = $expense_category->delete($id);
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }

    public function id_ajax(Request $request)
    {
        $list = ExpenseCategory::where('id',$request->id)->first();
        print_r(json_encode($list));
    }
}
