<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bank;
use Auth;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
    	$bank = Bank::all();
        return view('bank.index')->with('bank',$bank);
    }
    
    public function create()
    {
        return view('bank.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
			'name'=>'required|max:255',
			'bank'=>'required|max:255',
			'acc_no'=>'required|max:255',
		]);
		
		$bank = new Bank;
		$bank->name = $request->name;
		$bank->acc_no = $request->acc_no;
		$bank->bank = $request->bank;
		$bank->ifsc = $request->ifsc;
		$bank->branch_code = $request->code;
		$bank->address = $request->address;
		$bank->remark = $request->remark;
		$bank->status = 1;
		$bank->user_sys = \Request::ip();
		$bank->updated_by = Auth::id();
		$bank->created_by = Auth::id();
		
		$result = $bank->save();
		
		if($result){
			return back()->with('success', 'Record added successfully!');
		}
		else{
			return back()->with('error', 'Something went wrong!');
		}
    }

    public function show($id)
    {
        $bank = Bank::find($id);
        return view('bank.show')->with('bank', $bank);
    }

    public function edit($id)
    {
        $bank = Bank::find($id);
        return view('bank.edit')->with('bank', $bank);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
			'name'=>'required|max:255',
			'bank'=>'required|max:255',
			'acc_no'=>'required|max:255',
		]);
		
		$bank = Bank::find($id);
		$bank->name = $request->name;
		$bank->acc_no = $request->acc_no;
		$bank->bank = $request->bank;
		$bank->ifsc = $request->ifsc;
		$bank->branch_code = $request->code;
		$bank->address = $request->address;
		$bank->remark = $request->remark;
		$bank->status = 1;
		$bank->user_sys = \Request::ip();
		$bank->updated_by = Auth::id();
		
		$result = $bank->save();
		
		if($result){
			return redirect()->back()->with('success', 'Record updated successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }

    public function destroy($id)
    {
    	$count1 = User::all()->where('Bank_id',$id)->count();
    	$count2 = Workshop::all()->where('bank',$id)->count();
    	if($count1==0 && $count2==0){
			$bank = Bank::find($id);
        	$result = $bank->delete($id);
        	
        	if($result){
				return redirect()->back()->with('success', 'Record deleted successfully!');
			}
			else{
				return redirect()->back()->with('error', 'Something went wrong!');
			}
		}
		
		else{
				return redirect()->back()->with('error', 'You cannot delete the Bank because workshop & users exist in Bank');
			}
        
    }
    
    public function id_ajax(Request $request)
    {
		$location = $request->id;
		$location = Bank::where('workshop_id',$location)->get();//->pluck('name','id');
		print_r(json_encode($location));
	}
}
