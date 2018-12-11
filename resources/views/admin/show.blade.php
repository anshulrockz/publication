@extends('layouts.app')

@section('content')

<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    User
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/users') }}">User</a></li>
                    <li class="active">{{$users->name}}</li>
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
                                <td>{{$users->name}}</td>
                            </tr>
                            <tr>
                            	<th>Mobile</th>
                                <td>{{$users->mobile}}</td>
                            </tr>
                            <tr>
                            	<th>Email</th>
                                <td>{{$users->email}}</td>
                            </tr>
                            <tr>
                            	<th>Date Of Birth</th>
                                <td>{{$users->dob}}</td>
                            </tr>
                            <tr>
                            	<th>Address</th>
                                <td>{{$users->address}}</td>
                            </tr>
                            @if($users->employee_id>0)
                            <tr>
                            	<th>Employee ID</th>
                                <td>{{$users->employee_id}}</td>
                            </tr>
                            @endif
                            <tr>
                            	<th>User Type</th>
                                <td>{{$employeetype->name}}</td>
                            </tr>
                            <tr>
                            	<th>Company</th>
                                <td>{{$workshop->name}}</td>
                            </tr>
                            <tr>
                            	<th>Designation</th>
                                <td>{{$designation->name}}</td>
                            </tr>
                            <tr>
                            	<th>Department</th>
                                <td>{{$department->name}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
