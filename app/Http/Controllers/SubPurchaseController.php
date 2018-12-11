<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseCategory;
use App\SubPurchase;
use Auth;

class SubPurchaseController extends Controller
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
    	$sub_expense = SubPurchase::purchase_categoriesJoin();
        return view('sub_purchase_category.index')->with('sub_expense', $sub_expense);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$expense_category = PurchaseCategory::all();
        return view('sub_purchase_category.create')->with('expense_category', $expense_category);
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
		
		$sub_expense = new SubPurchase;
		$sub_expense->expense_category = $request->expense_category;
		$sub_expense->name = $request->name;
		$sub_expense->description = $request->description;
		$sub_expense->status = 1;
		$sub_expense->user_sys = \Request::ip();
		$sub_expense->updated_by = Auth::id();
		$sub_expense->created_by = Auth::id();
		
		$result = $sub_expense->save();
		
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
        $sub_expense = SubPurchase::find($id);
        return view('sub_purchase_category.show')->with('sub_expense', $sub_expense);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense_category = PurchaseCategory::all();
        $sub_expense = SubPurchase::find($id);
        return view('sub_purchase_category.edit')->with(array('sub_expense' => $sub_expense, 'expense_category' => $expense_category));
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
		
		$sub_expense = SubPurchase::find($id);
		$sub_expense->expense_category = $request->expense_category;
		$sub_expense->name = $request->name;
		$sub_expense->description = $request->description;
		$sub_expense->status = 1;
		$sub_expense->user_sys = \Request::ip();
		$sub_expense->updated_by = Auth::id();
		
		$result = $sub_expense->save();
		
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
        $sub_expense = SubPurchase::find($id);
        $result = $sub_expense->delete($id);
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
    
    public function ajax(Request $request)
    {
		$id = $request->id;
		$list = SubPurchase::where('expense_category',$id)->get();//->pluck('name','id');
		print_r(json_encode($list));
	}
}
