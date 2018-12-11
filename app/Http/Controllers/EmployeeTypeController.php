<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmployeeType;
use Auth;

class EmployeeTypeController extends Controller
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
    	$employee_type = EmployeeType::all();
        return view('employee_type.index')->with('employee_type',$employee_type);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employee_type.create');
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
			'name'=>'required|max:255'
		]);
		
		$employee_type = new EmployeeType;
		$employee_type->name = $request->name;
		$employee_type->description = $request->description;
		$employee_type->status = 1;
		$employee_type->user_sys = 1;
		$employee_type->updated_by = Auth::id();
		$employee_type->created_by = Auth::id();
		
		$result = $employee_type->save();
		
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
        $employee_type = EmployeeType::find($id);
        return view('employee_type.show')->with('employee_type', $employee_type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee_type = EmployeeType::find($id);
        return view('employee_type.edit')->with('employee_type', $employee_type);
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
			'name'=>'required|max:255'
		]);
		
		$employee_type = EmployeeType::find($id);
		$employee_type->name = $request->name;
		$employee_type->description = $request->description;
		$employee_type->status = 1;
		$employee_type->user_sys = 1;
		$employee_type->updated_by = Auth::id();
		
		$result = $employee_type->save();
		
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
        $employee_type = EmployeeType::find($id);
        $result = $employee_type->delete($id);
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
}
