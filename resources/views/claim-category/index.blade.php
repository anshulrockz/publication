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
                    Claim Category
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="active">Claim Category</li>
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
                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{ url('claim-categories/create')}}">Create New</a>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>Category Type</th>
                                <th>Category</th>
                                <th>Description</th>
                                <!-- <th>Status</th> -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <!--<tfoot>
                            <tr>
                                <th>Asset Category</th>
                                <th>Sub Category</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>-->
                        <tbody>
                        	@foreach( $data as $key=>$list )
                            <tr>
                                <td>
                                    @if($list->type_of_category==1) {{"Make"}}
                                    @elseif($list->type_of_category==2) {{"Type of Vehicle"}}
                                    @elseif($list->type_of_category==3) {{"Document Verification"}}
                                    @elseif($list->type_of_category==4) {{"KYC Verification"}}
                                    @elseif($list->type_of_category==5) {{"Type of Customer"}}
                                    @elseif($list->type_of_category==6) {{"Insurer"}}
                                    @elseif($list->type_of_category==7) {{"Surveyor"}}
                                    @elseif($list->type_of_category==8) {{"Vehicle Location"}}
                                    @else{{"Others"}}
                                    @endif
                                </td>
                                <td>{{$list->name}}</td>
                                <td>{{$list->description}}</td>
                                <!-- <td>
                                	@if($list->status==1){{"Active"}}
                                	@else {{"Inactive"}}
                                	@endif
                                </td> -->
                                <td>
                                    <a href="{{ url('/claim-categories/'.$list->id.'/edit')}}" class="btn btn-xs btn-info"> <i class="material-icons">edit</i> </a>
                                    <form style="display: inline;" method="post" action="{{route('claim-categories.destroy',$list->id)}}">
				                        {{ csrf_field() }}
				                        {{ method_field('DELETE') }}
				                        <button onclick="return confirm('Are you sure you want to Delete?');" type="submit" class="btn btn-xs btn-danger"><i class="material-icons">delete</i></button>
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
