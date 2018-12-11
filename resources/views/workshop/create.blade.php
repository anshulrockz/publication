@extends('layouts.app')

@section('content')

<script>
$(document).ready(function(){
	$('#other').prop('disabled',true);
    $('.other').hide();
    $("#location_type").change(function(){
    	if($(this).val() == "other"){
	    	$('#other').prop('disabled',false);
	    	$('.other').show();
	    }
	    else{
	    	$('#other').prop('disabled',true);
    		$('.other').hide();
	    }
    });
});
</script>

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Location
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/locations') }}">Location</a></li>
                    <li class="active">Create New</li>
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
                <form method="post" action="{{ route('locations.store') }}">
                	{{ csrf_field() }}
                	<div class="row clearfix">
						<div class="col-lg-6 col-md-12 col-sm-6 col-xs-6">
							<label for="company">Company</label>
		                    <div class="form-group">
		                        <select class="form-control show-tick" id="company" name="company">
		                            @foreach($company as $list)
		                            <option value="{{$list->id}}">{{$list->name}}</option>
		                            @endforeach
		                        </select>
		                    </div>
			            </div>
			        </div>
			        <div class="row clearfix">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		                    <label for="name">Name</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="name" value="{{ old('name') }}" name="name" class="form-control" placeholder="Enter location name">
		                        </div>
		                    </div>
		                </div>
		                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		                    <label for="code">Code</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="code" value="{{ old('code') }}" name="code" class="form-control" placeholder="Enter Location code">
		                        </div>
		                    </div>
		                </div>
		                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		                    <label for="reg_no">Registration Number</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="reg_no" value="{{ old('reg_no') }}" name="reg_no" class="form-control" placeholder="Enter registration number">
		                        </div>
		                    </div>
		                </div>
		                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		                    <label for="mobile">Mobile</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="mobile" value="{{ old('mobile') }}" name="mobile" class="form-control" placeholder="Enter mobile number">
		                        </div>
		                    </div>
		                </div>
		                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		                    <label for="email">Email</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="email" value="{{ old('email') }}" name="email" class="form-control" placeholder="Enter email id">
		                        </div>
		                    </div>
		                </div>
		                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		                    <label for="gst">GST Number</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="gst" value="{{ old('gst') }}" name="gst" class="form-control" placeholder="Enter GST no">
		                        </div>
		                    </div>
		                </div>
			            <div class="col-lg-6 col-md-12 col-sm-6 col-xs-6">
							<label for="location_type">Location Type</label>
		                    <div class="form-group">
		                        <select class="form-control show-tick" id="location_type" name="location_type">
		                            <option value="workshop">Workshop</option>
		                            <option value="home">Home</option>
		                            <option value="reg_office">Reg. Office</option>
		                            <option value="other">Other</option>
		                        </select>
		                    </div>
			            </div>
			            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 other">
		                    <label for="other">Please Specify</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="other" value="{{ old('other') }}" name="other" class="form-control" placeholder="Enter location type" required>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <label for="address">Address</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <textarea id="address" value="{{ old('email') }}" name="address" rows="1" class="form-control no-resize auto-growth" placeholder="Enter address of workshop (press ENTER for more lines)"></textarea>
		                        </div>
		                    </div>
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
