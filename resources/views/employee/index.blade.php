@extends('layouts.app')

@section('content')
<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Employees
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="active">Employee</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    All
                </h2>
                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{ url('/employees/create')}}">Create New</a>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        	@foreach( $employee as $key=>$list)
                            <tr>
                                <td>{{$list->name}}</td>
                                <td>{{$list->location_id}}</td>
                                <td>{{$list->mobile}}</td>
                                <td>{{$list->email}}</td>
                                <td>
                                	@if($list->status==1){{"Active"}}
                                	@else {{"Inactive"}}
                                	@endif
                                </td>
                                <td>
                                    <a href="{{ url('/employees/'.$list->id)}}" class="btn btn-sm btn-success"> View </a>
                                    <a href="{{ url('/employees/'.$list->id.'/edit')}}" class="btn btn-sm btn-info"> Edit </a>
                                    <form style="display: inline;" method="post" action="{{route('employees.destroy',$list->id)}}">
				                        {{ csrf_field() }}
				                        {{ method_field('DELETE') }}
				                        <button onclick="return confirm('Are you sure you want to Delete?');" type="submit" class="btn btn-sm btn-danger">Delete</button>
				                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jquery DataTable Plugin Js -->
<script src="{{ asset('bsb/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
<script src="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>

<script src="{{ asset('bsb/js/pages/tables/jquery-datatable.js') }}"></script>

<script>
$(document).ready(function() {
$('.datatable').DataTable();
} );
</script>
@endsection
