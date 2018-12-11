<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tax;
use Auth;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
    	$tax = Tax::all();
        return view('tax.index')->with('tax',$tax);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tax.create');
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
			'value'=>'numeric|required|max:255'
		]);
		
		$tax = new tax;
		$tax->name = $request->name;
        $tax->value = $request->value;
		$tax->status = 1;
		$tax->user_sys = 1;
		$tax->updated_by = Auth::id();
		$tax->created_by = Auth::id();
		
		$result = $tax->save();
		
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
        $tax = Tax::find($id);
        return view('tax.show')->with('tax', $tax);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tax = Tax::find($id);
        return view('tax.edit')->with('tax', $tax);
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
			'value'=>'numeric|required|max:100'
		]);
		
		$tax = Tax::find($id);
        $tax->name = $request->name;
		$tax->value = $request->value;
		$tax->status = 1;
		$tax->user_sys = 1;
		$tax->updated_by = Auth::id();
		
		$result = $tax->save();
		
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
        $result = Tax::find($id)->delete($id);
        
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
}
