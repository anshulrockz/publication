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
                    <li><a href="{{ url('/users/'.$users->id) }}">{{$users->name}}</a></li>
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
                <form id="form" method="post" action="{{route('users.update',$users->id)}}" autocomplete="off">
                	{{ csrf_field() }}
	                {{ method_field('PUT') }}
	                <div class="row clearfix">
	                	<div class="col-sm-6">
		                    <label for="company">Company</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="company" name="company" required>
			                            <option value="">-- Please select company --</option>
			                            @foreach($companies as $list)
			                            <option value="{{$list->id}}" @if($list->id==$users->company_id){ selected="selected" } @endif >{{$list->name}}</option>
			                            @endforeach
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                	<div class="col-sm-6">
		                    <label for="workshop">Location</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="workshop" name="workshop" required>
			                            @foreach($workshops as $list)
			                            <option value="{{$list->id}}" @if($list->id==$users->workshop_id){ selected="selected" } @endif >{{$list->name}}</option>
			                            @endforeach
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    <!--<div class="col-sm-6">
		                    <label for="location">Location</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="location" name="location" >
			                            
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>-->
	                	<div class="col-sm-6">
		                    <label for="name">Name</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value="{{ $users->name }}" required>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="mobile">Mobile</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Enter mobile number" value="{{ $users->mobile }}" required>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="dob">Date Of Birth</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="dob" name="dob" class="form-control datepicker" placeholder="Enter Date Of Birth" value="{{ date_format(date_create($users->dob),"d F Y") }}" required>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="email">Email</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input autocomplete="off" type="text" id="email" name="email" class="form-control" placeholder="Enter an unique email id or user name" value="{{ $users->email }}" required>
		                        </div>
		                    </div>
	                    </div>
	                    
	                </div>
	                <div class="row clearfix">
	                	<div class="col-sm-6">
		                    <label for="password">Change Password</label>
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
			                            <option value="">-- Please select user type --</option>
			                            <option value="1" @if($users->user_type == 1) selected="selected" @endif >Super Admin</option>
			                            <option value="5" @if($users->user_type == 5) selected="selected" @endif >Supervisor</option>
			                            <option value="3" @if($users->user_type == 3) selected="selected" @endif >Workshop Admin</option>
			                            <option value="4" @if($users->user_type == 4) selected="selected" @endif >Workshop User</option>
			                            <option value="6" @if($users->user_type == 6) selected="selected" @endif >Insurer</option>
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    
	                    <div class="col-sm-6">
		                    <label for="designation">Designation</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="designation" name="designation" >
			                            <option value="">-- Please select designation --</option>
			                            @foreach($designations as $list1)
			                            <option value="{{$list1->id}}" @if($list1->id==$users->designation_id) selected="selected" @endif >{{$list1->name}}</option>
			                            @endforeach
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="address">Address</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <textarea id="address" name="address" rows="1" class="form-control no-resize auto-growth" placeholder="Enter address of person(press ENTER for more lines)" >{{ $users->address }}</textarea>
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
