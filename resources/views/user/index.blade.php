@extends('layouts.app')

@section('content')

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css" rel="stylesheet"/>

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
                @if(auth::user()->user_type == 1 || auth::user()->user_type == 3 || auth::user()->user_type == 5)
                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{ url('/users/create')}}">Create New</a>
                @endif
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="dataTable">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Location</th>
                                <th>Name</th>
                                <th>User Id</th>
                                <th>Mobile</th>
                                <th>Last Login</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <!--<tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>DOB</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>-->
                        <tbody>
                            @foreach( $users as $key=>$list )
                            <tr>
                                <td>{{$list->company}}</td>
                                <td>{{$list->workshop}}</td>
                                <td>{{$list->name}}</td>
                                <td>{{$list->email}}</td>
                                <td>{{$list->mobile}}</td>
                                <td>
                                	@if($list->last_login_at == null) New User
                                	@else {{ date_format(date_create($list->last_login_at),'d/m/Y H:i')}}
                                	@endif
                                </td>
                                <td style="width: 65px">
                                    
                                    @if($list->deleted_at == '' || $list->deleted_at == '0000-00-00 00:00:00')
                                    <a href="{{ url('/users/'.$list->id.'/edit')}}" class="btn btn-xs btn-info"> <i class="material-icons">edit</i> </a>
                                    <form style="display: inline;" method="post" action="{{route('users.destroy',$list->id)}}">
				                        {{ csrf_field() }}
				                        {{ method_field('DELETE') }}
				                        <button onclick="return confirm('Are you sure you want to Delete?');" type="submit" class="btn btn-xs btn-danger" title="Deactivate"><i class="material-icons">lock_outline</i></button>
				                    </form>
                                    @else
                                    <a title="Activate" href="{{ url('users/activate-user/'.$list->id)}}" class="btn btn-xs btn-success"> <i class="material-icons">lock_open</i> </a>
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
<script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>

<!-- Select Plugin Js -->
    <script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

<script>
$(document).ready(function() {
    $('.dataTable').DataTable( {
        "order": [[ 1, "desc" ]],
        fixedHeader: {
            header: true,
            headerOffset: $('#navbar-collapse').height()
        }
    } );
} );
</script>
@endsection
