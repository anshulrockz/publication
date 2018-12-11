@extends('layouts.app')

@section('content')

<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Companies
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/companies') }}">Companies</a></li>
                    <li class="active">{{$company->name}}</li>
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
                                <td>{{$company->name}}</td>
                            </tr>
                            <tr>
                            	<th>Contact Person</th>
                                <td>{{$company->Contact_person}}</td>
                            </tr>
                            <tr>
                            	<th>Mobile</th>
                                <td>{{$company->mobile}}</td>
                            </tr>
                            <tr>
                            	<th>Phone</th>
                                <td>{{$company->phone}}</td>
                            </tr>
                            <tr>
                            	<th>Email</th>
                                <td>{{$company->email}}</td>
                            </tr>
                            <tr>
                            	<th>Address</th>
                                <td>{{$company->address}}</td>
                            </tr>
                            <tr>
                            	<th>CIN</th>
                                <td>{{$company->cin}}</td>
                            </tr>
                            <tr>
                            	<th>GST Number</th>
                                <td>{{$company->gst}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
