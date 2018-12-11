<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Designation;
use App\EmployeeType;
use App\Workshop;
use App\Location;
use App\User;
use App\Department;
use Auth;

class EmployeeController extends Controller
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
    	$employee = Employee::all();
        return view('employee.index')->with('employee',$employee);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {;
    	$employee_types = EmployeeType::all();
    	$workshops = Workshop::all();
    	$designations = Designation::all();
    	$hods = Department::all();
        return view('employee.create')->with(array('workshops' => $workshops, 'designations' => $designations, 'employee_types' => $employee_types, 'hods' => $hods,));
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
			'mobile'=>'required|max:255',
			'email'=>'required|max:255',
			'workshop'=>'required|max:255',
			'location'=>'required|max:255',
			'hod'=>'required|max:255',
			'employee_type'=>'required|max:255'
		]);
		
		$employee = new Employee;
		
		if(empty($request->hod_self))
		$employee->hod_self = 0;
		else
		$employee->hod_self = $request->hod_self;
		$date = $request->dob;
		$employee->dob = date_format(date_create($date),"Y-m-d");
		
		$employee->name = $request->name;
		$employee->mobile = $request->mobile;
		$employee->email = $request->email;
		$employee->workshop_id = $request->workshop;
		$employee->location_id = $request->location;
		$employee->employee_type = $request->employee_type;
		$employee->designation = $request->designation;
		$employee->hod_id = $request->hod;
		$employee->address = $request->address;
		$employee->status = 1;
		$employee->user_sys = 1;
		$employee->updated_by = Auth::id();
		$employee->created_by = Auth::id();
		$result = $employee->save();
		
		$user = new User;
		$date = $employee->dob;
		$user->dob = date_format(date_create($date),"Y-m-d");
		$user->employee_id = $employee->id;
		$user->user_type = $employee->employee_type;
		$user->name = $employee->name;
		$user->mobile = $employee->mobile;
		$user->email = $employee->email;
		$user->address = $employee->address;
		$user->status = 1;
		$result1 = $user->save();
		
		$employee->user_id = $user->id;
		$employee->save();
		
		if($result && $result1){
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
        $employee = Employee::find($id);
        $locations = Location::all();
    	$employee_types = EmployeeType::all();
        return view('employee.show')->with(array('employee' => $employee,'locations' => $locations,'employee_types' => $employee_types));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
        $employee_types = EmployeeType::all();
    	$workshops = Workshop::all();
    	$locations = Location::where('workshop_id',$employee->workshop_id);
    	$designations = Designation::all();
    	$hods = Department::all();
        return view('employee.edit')->with(array('employee' => $employee, 'workshops' => $workshops, 'designations' => $designations, 'employee_types' => $employee_types, 'hods' => $hods, 'locations' => $locations,));
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
			'mobile'=>'required|max:255',
			'email'=>'required|max:255',
			'workshop'=>'required|max:255',
			'location'=>'required|max:255',
			'hod'=>'required|max:255',
			'employee_type'=>'required|max:255'
		]);
		
		$employee = Employee::find($id);
		
		if(empty($request->hod_self))
		$employee->hod_self = 0;
		else
		$employee->hod_self = $request->hod_self;
		$date = $request->dob;
		$employee->dob = date_format(date_create($date),"Y-m-d");
		
		$employee->name = $request->name;
		$employee->mobile = $request->mobile;
		$employee->email = $request->email;
		$employee->workshop_id = $request->workshop;
		$employee->location_id = $request->location;
		$employee->employee_type = $request->employee_type;
		$employee->designation = $request->designation;
		$employee->hod_id = $request->hod;
		$employee->address = $request->address;
		$employee->status = 1;
		$employee->user_sys = 1;
		$employee->updated_by = Auth::id();
		
		$result = $employee->save();
		
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
        $employee = Employee::find($id);
        $result = $employee->delete($id);
        if($result){
			return redirect()->back()->with('success', 'Record deleted successfully!');
		}
		else{
			return redirect()->back()->with('error', 'Something went wrong!');
		}
    }
    
    public function id_ajax(Request $request)
    {
		$employee_id = $request->id;
		$employee = Employee::where('location_id',$employee_id)->get();
		print_r(json_encode($employee));
	}
}
