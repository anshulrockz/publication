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
                    Banks
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/banks') }}">Banks</a></li>
                    <li>{{$bank->name}}</a></li>
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
                <form id="form" method="post" action="{{route('banks.update',$bank->id)}}">
                	{{ csrf_field() }}
	                {{ method_field('PUT') }}
	                <div class="row">
                        <div class="col-md-6">
                            <label for="name">Account Holder Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter company name" value="{{ $bank->name }}" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="acc_no">Account Number</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="acc_no" name="acc_no" class="form-control" placeholder="Enter account number" value="{{ $bank->acc_no }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="bank">Bank</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="bank" name="bank" class="form-control" placeholder="Enter bank name" value="{{ $bank->bank }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="ifsc">IFSC</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="ifsc" name="ifsc" class="form-control" placeholder="Enter ifsc " value="{{ $bank->ifsc }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="code">Branch Code</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="code" name="code" class="form-control" placeholder="Enter code" value="{{ $bank->branch_code }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="address">Branch Address</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea id="address" name="address" rows="1" class="form-control no-resize auto-growth" placeholder="Enter address(press ENTER for more lines)">{{ $bank->address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <label for="remark">Remark</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea id="remark" name="remark" rows="1" class="form-control no-resize auto-growth" placeholder="Enter remark(press ENTER for more lines)">{{ $bank->remark }}</textarea>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" id="form-save" class="btn btn-success waves-effect">Save</button>
                            <button type="button" id="form-edit" class="btn btn-default waves-effect">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
