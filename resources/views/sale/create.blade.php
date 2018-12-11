@extends('layouts.app')

@section('content')

<!-- Bootstrap Material Datetime Picker Css -->
<link href="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

<!-- Autofill -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- AJAX DD Selecter for Category Js -->

<script>

$(function(){
    $("#expense_category_main").change(function(){
    	var id = $(this).val(); 
    	if(id != ''){
    		$('#description_main option').remove();
    		$.ajax({
    			type: "GET",
    			url: "{{url('/subexpenses/ajax')}}",
    			data:'id='+id,
    			success: function(data){
    				var data = JSON.parse(data);
    				var selOpts = "<option>-- select --</option>";
    				if(data.length >0){					
    					console.log(data);
    		            for (i=0;i<data.length;i++)
    		            {
    		                var id = data[i].id; 
    		                var val = data[i].name;
    		                selOpts += "<option value='"+id+"'>"+val+"</option>";
    		            }
    		            $('#description_main').append(selOpts);
    		            $('#description_main').selectpicker('refresh');
    				}
    				else{
    					$('#description_main option').remove();
    				}
    			}
    		});
    	}
    	else{
    			$('#sub_expenses option').remove();
    		}
    });
});

$(function(){
    $("#location").change(function(){
    	var id = $(this).val();
    	if(id != ''){
    		$('#vendor_id option').remove();
    		$.ajax({
    			type: "GET",
    			url: "{{url('/vendors/ajax')}}",
    			data:'id='+id,
    			success: function(data){
    				var data = JSON.parse(data);
    				var selOpts = "<option>-- select --</option>";
    				if(data){					
    					console.log(data);
    		            for (i=0;i<data.length;i++)
    		            {
    		                var id = data[i].id; 
    		                var val = data[i].name;
    		                var gst = data[i].gst;
    		                selOpts += "<option value='"+id+"'>"+val+"(GSTIN#"+gst+"#)</option>";
    		            }
    		            $('#vendor_id').append(selOpts);
    		            $('#vendor_id').selectpicker('refresh');
    				}
    				else{
    					$('#vendor_id option').remove();
    				}
    			}
    		});
    	}
    	else{
    			$('#vendor_id option').remove();
    		}
    });
});
</script>

<script>

$(function(){
	$("#radio_3").change(function(){
		if ($("#radio_3:checked")) {
            $(".created_for").hide();
        }
	});

	$("#radio_4").change(function(){
		if ($("#radio_4:checked")) {
            $(".created_for").show();
        }
	});

	$(".add-row").click(function(){
		var id = $('#expense_category_main option:selected').val();
    	var reason = $('#reason_main').val().toUpperCase(); 
    	var cost = $('#cost_main').val();
    	var quantity = $('#quantity_main').val();
    	var description = $('#description_main option:selected').text();
    	var code = $('#code_main').val();
    	var vendor_id = $('#vendor_id option:selected').val();
    	// var discount = $('#discount').val();
    	// alert(vendor_id);
        reason = reason.toUpperCase();
        
		if(vendor_id == '' || vendor_id == 0){ alert("Please select vendor") }
		else if(id == ''){ alert("Please select category") }
		else if(description == '' || description == '--select--'){ alert("Please select sub category") }
		else if(reason == ''){ alert("Please enter Description") }
		else if(cost == ''){ alert("Please enter base value") }
		else if(quantity == ''){ alert("Please enter quantity") }

		else{
			$.ajax({
				type: "GET",
				url: "{{url('expense-categories/ajax')}}",
				data:'id='+id,
				success: function(data){
					var data = JSON.parse(data);
					var supply_type = data.supply_type; 
	                var category = data.supply_category;
	                var expense_category = data.name;
	                var sgst = 0;
		        	var cgst = 0;
		        	var igst = 0;
		        	var total_cost = 0; 
		        	var total_sgst = 0; 
		        	var total_cgst = 0; 
		        	var total_igst = 0; 
					var total_amount = 0; 
		        	var tax = $('#tax_main').val();
		        	
		        	if(cost < 0) cost = 0;
		        	if(quantity < 0) quantity = 0;
		        	if ($("#tax_type").val() == '1') {
		                sgst = (cost*quantity*tax)/200;
						cgst = (cost*quantity*tax)/200;
						// $('#tax_type').val(1);
						$('.sgst_tr').show();
						$('.cgst_tr').show();
						$('.igst_tr').hide();
		            }
					else {
		               	igst = (cost*quantity*tax)/100;
						// $('#tax_type').val(2);
		               	$('.sgst_tr').hide();
						$('.cgst_tr').hide();
						$('.igst_tr').show();
		            }
		            
		            
		            
		            abt = parseFloat(cost*quantity);
				    amount = parseFloat(cost*quantity)+parseFloat(sgst)+parseFloat(cgst)+parseFloat(igst);
				    amount = parseFloat(amount);

		        	var delBtn = '<button type="button" class="btn btn-danger btn-xs m-t-15 waves-effect delete-row"><i class="material-icons">remove_circle</i></button>';

		            var markup = "<tr><td>"+supply_type+"<input name='type[]' class='form-control' type='hidden' value='"+supply_type+"'  /></td><td>"+category+"<input name='category[]' class='form-control' type='hidden' value='"+category+"'  /></td><td>"+expense_category+"<input name='expense_category[]' class='form-control' type='hidden' value='"+expense_category+"'  /></td><td>"+description+"<input name='description[]' class='form-control' type='hidden' value='"+description+"' /></td><td>"+reason+"<input name='reason[]' class='form-control' type='hidden' value='"+reason+"'  /></td><td>"+code+"<input name='code[]' class='form-control' type='hidden' value='"+code+"'  /></td><td class='cost_td'>"+cost+"<input name='cost[]' class='form-control cost1' type='hidden' value='"+cost+"'  /><input name='tax[]' class='form-control' type='hidden' value='"+tax+"'/></td><td class='quantity_td'>"+quantity+"<input name='quantity[]' class='form-control quantity' type='hidden' value='"+quantity+"'  /></td><td class='abt_td'>"+abt+"<input name='abt[]' class='form-control abt' type='hidden' value='"+abt+"'  /></td> <td class='sgst_td'> "+sgst+"<input name='sgst[]' class='form-control sgst' type='hidden' value='"+sgst+"'  />  </td><td class='tax_amount_td'>"+cgst+"<input name='cgst[]' class='form-control cgst' type='hidden' value='"+cgst+"' />  </td><td class='tax_amount_td'>"+igst+" <input name='igst[]' class='form-control igst' type='hidden' value='"+igst+"'  />  </td> <td class='amount_td'> "+amount+" <input name='amount[]' class='form-control unamount1' type='hidden' value='"+amount+"' /> </td><td>"+delBtn+"</td></tr>";
									  
		            $(".data-field").append(markup);

		            
		            $("input[class *= 'abt']").each(function(){
			        	total_cost += +$(this).val();
			    	}); 
		            $("input[class *= 'sgst']").each(function(){
			        	total_sgst += +$(this).val();
			    	});
			    	$("input[class *= 'cgst']").each(function(){
			        	total_cgst += +$(this).val();
			    	});
			    	$("input[class *= 'igst']").each(function(){
			        	total_igst += +$(this).val();
			    	});
			    	$("input[class *= 'unamount']").each(function(){
			    		total_amount += +$(this).val(); 
			    	}); 

			    	if ($("#round_off").prop("checked")==true) {
						total_amount = parseFloat(total_amount).toFixed(2);
			        }

					$('.amount_before_tax_td').html(parseFloat(total_cost));
			    	$('.sgst_amount_td').html(parseFloat(total_sgst));
			    	$('.cgst_amount_td').html(parseFloat(total_cgst));
			    	$('.igst_amount_td').html(parseFloat(total_igst));
					$('.total_amount_td').html(parseFloat(total_amount)+'<input id="total_amount" name="total_amount" class="form-control " type="hidden" value="'+parseFloat(total_amount).toFixed(5)+'"/>');
					
					$('#radio_1').prop('disabled',true);
		        	$('#radio_2').prop('disabled',true);
		        	$('.dataTable').show();

	        	}
			});

			$('#reason_main').val(''); 
	    	$('#cost_main').val('');
	    	$('#quantity_main').val('');
	   // 	$('#description_main option').remove(); 
    //         $('#description_main').selectpicker('refresh');
	    	$('#code_main').val('');
		}

    });

    $('.data-field').on('click', '.delete-row', function(e){
		e.preventDefault();
		
		$(this).closest("tr").remove();

		var total_cost = 0; 
    	var total_sgst = 0; 
    	var total_cgst = 0; 
    	var total_igst = 0; 
		var total_amount = 0;

		$("input[class *= 'abt']").each(function(){
        	total_cost += +$(this).val();
    	});  
        $("input[class *= 'sgst']").each(function(){
        	total_sgst += +$(this).val();
    	});
    	$("input[class *= 'cgst']").each(function(){
        	total_cgst += +$(this).val();
    	});
    	$("input[class *= 'igst']").each(function(){
        	total_igst += +$(this).val();
    	});
    	$("input[class *= 'unamount']").each(function(){
    		total_amount += +$(this).val(); 
    	});

    	if ($("#round_off").prop("checked")==true) {
            //total_amount = $('#total_amount').val();
			total_amount = parseFloat(total_amount).toFixed(2);
        }

    	$('.amount_before_tax_td').html(parseFloat(total_cost));
    	$('.sgst_amount_td').html(parseFloat(total_sgst));
    	$('.cgst_amount_td').html(parseFloat(total_cgst));
    	$('.igst_amount_td').html(parseFloat(total_igst));
    	$('.total_amount_td').html(parseFloat(total_amount)+'<input id="total_amount" name="total_amount" class="form-control " type="hidden" value="'+parseFloat(total_amount)+'"/>');
	});

	$("#round_off").change(function(){
		if ($("#round_off").prop("checked")==true) {
            var total_amount = $('#total_amount').val();
			total_amount = parseFloat(total_amount).toFixed(0);
        }
		if ($("#round_off").prop("checked")==false) {
           total_amount = 0;
           $("input[class *= 'unamount']").each(function(){
	    		total_amount += +$(this).val(); 
	    	});
           total_amount = parseFloat(total_amount).toFixed(2);
        }
		$('.total_amount_td').html(parseFloat(total_amount)+'<input id="total_amount" name="total_amount" class="form-control " type="hidden" value="'+parseFloat(total_amount)+'"/>');
	});

});    



$(document).ready(function() {
	$('.created_for').hide();
	$('.sub_expense').hide();
	$('.purchase').hide();
	$('.expense').hide();
	$('.common').hide();
	$('.acc_no').hide();
	$('.ifsc').hide();
	$('.txn_no').hide();
	$('.dataTable').hide();
});

</script>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Expense
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/expenses') }}">Expense</a></li>
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
        	<form id="form" method="post" action="{{route('expenses.store')}}" enctype="multipart/form-data">
            	{{ csrf_field() }}
	            <div class="header row">
	                <h2>
	                    <div class="form-line" class="balName">
	                    	<div class="col-sm-6">
		                		Details
		                		@if(Auth::user()->user_type == 4)
		                        <input name="group2" type="radio" id="radio_3" value="1" checked />
	                            <label for="radio_3"><b>@if($balance) Balance({{$balance}}) @else Balance 0 @endif</b></label>
	                            <input name="group2" type="radio" id="radio_4" value="2" />
	                            <label for="radio_4"><b>Shared</b></label>
                        	</div>
                            <div class="col-sm-4 created_for">
                            	<div class="form-group ">
			                        <div class="form-line ">
	                            		<input name="created_for" id="created_for" class="form-control" type="text" placeholder="enter name of employee" />
	                            	</div>
	                            </div>
                            </div>
                            <div class="col-sm-2 ">
                            	<div class="form-group ">
			                        <div class="form-line payeebalance">
	                            	</div>
	                            </div>
                            </div>
                            @else
                            @if($balance) Balance({{$balance}}) @else Balance 0 @endif
                            @endif
	                    </div>
	                </h2>
	            </div>
            	<div class="body">
                	<div class="row clearfix">
	                	<div class="col-sm-3">
	                		<label for="voucher_no">Voucher No.(auto)</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="voucher_no" name="voucher_no" class="form-control" placeholder="Enter voucher number" value="{{ $voucher_no }}" readonly>
		                        </div>
		                    </div>
	                    </div>
                	 	<div class="col-sm-3">
		                    <label for="voucher_date">Voucher Date</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="voucher_date" name="voucher_date" class="form-control" placeholder="Enter Date Of voucher" value="{{  date('d F Y') }}" readonly>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
		                    <label for="invoice_no">Invoice No.</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="invoice_no" name="invoice_no" class="form-control" placeholder="Enter Invoice number" value="{{ old('invoice_no') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
		                    <label for="invoice_date">Invoice Date</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="invoice_date" name="invoice_date" class="form-control datepicker" placeholder="Enter Date Of Invoice" value="{{ old('invoice_date') }}" required >
		                        </div>
		                    </div>
	                    </div>
                        <div class="col-sm-3">
		                    <label for="mode">Mode Of Payment</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" id="mode" name="mode">
			                            <option value="1">Cash</option>
			                            <option value="2" @if($balance<0) selected @endif >Credit</option>
			                        </select>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
		                    <label for="location">Location</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" id="location" name="location" @if(Auth::user()->user_type == 3 || Auth::user()->user_type == 4) disabled @endif>
			                            <option >select</option>
			                            @foreach($workshop as $list)
			                            <option value="{{$list->id}}" @if(Auth::user()->workshop_id == $list->id) selected @endif >{{$list->name}}</option>
			                            @endforeach
			                        </select>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="vendor_id">Vendor Name</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" data-live-search="true"  id="vendor_id" name="vendor_id" required="true">
			                        	<option >please select</option>
			                            @foreach($vendor as $list)
			                            <option value="{{$list->id}}"> {{$list->name}}  (GSTIN#{{$list->gst}}#)</option>
			                            @endforeach
			                        </select>
			                        <input name="tax_type" id="tax_type" type="hidden" />
		                    	</div>
	                    	</div>
	                    </div>
	                    <!-- <div class="col-sm-6 ">
		                    <label for="party_name">Seller Name</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="party_name" name="party_name" class="form-control" placeholder="Enter seller name" value="{{ old('party_name') }}" required>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="party_gstin">Seller GSTIN</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="party_gstin" name="party_gstin" class="form-control" placeholder="Enter seller gstin " value="{{ old('party_gstin') }}" >
		                        </div>
		                    </div>
	                    </div> -->
	                    <div class="col-sm-3 ">
		                    <label for="voucher_img">Upload Document</label>
		                    <div class="form-group form-float">
		                        <div class="form-line">
	                                <div class="fallback">
	                                    <input name="voucher_img" id="voucher_img" class="form-control" type="file" placeholder="img only" required accept="image/x-png,image/gif,image/jpeg" title="image/x-png,image/gif,image/jpeg 5MB" max-size=5120/>
	                                </div>
			                    </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 ">
		                    <label for="import_csv_inv">Import CSV Document</label>
		                    <div class="form-group form-float">
		                        <div class="form-line">
	                                <div class="fallback">
	                                    <input name="import_csv_inv" id="import_csv_inv" class="form-control" type="file" placeholder="img only" max-size=5120/>
	                                </div>
			                    </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 ">
	                    	<button type="button" id="upload" class="btn btn-default m-t-15 waves-effect">Import</button>
	                    </div>
	                    <!-- <div class="col-sm-3 ">
	                    	<label>Invoice Type</label>
		                    <div class="form-line">
		                        <input name="group1" type="radio" id="radio_1" value="1" checked />
                                <label for="radio_1">GST</label>
                                <input name="group1" type="radio" id="radio_2" value="2" />
                                <label for="radio_2">IGST</label>
                                <input name="tax_type" id="tax_type" class="form-control" type="hidden" />
		                    </div>
		                </div> -->
	                </div>
	                <div class="row clearfix">
	                	<div class="col-sm-12 " >
			                <table class="table table-bordered table-striped table-hover " >
		                        <thead>
		                            <tr>
		                                <th >Exp Cat.</th>
		                                <th >Sub Exp.</th>
		                                <th >Description</th>
		                                <th >HSN/SAC</th>
		                                <th >Base Value</th>
		                                <th >Quantity</th>
		                                <th >Tax %</th>
		                                <th >Action</th>
		                            </tr>
		                        	<tr>
		                                <!-- <td>
						                    <div class="form-group ">
							                    <div class="form-line focused">
							                        <select class="form-control" id="supply_type_main">
							                        	<option value="" >select</option>
							                        	@foreach($expense_category as $list)
							                            <option value="{{$list->id}}">{{$list->name}}</option>
							                            @endforeach
							                            <option value="Service" >Service</option>
							                            <option value="Material" >Material</option> 
							                        </select>
						                    	</div>
					                    	</div>
		                                </td>
		                                <td>
						                    <div class="form-group ">
							                    <div class="form-line focused">
							                        <select class="form-control" id="supply_category_main">
							                            <option >select</option>
							                            <option value="Non-Workshop" >Non-Workshop</option> 
							                        </select>
						                    	</div>
					                    	</div>
		                                </td> -->
		                                <td>
						                    <div class="form-group">
							                    <div class="form-line focused">
							                        <select class="form-control show-tick" id="expense_category_main"  > 
							                           <option value="" >select</option>
							                        	@foreach($expense_category as $list)
							                            <option value="{{$list->id}}">{{$list->name}}</option>
							                            @endforeach
							                        </select>
						                    	</div>
					                    	</div>
		                                </td>
		                                <td>
						                    <div class="form-group ">
							                    <div class="form-line focused">
							                        <select class="form-control show-tick" id="description_main" >
							                            <option value="">select</option>
							                        </select>
						                    	</div>
					                    	</div>
		                                </td>
		                                <td>
		                                	<div class="form-group form-float">
						                        <div class="form-line focused">
					                                <div class="fallback">
					                                    <input id="reason_main" class="form-control" type="text" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
		                                <td>
		                                	<div class="form-group form-float">
						                        <div class="form-line focused">
					                                <div class="fallback">
					                                    <input id="code_main" class="form-control" type="text" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
		                                <td  >
		                                	<div class="form-group form-float">
						                        <div class="form-line focused"> 
					                                <div class="fallback">
					                                    <input id="cost_main" class="form-control " type="number" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
		                                <td >
						                    <div class="form-group form-float">
							                    <div class="form-line focused"> 
					                                <div class="fallback">
					                                    <input id="quantity_main" class="form-control " type="number" />
					                                </div>
							                    </div>
					                    	</div>
		                                </td>
		                                <td >
						                    <div class="form-group form-float">
							                    <div class="form-line ">
							                        <select class="form-control show-tick " id="tax_main" >
							                            <option value="0">0</option>
							                            @foreach($tax as $list)
							                            <option value="{{$list->value}}">{{$list->value}}</option>
							                            @endforeach
							                        </select>
						                    	</div>
					                    	</div>
		                                </td>
		                                <td><button type="button" class="btn btn-xs btn-primary m-t-15 waves-effect add-row"><i class="material-icons">add_circle</i></button> </td>
		                            </tr>
		                        </thead>
		                    </table>
		                </div>
		            </div>
	                <div class="row clearfix">
	                	<div class="col-sm-12" style="overflow:auto">
			                <table class="table table-bordered table-striped table-hover dataTable" >
		                        <thead>
		                            <tr>
		                                <th >Supply Type</th>
		                                <th >Supply Cat.</th>
		                                <th >Exp Cat.</th>
		                                <th >Sub Exp.</th>
		                                <th >Description</th>
		                                <th >HSN/SAC</th>
		                                <th >Base Value</th>
		                                <th >Quantity</th>
		                                <th >Amount</th>
		                                <th >SGST</th>
		                                <th >CGST</th>
		                                <th >IGST</th>
		                                <th >Total</th>
		                                <th >Action</th>
		                            </tr>
		                        </thead>
		                        <tbody class="data-field">
		                            
		                        </tbody>
		                        <tfoot class="final_amount">
		                            <tr>
		                            	<th colspan="12" style="text-align: right;">Amount Before Tax</th>
		                            	<td class="amount_before_tax_td">
		                                	<!-- <div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback"> -->
					                                    <input name="amount_before_tax" id="amount_before_tax" class="form-control" type="hidden" />
					                                <!-- </div>
							                    </div>
						                    </div> -->
					                	</td>
					                	<th></th>
		                            </tr>
		                            <!-- <tr>
		                            	<th colspan="12" style="text-align: right;">Discount</th>
		                            	<td class="discount">
		                                	<div class="form-group form-float" style="margin-bottom: -5px;margin-top: -8px;">
						                        <div class="form-line">
					                                <div class="fallback">
					                                    <input name="discount" id="discount" class="form-control" type="number" value="0" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
					                	<th></th>
		                            </tr> -->
		                            <tr class="sgst_tr">
		                            	<th colspan="12" style="text-align: right;">SGST Amount</th>
		                            	<td class="sgst_amount_td">
		                                	<!-- <div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback"> -->
					                                    <input name="sgst_amount" id="sgst_amount" class="form-control" type="hidden"  />
					                                <!-- </div>
							                    </div>
						                    </div> -->
					                	</td>
					                	<th></th>
		                            </tr>
		                            <tr class="cgst_tr">
		                            	<th colspan="12" style="text-align: right;">CGST Amount</th>
		                            	<td class="cgst_amount_td">
		                                	<!-- <div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback"> -->
					                                    <input name="cgst_amount" id="cgst_amount" class="form-control" type="hidden"  />
					                                <!-- </div>
							                    </div>
						                    </div> -->
					                	</td>
					                	<th></th>
		                            </tr>
		                            <tr class="igst_tr">
		                            	<th colspan="12" style="text-align: right;">IGST Amount</th>
		                            	<td class="igst_amount_td">
		                                	<!-- <div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback"> -->
					                                    <input name="igst_amount" id="igst_amount" class="form-control" type="hidden" />
					                                <!-- </div>
							                    </div>
						                    </div> -->
					                	</td>
					                	<th></th>
		                            </tr>
		                            <tr class="">
		                            	<th colspan="12" style="text-align: right;">Total Amount</th>
		                            	<td class="total_amount_td">
		                                	<!-- <div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback"> -->
					                                    <input name="total_amount" id="total_amount" class="form-control " type="hidden" />
					                                <!-- </div>
							                    </div>
						                    </div> -->
					                	</td>
					                	<td>
					                		<input type="checkbox" id="round_off" name="round_off" value="1">
			                                <label for="round_off">Round Off</label>
			                            </td>
		                            </tr>
		                            <!-- <tr class="">
		                            	<th colspan="12" style="text-align: right;">Total Amount</th>
		                            	<td class="total_discount_td">
		                                	<div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback">
					                                    <input name="total_discount_amount" id="total_discount_amount" class="form-control " type="hidden" />
					                                <!- </div>
							                    </div>
						                    </div> ->
					                	</td>
					                	<th></th>
		                            </tr> -->
		                        </tfoot>
		                    </table>
	                	</div>
	                </div>
	                <div class="row clearfix">
	                	<div class="col-sm-6">
	                		<button type="submit" id="form-save" class="btn btn-primary m-t-15 waves-effect">Save</button>
	                	</div>
	                </div>
            	</div>
            </form><!-- 
            <div class="preloader pl-size-xl">
                <div class="spinner-layer">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>


<!-- Moment Plugin Js -->
<script src="{{ asset('bsb/plugins/momentjs/moment.js')}}"></script>

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$('.datepicker').bootstrapMaterialDatePicker({
    format: 'DD MMMM YYYY',
    clearButton: true,
    weekStart: 1,
    time: false
});
</script>

<script>
  $( function() {

	$("#vendor_id").change(function(){
		@php $comapny = getAllFromID(Auth::user()->company_id, 'companies') @endphp
		var company_gstin = "{{$comapny->gst}}"; 
		var company_gstin_code = parseInt(company_gstin.substring(0,2));
		var vendor_gstin = $( "#vendor_id option:selected" ).text().split('#');
		var vendor_gstin_code = parseInt(vendor_gstin[1].substring(0,2));
		if (company_gstin_code == vendor_gstin_code) {
			$('#tax_type').val(1);
		}
		else{
			$('#tax_type').val(2);
		}
	});

    $( "#created_for" ).autocomplete({
      source: '{{url('user-deposit/payeename')}}',
      minLength:1,
      maxLength:10
    });
    
    $("#created_for").change(function(){
		var created_for = $( "#created_for" ).val();
		$.ajax({
				type: "GET",
				url: "{{url('user-deposit/payeebalance')}}",
				data:'created_for='+created_for,
				success: function(data){
					console.log(data);
					var data = JSON.parse(data);
					$( ".payeebalance" ).html('Balance('+data+')');
	                var category = data.supply_category;
	    }});
	});

  });

  	$( document ).ready(function() {
		$('#expense_category_main').selectpicker('refresh');
		$('#vendor_id').selectpicker('refresh');
	});
  </script>

<script type="text/javascript">

function calculate() {
	var total_cost = 0; 
    	var total_sgst = 0; 
    	var total_cgst = 0; 
    	var total_igst = 0; 
		var total_amount = 0;

		$("[class *= 'abt_td']").each(function(){
        	total_cost += +$(this).text();
    	});  
        $("[class *= 'sgst_td']").each(function(){
        	total_sgst += +$(this).text();
    	});
    	$("[class *= 'cgst_td']").each(function(){
        	total_cgst += +$(this).text();
    	});
    	$("[class *= 'igst_td']").each(function(){
        	total_igst += +$(this).text();
    	});
    	$("[class *= 'amount_td']").each(function(){
    		total_amount += +$(this).text(); 
    	});
    	
    	total_cost = parseFloat(total_cost).toFixed(2);
        total_sgst = parseFloat(total_sgst).toFixed(2);
        total_cgst = parseFloat(total_cgst).toFixed(2);
        total_igst = parseFloat(total_igst).toFixed(2);
        total_amount = parseFloat(total_amount).toFixed(2);

    	if ($("#round_off").prop("checked")==true) {
			total_amount = parseFloat(total_amount).toFixed(0);
    	}
    	
    	$("#round_off").prop("disabled",true)

    	$('.amount_before_tax_td').html(parseFloat(total_cost));
    	$('.sgst_amount_td').html(parseFloat(total_sgst));
    	$('.cgst_amount_td').html(parseFloat(total_cgst));
    	$('.igst_amount_td').html(parseFloat(total_igst));
    	$('.total_amount_td').html(parseFloat(total_amount)+'<input id="total_amount" name="total_amount" class="form-control " type="hidden" value="'+parseFloat(total_amount).toFixed(2)+'"/>');
}


	$(document).ready(function () {

	    $("#upload").click(function (event) {

	        //stop submit the form, we will post it manually.
	        event.preventDefault();
	        var filename = $('#import_csv_inv').val();
	        var ext = filename.split('.').pop();
	        if (ext=='csv') {

		        // Get form
		        var form = $('#form')[0];

				// Create an FormData object 
		        var data = new FormData(form);
				// disabled the submit button
		        $("#upload").prop("disabled", true);

		        $.ajax({
		            type: "post",
		            enctype: 'multipart/form-data',
		            url: "{{url('importcsv')}}",
		            data: data,
		            processData: false,
		            contentType: false,
		            cache: false,
		            timeout: 600000,
		            success: function (data) {
		            	var data = JSON.parse(data);
		                $(".data-field").append(data);
		                $('#tax_type').val(1);
		                calculate();
		                $('.dataTable').show();
		                $("#btnSubmit").prop("disabled", false);
		            },
		            error: function (e) {
		                console.log("ERROR : ", e);
		                $("#upload").prop("disabled", false);
		                $("#import_csv_inv").prop("disabled", false);
		            }
		        });
	      	}
	      	else{
	      		alert('Invalid File! Please upload a valid csv file');
	      	}
	    });

	});

</script>


 <!-- Select Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

@endsection
