<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExpenseCategory;
use App\SubExpense;
use App\SubSubExpense;
use Auth;

class SubSubExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$sub_sub_expense = SubSubExpense::expense_categoriesJoin();
        return view('sub_sub_expense_category.index')->with('sub_sub_expense', $sub_sub_expense);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$expense_category = ExpenseCategory::all();
    	$sub_expense = SubExpense::all();
        return view('sub_sub_expense_category.create')->with(array('expense_category' =>  $expense_category,'sub_expense' =>  $sub_expense));
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
			'expense_category'=>'required|max:255',
			'name'=>'required|max:255'
		]);
		
		$sub_sub_expense = new SubSubExpense;
		$sub_sub_expense->sub_expenses = $request->sub_expenses;
		$sub_sub_expense->name = $request->name;
		$sub_sub_expense->description = $request->description;
		$sub_sub_expense->status = 1;
		$sub_sub_expense->user_sys = \Request::ip();
		$sub_sub_expense->updated_by = Auth::id();
		$sub_sub_expense->created_by = Auth::id();
		
		$result = $sub_sub_expense->save();
		
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
        $sub_sub_expense = SubSubExpense::find($id);
        return view('sub_sub_expense_category.show')->with('sub_sub_expense', $sub_sub_expense);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense_category = ExpenseCategory::all();
        $sub_expense = SubExpense::all();
        $sub_sub_expense = SubSubExpense::find($id);
        return view('sub_sub_expense_category.edit')->with(array('sub_sub_expense' => $sub_sub_expense, 'sub_expense' => $sub_expense, 'expense_category' => $expense_category));
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
			'expense_category'=>'required|max:255'
		]);
		
		$sub_sub_expense = SubSubExpense::find($id);
		$sub_sub_expense->expense_category = $request->expense_category;
		$sub_sub_expense->name = $request->name;
		$sub_sub_expense->description = $request->description;
		$sub_sub_expense->status = 1;
		$sub_sub_expense->user_sys = \Request::ip();
		$sub_sub_expense->updated_by = Auth::id();
		
		$result = $sub_sub_expense->save();
		
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
        $sub_sub_expense = SubSubExpense::find($id);
        $result = $sub_sub_expense->delete($id);
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
    
    public function id_ajax(Request $request)
    {
		$expense_category = $request->id;
		$sub_expense = SubSubExpense::where('sub_expenses',$expense_category)->get();//->pluck('name','id');
		print_r(json_encode($sub_expense));
	}
}
