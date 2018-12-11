@extends('layouts.app')

@section('content')
<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Workshops
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/workshops') }}">Workshops</a></li>
                    <li class="active">{{$workshop->name}}</li>
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
                            <!--<tr>
                            	<th>Company</th>
                                <td>{{$workshop->company}}</td>
                            </tr>-->
                            <tr>
                            	<th>Name</th>
                                <td>{{$workshop->name}}</td>
                            </tr>
                            <tr>
                            	<th>Code</th>
                                <td>{{$workshop->code}}</td>
                            </tr>
                            <tr>
                            	<th>Registration Number</th>
                                <td>{{$workshop->reg_no}}</td>
                            </tr>
                            <tr>
                            	<th>Mobile</th>
                                <td>{{$workshop->mobile}}</td>
                            </tr>
                            <tr>
                            	<th>Phone</th>
                                <td>{{$workshop->phone}}</td>
                            </tr>
                            <tr>
                            	<th>Email</th>
                                <td>{{$workshop->email}}</td>
                            </tr>
                            <tr>
                            	<th>Address</th>
                                <td>{{$workshop->address}}</td>
                            </tr>
                            <tr>
                            	<th>GST Number</th>
                                <td>{{$workshop->gst}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
