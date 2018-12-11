@extends('layouts.app')

@section('content')

<script>
$( document ).ready(function() {
    $("#form input").prop("disabled", true);
    $("#form select").prop("disabled", true);
    $("#form textarea").prop("disabled", true);
    $("#form-save").prop("disabled", true);
});

$(function() {
    $("#form-edit").click(function() {
        $("#form input").prop("disabled", false);
        $("#form select").prop("disabled", false);
        $("#form textarea").prop("disabled", false);
        $("#form-save").prop("disabled", false);
    });
});

</script>

<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Tax
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/taxes') }}">Tax</a></li>
                    <li><a href="{{ url('/taxes/'.$tax->id) }}">{{$tax->name}}</a></li>
                    <li class="active">Edit</li>
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
                    Details
                </h2>
            </div>
            <div class="body">
                <form id="form" method="post" action="{{route('taxes.update',$tax->id)}}">
                	{{ csrf_field() }}
	                {{ method_field('PUT') }}
	                <div class="row clearfix">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		                    <label for="name">Tax Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter hod name" value="{{ $tax->name }}" >
                                </div>
                            </div>
                            <label for="value">Tax Value</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="value" name="value" class="form-control" placeholder="Enter value" value="{{ $tax->value }}" >
		                        </div>
		                    </div>
	                    </div>
	                </div>
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <button type="submit" id="form-save" class="btn btn-primary waves-effect">Save</button>
                            <button type="button" id="form-edit" class="btn btn-primary waves-effect">Edit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
