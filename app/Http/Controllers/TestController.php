<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Test;
use DataTables;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::super_admin_all(); //dd($tests);
        // return view('tests.index')->with('expense', $tests);
        return view('tests.index');
    }

    public function create()
    {
        return view('tests.add');
    }

    public function store(Request $request)
    {
		$this->validate($request,[
			'name'=>'required|max:255',
			'description'=>'nullable|max:255',
		]);
		
		$test = new Test;
		$test->name = $request->name;
		$test->description = $request->description;
		
		$result = $test->save();
		
		if($result){
			return back()->with('success', 'Record added successfully!');
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
    }

    public function show($id)
    {
        $test = Test::find($id);
        return view('tests.detail')->with('test', $test);
    }

    public function edit($id)
    {
        $test = Test::find($id);
        return view('tests.edit')->with('test', $test);
    }

    public function update(Request $request, $id)
    {
		$this->validate($request,[
			'name'=>'required|max:255',
			'description'=>'nullable|max:255',
		]);
		
		$test = Test::find($id);
		$test->name = $request->name;
		$test->description = $request->description;
		
		$result = $test->save();
		
		if($result){
			return redirect()->back()->with('success', 'Record updated successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }

    public function destroy($id)
    {
        $test = Test::find($id);
        $result = $test->delete($id);
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
    
    public function getPosts()
    { 
        return \DataTables::of(Expense::super_admin_all())->make(true);
    }
}
