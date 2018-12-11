@extends('layouts.app')

@section('content')
<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Employee
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/employees') }}">Employee</a></li>
                    <li class="active">{{$employee->name}}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <tbody>
                            <tr>
                            	<th>Name</th>
                                <td>{{$employee->name}}</td>
                            </tr>
                            <tr>
                            	<th>Mobile</th>
                                <td>{{$employee->mobile}}</td>
                            </tr>
                            <tr>
                            	<th>Phone</th>
                                <td>{{$employee->phone}}</td>
                            </tr>
                            <tr>
                            	<th>Email</th>
                                <td>{{$employee->email}}</td>
                            </tr>
                            <tr>
                            	<th>Date Of Birth</th>
                                <td>{{$employee->dob}}</td>
                            </tr>
                            <tr>
                            	<th>Location</th>
                                <td>{{$employee->location_id}}</td>
                            </tr>
                            <tr>
                            	<th>Employee Type</th>
                                <td>{{$employee->employee_type}}</td>
                            </tr>
                            <tr>
                            	<th>HOD</th>
                                <td>{{$employee->hod}}</td>
                            </tr>
                            <tr>
                            	<th>Address</th>
                                <td>{{$employee->address}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
