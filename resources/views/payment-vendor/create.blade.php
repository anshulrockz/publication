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
					var selOpts = "<option>select</option>";
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
			$('#name option').remove();
			$.ajax({
				type: "GET",
				url: "{{url('/payment-vendors/ajax')}}",
				data:'id='+id,
				success: function(data){
					var data = JSON.parse(data);
					var selOpts = "<option>select</option>";
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
	$('.bank').hide();
	$('.epay_no').hide();
	$('.acc_no').hide();
	$('.ifsc').hide();
	$('.txn_no').hide();
});
function paymentMode(mode){
	if(mode == '3'){
		$('.bank').show();
		$('.epay_no').show();
		$('.acc_no').hide();
		$('.ifsc').hide();
		$('.txn_no').hide();
	}
	else if(mode == '2'){
		$('.bank').show();
		$('.epay_no').hide();
		$('.acc_no').hide();
		$('.ifsc').hide();
		$('.txn_no').show();
	}
	else if(mode == '4'){
		$('.bank').hide();
		$('.epay_no').hide();
		$('.acc_no').hide();
		$('.ifsc').hide();
		$('.txn_no').hide();
	}
	else if(mode == '1'){
		$('.bank').hide();
		$('.epay_no').hide();
		$('.acc_no').hide();
		$('.ifsc').hide();
		$('.txn_no').hide();
	}
	else{
		$('.bank').hide();
		$('.epay_no').hide();
		$('.acc_no').hide();
		$('.ifsc').hide();
		$('.txn_no').hide();
	}
}
</script>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Deposit
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/payment-vendors') }}">Payments</a></li>
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
                <b>
	                @if(isset($balance))
	                Balance({{$balance}})
	                @endif
                </b>
            </div>
            <div class="body">
                <form method="post" action="{{route('payment-vendors.store')}}">
                	{{ csrf_field() }}
                	 <div class="row clearfix">
                	 	<div class="col-sm-3">
	                		<label for="voucher_no">Sr. No.(auto)</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="voucher_no" name="voucher_no" class="form-control" placeholder="Enter voucher number" value="{{ $voucher_no }}" readonly>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
		                    <label for="voucher_date">Date</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="voucher_date" name="voucher_date" class="form-control" placeholder="Enter Date Of voucher" value="{{  date('d F Y') }}" readonly>
		                        </div>
		                    </div>
	                    </div>
	                	<div class="col-sm-6">
		                    <label for="name">Vendor Name</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" data-live-search="true" id="name" name="name" required>
			                            <option value="">-- Please select vendor name --</option>
				                            @foreach($vendor as $list)
				                            <option value="{{$list->id}}">{{$list->name}}(GSTIN-{{$list->gst}})</option>
				                            @endforeach
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
			                            <option value="4">Credit Note/Discount</option>
			                            <option value="5">TDS</option>
			                        </select>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 epay_no">
		                    <label for="epay_no">Epay No.</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="epay_no" name="epay_no" class="form-control" placeholder="  Enter epay_no" value="{{ old('epay_no') }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 bank">
	                    	<label for="bank">Bank</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" id="bank" name="bank">
                                        <option value="" >-- Please select category --</option>
                                        @foreach($bank as $list)
                                        <option value="{{$list->id}}">{{$list->name}}</option>
                                        @endforeach
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
		                    <label for="txn_no">Cheque No.</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="txn_no" name="txn_no" class="form-control" placeholder="Enter Payment ID" value="{{ old('txn_no') }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="voucher_img">Upload Document</label>
		                    <div class="form-group">
		                        <div class="form-line">
	                                <div class="fallback">
	                                    <input name="voucher_img" id="voucher_img" class="form-control" type="file" placeholder="img only" accept="image/x-png,image/gif,image/jpeg" />
	                                </div>
			                    </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6">
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
    
<!-- Select Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

@endsection
