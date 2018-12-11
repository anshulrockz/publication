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
                    <li class="active">User</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		@include('layouts.flashmessage')
	</div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    All
                </h2>
                @if(auth::user()->user_type < 2)
                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{ url('/users/create')}}">Create New</a>
                @endif
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="dataTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>DOB</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>DOB</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach( $users as $key=>$list )
                            <tr>
                                <td>{{$list->name}}</td>
                                <td>{{$list->email}}</td>
                                <td>{{$list->mobile}}</td>
                                <td>{{date_format(date_create($list->dob),'d F Y')}}</td>
                                <td>
                                	@if($list->status==1){{"Active"}}
                                	@else {{"Inactive"}}
                                	@endif
                                </td>
                                <td>
                                    <a href="{{ url('/users/'.$list->id)}}" class="btn btn-sm btn-success"> View </a>
                                    @if(auth::user()->user_type < 2)
                                    <a href="{{ url('/users/'.$list->id.'/edit')}}" class="btn btn-sm btn-info"> Edit </a>
                                    <form style="display: inline;" method="post" action="{{route('users.destroy',$list->id)}}">
				                        {{ csrf_field() }}
				                        {{ method_field('DELETE') }}
				                        <button onclick="return confirm('Are you sure you want to Delete?');" type="submit" class="btn btn-sm btn-danger">Delete</button>
				                    </form>
				                    @endif
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
