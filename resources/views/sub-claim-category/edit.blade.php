@extends('layouts.app')

@section('content')
<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
  
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
                    <li><a href="{{ url('/claim-categories') }}">Claim Category</a></li>
                    <li><a href="{{ url('/claim-categories/'.$claim_category->id) }}">{{$claim_category->name}}</a></li>
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
                <form method="post" action="{{route('claim-categories.update',$claim_category->id)}}">
                	{{ csrf_field() }}
	                {{ method_field('PUT') }}
                    <label for="asset_category">Type of Category</label>
                    <div class="form-group">
                        <select class="form-control show-tick" id="type_of_category" name="type_of_category" required>
                            <option value="">-- Please select --</option>
                            <option value="1" @if($claim_category->type_of_category==1) {{"selected"}} @endif >Make/Model</option>
                            <option value="2" @if($claim_category->type_of_category==5) {{"selected"}} @endif >Type of Customer</option>
                            <option value="2" @if($claim_category->type_of_category==2) {{"selected"}} @endif >Type of Vehicle</option>
                            <option value="3" @if($claim_category->type_of_category==3) {{"selected"}} @endif >Document Verification</option>
                            <option value="4" @if($claim_category->type_of_category==4) {{"selected"}} @endif >KYC Verification</option>
                        </select>
                    </div>
                    <label for="name">Category Name</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter expense category name" value="{{ $claim_category->name }}" >
                        </div>
                    </div>
                    <label for="description">Description</label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea id="description" name="description" rows="1" class="form-control no-resize auto-growth" placeholder="Enter description(press ENTER for more lines)">{{ $claim_category->description }}</textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Select Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

@endsection
