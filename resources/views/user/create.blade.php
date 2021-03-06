@extends('layouts.app')

@section('content')

<!-- Bootstrap Material Datetime Picker Css -->
<link href="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

<!-- AJAX DD Selecter for Location Js -->
<script>
$(function(){
	$("#company").change(function(){
		var id = $(this).val();
		if(id != ''){
			$('#workshop option').remove();
			$.ajax({
				type: "GET",
				url: "{{url('/workshops/ajax')}}",
				data:'id='+id,
				success: function(data){
					var data = JSON.parse(data);
					var selOpts = "";
					if(data.length >0){					
						console.log(data);
			            for (i=0;i<data.length;i++)
			            {
			                var id = data[i].id; 
			                var val = data[i].name;
			                selOpts += "<option value='"+id+"'>"+val+"</option>";
			            }
			            $('#workshop').append(selOpts);
					}
					else{
						$('#workshop option').remove();
					}
				}
			});
		}
	});
});
</script>

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
                    <li><a href="{{ url('/users') }}">User</a></li>
                    <li class="active">Create</li>
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
                <form method="post" action="{{route('users.store')}}" autocomplete="off">
                	{{ csrf_field() }}
	                <div class="row clearfix">
	                	@if(Auth::user()->user_type == 1)
	                	<div class="col-sm-6">
		                    <label for="company">Company</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="company" name="company" required>
			                            <option value="">-- Please select company --</option>
			                            @foreach($companies as $list)
			                            <option value="{{$list->id}}">{{$list->name}}</option>
			                            @endforeach
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="workshop">Location</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="workshop" name="workshop">
			                            <option value="">-- Please select location --</option>
			                            
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    @endif
	                    <!--<div class="col-sm-6">
		                    <label for="location">Location</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="location" name="location" >
			                            <option value="">-- Please select location --</option>
			                            @if(Auth::user()->user_type == 2)
			                            @foreach($locations as $list)
			                            <option value="{{$list->id}}">{{$list->name}}</option>
			                            @endforeach
			                            @endif
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>-->
	                	<div class="col-sm-6">
		                    <label for="name">Name</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value="{{ old('name') }}" required>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="mobile">Mobile</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Enter mobile number" value="{{ old('mobile') }}" required>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="dob">Date Of Birth</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="dob" name="dob" class="form-control datepicker" placeholder="Enter Date Of Birth" value="{{ old('dob') }}" required>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="email">Email</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input autocomplete="off" type="text" id="email" name="email" class="form-control" placeholder="Enter an unique email id" value="{{ old('email') }}" required>
		                        </div>
		                    </div>
	                    </div>
	                </div>
	                <div class="row clearfix">
	                	<div class="col-sm-6">
		                    <label for="password">New Password</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter new password" required>
		                        </div>
		                    </div>
	                	</div>
	                	<div class="col-sm-6">
		                    <label for="c_password">Confirm Password</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Renter confirm password" required>
		                        </div>
		                    </div>
	                	</div>
	                </div>
	                <div class="row clearfix">
	                	
	                    
	                    <div class="col-sm-6">
		                    <label for="employee_type">User Type</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="employee_type" name="employee_type" required>
			                        	@if(Auth::user()->user_type == 1)
			                            <option value="">-- Please select User Type --</option>
			                            <option value="1">Super Admin</option>
			                            <option value="5">Supervisor</option>
			                            <option value="6">Insurer</option>
			                            <option value="3">Workshop Admin</option>
			                            @endif
			                            <option value="4">Workshop User</option>
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    
	                    <div class="col-sm-6">
		                    <label for="designation">Designation</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="designation" name="designation" required>
			                            <option value="">-- Please select designation --</option>
			                            @foreach($designations as $list1)
			                            <option value="{{$list1->id}}">{{$list1->name}}</option>
			                            @endforeach
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="address">Address</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <textarea id="address" name="address" rows="1" class="form-control no-resize auto-growth" placeholder="Enter address of person(press ENTER for more lines)" required>{{ old('address') }}</textarea>
		                        </div>
		                    </div>
	                    </div>
                    </div>
	                <div class="row clearfix">
	                	<div class="col-sm-6">
	                		<button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
	                	</div>
	                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Moment Plugin Js -->
<script src="{{ asset('bsb/plugins/momentjs/moment.js')}}"></script>

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>

<script>
	$('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });
</script>
    
<!--<!-- Select Plugin Js ->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>-->

@endsection
