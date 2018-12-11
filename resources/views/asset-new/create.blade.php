@extends('layouts.app')

@section('content')

<!-- Bootstrap Material Datetime Picker Css -->
<link href="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

<!-- Dropzone Css -->
<link href="{{ asset('bsb/plugins/dropzone/dropzone.css')}}" rel="stylesheet"/>

<!-- AJAX DD Selecter for Location Js -->
<script>
$(function(){
	$("#asset_category").change(function(){
		var id = $(this).val();
		var name = $('#asset_category option:selected').text();
		$('#sub_asset option').remove();
			$.ajax({
				type: "GET",
				url: "{{url('assets/new/ajax_voucher_no')}}",
				data:'category='+name,
				success: function(data){
					var data = data;
					$('input#voucher_no').val(data);
					if (name == 'vehicle' || name == 'Vehicle'|| name == 'VEHICLE'){
						$('.vehicle').show();
						$('.pannel-hide').show();
					}

					else if(id == ""){
						$('.vehicle').hide();
						$('.pannel-hide').hide();
					}

					else{
						$('.vehicle').hide();
						$('.pannel-hide').show();
					}
				}
			});
			$.ajax({
				type: "GET",
				url: "{{url('subassets/ajax')}}",
				data:'id='+id,
				success: function(data2){
					var data2 = JSON.parse(data2);
					if(data2.length >0){
						$('.sub_asset').show();
						console.log(data2);
			            for (var i in data2)
			            {
			                var id = data2[i].id;
			                var val = data2[i].name;
			                $('#sub_asset').append("<option value='"+id+"'>"+val+"</option>");
			            }
					}
					else{
						$('.sub_asset').hide();
					}
				}
			});
		
	});
});

$(document).ready(function() {
	$('.vehicle').hide();
	$('.sub_asset').hide();
	$('.pannel-hide').hide();
});
</script>

<script>

$(function(){
	$('.pannel-hide').on('keyup', '#amount', function(e){
		e.preventDefault();

		var cost = $('#amount').val();
    	var tax = $('#tax').val();
    	var total_tax = (cost*tax)/100;
    	var total_amount = parseFloat(total_tax)+parseFloat(cost);

		$('#total_tax').prop('disabled', true);
		$('#total_amount').prop('disabled', true);
		$('#total_tax').val(parseFloat(total_tax).toFixed(2));
    	$('#total_amount').val(parseFloat(total_amount).toFixed(2));

	}); 

	$('.pannel-hide').on('change', '#tax', function(e){
		e.preventDefault();

		var cost = $('#amount').val();
    	var tax = $('#tax').val();
    	var total_tax = (cost*tax)/100;
    	var total_amount = parseFloat(total_tax)+parseFloat(cost);

    	if(total_amount == "") total_amount = 0;

		$('#total_tax').prop('disabled', true);
		$('#total_amount').prop('disabled', true);
		$('#total_tax').val(parseFloat(total_tax).toFixed(2));
    	$('#total_amount').val(parseFloat(total_amount).toFixed(2));

	}); 
});    
</script>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Asset New
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('dashboard') }}">Home</a></li>
                    <li><a href="{{ url('assets/new') }}">Asset New</a></li>
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
                <form method="post" action="{{route('new.store')}}" enctype="multipart/form-data">
                	{{ csrf_field() }}
                	<div class="row clearfix">
	                	<div class="col-sm-6 ">
		                    <label for="asset_category">Asset Category</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="asset_category" name="asset_category">
			                            <option value="" >-- Please select asset category --</option>
			                            @foreach($asset_category as $list)
			                            <option value="{{$list->id}}">{{$list->name}}</option>
			                            @endforeach
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    <div class="col-sm-6 sub_asset">
		                    <label for="sub_asset">Sub Category</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="sub_asset" name="sub_asset">
			                            <option value="" >-- Please select sub category --</option>
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                 </div>
                	 <div class="row clearfix pannel-hide">
	                	<div class="col-sm-3">
		                    <label for="voucher_no">Asset No.(auto)</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="voucher_no" name="voucher_no" class="form-control" placeholder="Enter voucher number" value="{{ $voucher_no }}" disabled="">
		                        </div>
		                    </div>
	                    </div>
                	 	<div class="col-sm-3">
		                    <label for="voucher_date">Date</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="voucher_date" name="voucher_date" class="form-control datepicker" placeholder="Enter Date Of voucher" value="{{  date('d F Y') }}" disabled>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
		                    <label for="invoice_no">Invoice No.</label>
		                    <div class="form-group">
		                        <div class="form-line focused">
		                            <input type="text" id="invoice_no" name="invoice_no" class="form-control" placeholder="Enter asset Invoice No" value="{{ old('invoice_no') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
		                    <label for="invoice_date">Invoice Date</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="invoice_date" name="invoice_date" class="form-control datepicker" placeholder="Enter Date Of Invoice" value="{{ old('invoice_date') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="location">Location</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="location" name="location">
			                            <option value="" >-- Please select asset location --</option>
			                            @foreach($location as $list)
			                            <option value="{{$list->id}}">{{$list->name}}</option>
			                            @endforeach
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="party_name">Seller Name</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="party_name" name="party_name" class="form-control" placeholder="Enter seller name" value="{{ old('party_name') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="party_gstin">Seller GSTIN</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="party_gstin" name="party_gstin" class="form-control" placeholder="Enter seller GSTIN" value="{{ old('party_gstin') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="make">Make</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="make" name="make" class="form-control" placeholder="Enter make/brand" value="{{ old('make') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="model">Model</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="model" name="model" class="form-control" placeholder="Enter model" value="{{ old('model') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="mfg">Manufacturing Year</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" id="mfg" name="mfg">
			                            <option value="" >-- Please select manufacturing year --</option>
			                            @foreach(range(1990, date('Y') ) as $list)
			                            <option value="{{$list}}"> {{ $list }} </option>
			                            @endforeach
			                        </select>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="expiry">Warranty</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="expiry" name="expiry" class="form-control datepicker" placeholder="Enter warranty or validity date" value="{{ old('expiry') }}" required>
		                        </div>
		                    </div>
	                    </div>
	                	<div class="col-sm-6 ">
		                    <label for="hsn_code">HSN/SAC Code</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="hsn_code" name="hsn_code" class="form-control" placeholder="Enter hsn_code no" value="{{ old('hsn_code') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <!-- <div class="col-sm-6 ">
		                    <label for="amount">Asset Value (Aprox.)</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="amount" name="amount" class="form-control" placeholder="Enter aprox amount" value="{{ old('amount') }}" required>
		                        </div>
		                    </div>
	                    </div> -->
	                </div>
	                <div class="row clearfix vehicle">
	                	<div class="col-sm-6 ">
		                    <label for="registration">Registration No.</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="registration" name="registration" class="form-control" placeholder="Enter registration no" value="{{ old('registration') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="insurance">Insurance Validity</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="insurance" name="insurance" class="form-control datepicker" placeholder="Enter insurance validity" value="{{ old('insurance') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="puc">PUC Validity</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="puc" name="puc" class="form-control datepicker" placeholder="Enter puc validity" value="{{ old('puc') }}" >
		                        </div>
		                    </div>
	                    </div>
	                </div>
	                <div class="row clearfix pannel-hide">
	                    <div class="col-sm-3 ">
		                    <label for="amount">Base Value</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="number" id="amount" name="amount" class="form-control " placeholder="Enter cost" value="{{ old('amount') }}" required>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 ">
		                    <label for="tax">Tax %</label>
		                    <div class="form-group">
		                        <select class="form-control show-tick" id="tax" name="tax">
		                            <option value="0" >0</option>
		                            @foreach($tax as $list)
		                            <option value="{{$list->value}}"> {{ $list->value }} </option>
		                            @endforeach
		                        </select>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 ">
		                    <label for="total_tax">Tax Value</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="total_tax" name="total_tax" class="form-control " value="{{ old('total_tax') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 ">
		                    <label for="total_amount">Total Amount</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="total_amount" name="total_amount" class="form-control " value="{{ old('total_amount') }}" >
		                        </div>
		                    </div>
	                    </div>
	                </div>
	                <div class="row clearfix pannel-hide">
	                    <div class="col-sm-3 ">
		                    <label for="voucher_img">Upload Invoice</label>
		                    <div class="form-group">
		                        <div class="form-line">
	                                <div class="fallback">
	                                    <input name="voucher_img" id="voucher_img" class="form-control" type="file" placeholder="img only" accept="image/x-png,image/gif,image/jpeg" />
	                                </div>
			                    </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 ">
		                    <label for="status">Status</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="status" name="status">
			                            <option value="In use"  >In use</option>
			                            <option value="Discarded" >Discarded</option>
			                        </select>
		                    	</div>
	                    	</div>
	                    </div>
	                    <div class="col-sm-6">
		                    <label for="remarks">Remark</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <textarea id="remarks" name="remarks" rows="1" class="form-control no-resize auto-growth" placeholder="Remarks if any... (press ENTER for more lines)">{{ old('remarks') }}</textarea>
		                        </div>
		                    </div>
	                    </div>
	                </div>
	                <div class="row clearfix pannel-hide">
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

<!-- Dropzone Plugin Js -->
<script src="{{ asset('bsb/plugins/dropzone/dropzone.js')}}"></script>

<script>
	$('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });
    
    Dropzone.options.frmFileUpload = {
        paramName: "file",
        maxFilesize: 2
    };
</script>
    
<!-- Select Plugin Js -> -->
<!-- <script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script> -->

@endsection
