<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentHistory;
use App\Expense;
use Auth;

class PaymentHistoryController extends Controller
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
        $data = PaymentHistory::all(); 
        
        if(Auth::user()->user_type == 4 || Auth::user()->user_type == 3)
            $data = PaymentHistory::all()->where('created_for',Auth::id());
            
        return view('payment-history.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = PaymentHistory::all();
        if(Auth::user()->user_type == 4 || Auth::user()->user_type == 3)
            $company = PaymentHistory::all()->where('created_for',Auth::id());
        return view('payment-history.create')->with('company',$company);
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
			'date'=>'required|max:100',
		]);

        try{
            $expense = Expense::where('voucher_no',$request->voucher_no)->first();
            $expense->mode = 1;
            $expense->paid_by = Auth::id();
            $expense->updated_by = Auth::id();
            $result = $expense->save();

    		$data = new PaymentHistory;

            $date = $request->date;
            $data->date = date_format(date_create($date),"Y-m-d");

    		$data->amount = $request->amount;
            $data->voucher_no = $request->voucher_no;
            $data->remark = $request->remark;
    		$data->user_sys = \Request::ip();
    		$data->status = 1;
            $data->created_for = $expense->created_by;
            $data->created_by = Auth::id();
    		$data->updated_by = Auth::id();
    		
    		$result = $data->save();
            
            if($result){
    			return back()->with('success', 'Request completed successfully!');
    		}
    		else{
    			return back()->with('error', 'Something went wrong!');
    		}
        }
        catch(\Exception $e){
            $error = $e->getMessage();
            return back()->with('error', 'Something went wrong! Please contact admin');
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
    	$data = PaymentHistory::find($id);
        return view('payment-history.show')->with('location', $data );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = PaymentHistory::find($id);
        return view('payment-history.edit')->with(array('location' => $data));
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
			'name'=>'required|max:100',
		]);
		
		$data = PaymentHistory::find($id);
		$data->name = $request->name;
		$data->user_sys = \Request::ip();
		$data->status = 1;
		$data->updated_by = Auth::id();
		
		$result = $data->save();
		
		if($result){
			return redirect()->back()->with('success', 'Record updated successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }

    public function destroy($id)
    {
        $data = PaymentHistory::find($id);
        $result = $data->delete($id);
        
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
    
    public function id_ajax(Request $request)
    {
		$data = $request->id;
		$data = PaymentHistory::where('workshop_id',$data)->get();//->pluck('name','id');
		print_r(json_encode($data));
	}
}
