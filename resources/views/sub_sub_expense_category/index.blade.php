@extends('layouts.app')

@section('content')
<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Expense Category
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="active">Expense Category</li>
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
                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{ url('expense-categories/expense-category/create')}}">Create New</a>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>Supply Type</th>
                                <th>Supply Category</th>
                                <th>Expense Category</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead><!-- 
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Name</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot> -->
                        <tbody>
                        	@foreach( $sub_sub_expense as $key=>$list )
                            <tr>
                                <td>{{$list->expense_categories_name}}</td>
                                <td>{{$list->sub_expenses_name}}</td>
                                <td>{{$list->name}}</td>
                                <td>{{$list->description}}</td>
                                <td>
                                    <!-- <a href="{{ url('/expense-category/'.$list->id)}}" class="btn btn-sm btn-success"> View </a> -->
                                    <a href="{{ url('expense-categories/expense-category/'.$list->id.'/edit')}}" class="btn btn-sm btn-info"> <i class="material-icons">edit</i> </a>
                                    <form style="display: inline;" method="post" action="{{route('expense-category.destroy',$list->id)}}">
				                        {{ csrf_field() }}
				                        {{ method_field('DELETE') }}
				                        <button onclick="return confirm('Are you sure you want to Delete?');" type="submit" class="btn btn-sm btn-danger"><i class="material-icons">delete</i></button>
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
