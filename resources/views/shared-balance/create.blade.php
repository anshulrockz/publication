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
						$('#name option').remove();
					}
				}
			});
		}
	});
	$("#workshop").change(function(){
		var id = $(this).val();
		if(id != ''){
			$.ajax({
				type: "GET",
				url: "{{url('/users/ajax')}}",
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
			            $('#name').append(selOpts);
					}
					else{
						$('#name option').remove();
					}
				}
			});
		}
	});
});

$(document).ready(function() {
	$('.acc_no').hide();
	$('.ifsc').hide();
	$('.txn_no').hide();
});
function paymentMode(mode){
	if(mode == '3'){
		$('.acc_no').show();
		$('.ifsc').show();
		$('.txn_no').show();
	}
	else if(mode == '2'){
		$('.txn_no').show();
		$('.acc_no').hide();
		$('.ifsc').hide();
	}
	else if(mode == '1'){
		$('.txn_no').hide();
		$('.acc_no').hide();
		$('.ifsc').hide();
	}
	else{
		$('.acc_no').hide();
		$('.ifsc').hide();;
		$('.txn_no').hide();
	}
}
</script>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Shared Balance
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('dashboard') }}">Home</a></li>
                    <li><a href="{{ url('shared-balances') }}">Shared Balance</a></li>
                    <li class="active">Pay</li>
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
                {{$balance}}
            </div>
            <div class="body">
                <form method="post" action="{{route('shared-balances.store')}}">
                	{{ csrf_field() }}
                	 <div class="row clearfix">
                	 	@if(Auth::user()->user_type==1 || Auth::user()->user_type==5)
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
	                    @endif
	                    @if(Auth::user()->user_type==1 || Auth::user()->user_type==5)
	                    <div class="col-sm-6">
		                    <label for="workshop">Location</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="workshop" name="workshop">
			                            <option value="">-- Please select location --</option>
			                            @if(Auth::user()->user_type==2)
				                            @foreach($workshops as $list)
				                            <option value="{{$list->id}}">{{$list->name}}</option>
				                            @endforeach
			                            @endif
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    @endif
	                	<div class="col-sm-6">
		                    <label for="name">Payee</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" id="name" name="name" required>
			                            <option value="">-- Please select employee name --</option>
			                            @if(Auth::user()->user_type==4)
				                            @foreach($users as $list)
				                            <option value="{{$list->id}}">{{$list->name}}</option>
				                            @endforeach
			                            @endif
			                        </select>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="date">Date Of Payment</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="date" name="date" class="form-control datepicker" placeholder="Enter Date Of Payment" value="{{ date('d F Y') }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="amount">Amount</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="amount" name="amount" class="form-control" placeholder="&#x20B9;  Enter amount" value="{{ old('amount') }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="mode">Mode Of Payment</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" id="mode" name="mode" onchange="paymentMode(this.value);">
		                            	<!--<option value="">-- Please select mode of payment --</option>-->
			                            <option value="1">Cash</option>
			                            <option value="2">Cheque</option>
			                            <option value="3">Direct Transfer</option>
			                        </select>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 acc_no">
		                    <label for="acc_no">Account No</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="acc_no" name="acc_no" class="form-control" placeholder="Enter account no" value="{{ old('acc_no') }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ifsc">
		                    <label for="ifsc">IFSC</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="ifsc" name="ifsc" class="form-control" placeholder="Enter IFSC of bank acc" value="{{ old('ifsc') }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 txn_no">
		                    <label for="txn_no">Cheque/Transaction No.</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="txn_no" name="txn_no" class="form-control" placeholder="Enter Payment ID" value="{{ old('txn_no') }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-12">
		                    <label for="remarks">Remarks</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <textarea id="remarks" name="remarks" rows="1" class="form-control no-resize auto-growth" placeholder="Remarks if any... (press ENTER for more lines)">{{ old('remarks') }}</textarea>
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
