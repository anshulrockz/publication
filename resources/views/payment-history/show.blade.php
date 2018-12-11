@extends('layouts.app')

@section('content')
<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Locations
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/locations') }}">Locations</a></li>
                    <li class="active">{{$location->name}}</li>
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
                            	<th>Workshop</th>
                                <td>{{$location->workshop_id}}</td>
                            </tr>-->
                            <tr>
                            	<th>Name</th>
                                <td>{{$location->name}}</td>
                            </tr>
                            <tr>
                            	<th>Contact Person</th>
                                <td>{{$location->contact_person}}</td>
                            </tr>
                            <tr>
                            	<th>Mobile</th>
                                <td>{{$location->mobile}}</td>
                            </tr>
                            <tr>
                            	<th>Phone</th>
                                <td>{{$location->phone}}</td>
                            </tr>
                            <tr>
                            	<th>Address</th>
                                <td>{{$location->address}}</td>
                            </tr>
                            <tr>
                            	<th>Remark</th>
                                <td>{{$location->remark}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
