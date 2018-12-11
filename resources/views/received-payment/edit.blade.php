@extends('layouts.app')

@section('content')

<!-- Bootstrap Material Datetime Picker Css -->
<link href="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

<!-- AJAX DD Selecter for Location Js -->
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

$(document).ready(function() {
	
	var mode = $("#mode option:selected").val();
	if(mode == '3'){
		$('.card_no').show();
		$('.batch_no').show();
		$('.actual_amt').show();
		$('.ifsc').show();	
		$('.dod').show();
		$('.ref_no').hide();	
		$('.cheque_bank').hide();
		$('.party_name').hide();
	}
	else if(mode == '2'){
		$('.ref_no').show();
		$('.batch_no').hide();		
		$('.actual_amt').hide();		
		$('.dod').show();
		$('.party_name').show();
		$('.cheque_bank').show();
		$('.card_no').hide();
		$('.ifsc').hide();
	}
	else if(mode == '1' || mode == '4'){
		$('.dod').show();
		$('.batch_no').hide();		
		$('.ref_no').show();
		$('.card_no').hide();
		$('.actual_amt').hide();
		$('.ifsc').hide();
		$('.cheque_bank').hide();
		$('.party_name').hide();
	}
	else{
		$('.dod').hide();
		$('.batch_no').hide();		
		$('.actual_amt').hide();		
		$('.party_name').hide();
		$('.card_no').hide();
		$('.ifsc').hide();
		$('.ref_no').hide();
		$('.cheque_bank').hide();
	}
});
function paymentMode(mode){
	if(mode == '3'){
		$('.card_no').show();
		$('.batch_no').show();
		$('.actual_amt').show();
		$('.ifsc').show();	
		$('.dod').show();
		$('.ref_no').hide();	
		$('.cheque_bank').hide();
		$('.party_name').hide();
	}
	else if(mode == '2'){
		$('.ref_no').show();
		$('.batch_no').hide();		
		$('.actual_amt').hide();		
		$('.dod').show();
		$('.party_name').show();
		$('.cheque_bank').show();
		$('.card_no').hide();
		$('.ifsc').hide();
	}
	else if(mode == '1' || mode == '4'){
		$('.dod').show();
		$('.batch_no').hide();		
		$('.ref_no').show();
		$('.card_no').hide();
		$('.actual_amt').hide();
		$('.ifsc').hide();
		$('.cheque_bank').hide();
		$('.party_name').hide();
	}
	else{
		$('.dod').hide();
		$('.batch_no').hide();		
		$('.actual_amt').hide();		
		$('.party_name').hide();
		$('.card_no').hide();
		$('.ifsc').hide();
		$('.ref_no').hide();
		$('.cheque_bank').hide();
	}
}
</script>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Received Payment
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/received-payments') }}">Received Payment</a></li>
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
                <form id="form" method="post" action="{{route('received-payments.update',$paymentother->id)}}">
                	{{ csrf_field() }}
	                {{ method_field('PUT') }}
	                
	                <div class="row clearfix pannel-hide">
	                	<div class="col-sm-3">
		                    <label for="uid">Sr No.(auto)</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="uid" name="uid" class="form-control" placeholder="Enter number" value="{{ $paymentother->uid }}" disabled="">
		                        </div>
		                    </div>
	                    </div>
                	 	<div class="col-sm-3">
		                    <label for="voucher_date">Date</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="voucher_date" name="voucher_date" class="form-control datepicker" placeholder="Enter Date" value="{{  date_format(date_create($paymentother->created_at),'d F y') }}" disabled>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="company_bank">Company Bank</label>
		                    <div class="form-group">
		                        <div class="form-line">
									<select class="form-control show-tick" id="company_bank" name="company_bank">
			                            <option >select</option>
			                            @foreach($bank as $list)
			                            <option value="{{$list->id}}" @if($paymentother->bank_id == $list->id) selected @endif >{{$list->name}}</option>
			                            @endforeach
			                        </select>		                        
			                    </div>
		                    </div>
	                    </div> 
	                    <div class="col-sm-6 ">
		                    <label for="amount">Amount</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="amount" name="amount" class="form-control" placeholder="Enter amount" value="{{ $paymentother->amount }}" >
		                        </div>
		                    </div>
	                    </div> 
	                    <div class="col-sm-6 ">
		                    <label for="location_id">Location</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="location_id" name="location_id" @if(Auth::user()->user_type == 3 && Auth::user()->user_type == 4) disabled @endif>
			                            <option >select</option>
			                            @foreach($workshop as $list)
			                            <option value="{{$list->id}}" @if($paymentother->location_id == $list->id) selected @endif >{{$list->name}}</option>
			                            @endforeach
			                        </select>
		                    	</div>
	                    	</div>
	                    </div> 
	                    <div class="col-sm-3">
		                    <label for="mode">Mode Of Payment</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" id="mode" name="mode" onchange="paymentMode(this.value);" required>
		                            	<option value="">Please select mode of payment</option>
			                            <option value="1" @if($paymentother->mode == 1) selected @endif >Cash</option>
			                            <option value="2" @if($paymentother->mode == 2) selected @endif >Cheque</option>
			                            <option value="3" @if($paymentother->mode == 3) selected @endif >Card(CC/DC/Online)</option>
			                            <option value="4" @if($paymentother->mode == 4) selected @endif >Direct Transfer(NEFT/IMPS)</option>
			                        </select>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 dod">
		                    <label for="date">Received Payment Date</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="date" name="date" class="form-control datepicker" placeholder="Enter Date " value="{{ date_format(date_create($paymentother->date_deposit),'d F y') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 card_no">
		                    <label for="card_no">Card Number/Batch Number</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="card_no" name="card_no" class="form-control" placeholder="Enter card no" value="{{ $paymentother->card_no }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 actual_amt">
		                    <label for="actual_amt">Actual Amount</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="actual_amt" name="actual_amt" class="form-control" placeholder="Enter card no" value="{{ $paymentother->actual_amt }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 party_name">
		                    <label for="party_name">Party Name</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="party_name" name="party_name" class="form-control" placeholder="Enter Name of card holder" value="{{ $paymentother->party_name }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 ref_no">
		                    <label for="ref_no">Cheque/Ref No.</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="ref_no" name="ref_no" class="form-control" placeholder="Enter Payment ID" value="{{ $paymentother->ref_no }}">
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 cheque_bank">
		                    <label for="cheque_bank">Bank Name</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="cheque_bank" name="cheque_bank" class="form-control" placeholder="Enter bank name of Cheque" value="{{ $paymentother->cheque_bank }}">
		                        </div>
		                    </div>
	                    </div>
	                </div>
	                <div class="row clearfix">
	                    @if(!empty($expense->document))
	                    <div class="col-sm-3 ">
		                    <label for="voucher_img">Download Invoice</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
	                                <div class="fallback">
	                                    <a href="{{ asset('uploads/expenses/'.$expense->voucher_img)}}" download>
	                                    	<img border="0" src="{{ asset('uploads/expenses/'.$expense->voucher_img)}}" alt="W3Schools" width="100" height="100">
	                                    </a>
	                                </div>
			                    </div>
		                    </div>
	                    </div>
                    	@endif
	                    <div class="col-sm-6">
		                    <label for="remarks">Remark</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <textarea id="remarks" name="remarks" rows="1" class="form-control auto-growth" placeholder="Remarks if any... (press ENTER for more lines)">{{ $paymentother->remark }}</textarea>
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

<!-- Select Plugin Js ->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script-->

@endsection
