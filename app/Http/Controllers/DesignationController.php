<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Designation;
use Auth;

class DesignationController extends Controller
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
    	$designation = Designation::all();
        return view('designation.index')->with('designation',$designation);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$designation = Designation::all();
        return view('designation.create')->with(array('designation' => $designation,));
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
		
		$designation = new Designation;
		$designation->name = $request->name;
		$designation->description = $request->description;
		$designation->status = 1;
		$designation->user_sys = 1;
		$designation->updated_by = Auth::id();
		$designation->created_by = Auth::id();
		
		$result = $designation->save();
		
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
        $designation = Designation::find($id);
        return view('designation.show')->with(array('designation' => $designation,));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $designation = Designation::find($id);
        return view('designation.edit')->with(array('designation' => $designation,));
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
		
		$this->validate($request,[
			'name'=>'required|max:255'
		]);
		
		$designation = Designation::find($id);
		$designation->name = $request->name;
		$designation->description = $request->description;
		$designation->status = 1;
		$designation->user_sys = 1;
		$designation->updated_by = Auth::id();
		
		$result = $designation->save();
		
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
        $designation = Designation::find($id);
        $result = $designation->delete($id);
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
}
