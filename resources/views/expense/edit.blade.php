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

$( document ).ready(function() {
    $("#form input").prop("disabled", true);
    $("#form select").prop("disabled", true);
    $("#form textarea").prop("disabled", true);
    $("#form-save").prop("disabled", true);
    $(".delete-row").hide();
	$(".addTable").hide();
});

$(function() {
    $("#form-edit").click(function() {
    	$("#form input, #form select, #form textarea, #form-save").prop("disabled", false);
    	$("#radio_1, #radio_2").prop("disabled", true);
    	@if(Auth::user()->user_type == 4 || Auth::user()->user_type == 3)
    	$("#location").prop("disabled", true);
    	@endif
    	$(".delete-row").removeAttr("style");
    	$(".addTable").show();
    	$('#expense_category_main, #vendor_id, #location, #tax_main').selectpicker('refresh');
    });
});

</script>

<!-- AJAX DD Selecter for Location Js -->
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
    	var reason = $('#reason_main').val(); 
    	var cost = $('#cost_main').val();
    	var quantity = $('#quantity_main').val();
    	var description = $('#description_main option:selected').text();
    	var code = $('#code_main').val();
    	// var discount = $('#discount').val();
 

		if(id == ''){ alert("Please select category") }
		if(description == '' || description == '--select--'){ alert("Please select sub category") }
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
		        	if ($('#tax_type').val() == '1') {
		                sgst = (cost*quantity*tax)/200;
						cgst = (cost*quantity*tax)/200;
						$('#tax_type').val(1);
						$('.sgst_tr').show();
						$('.cgst_tr').show();
						$('.igst_tr').hide();
		            }
					else {
		               igst = (cost*quantity*tax)/100;
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
						total_amount = parseFloat(total_amount).toFixed(0);
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
	    	$('#code_main').val('');
		}

    });

    $('.data-field').on('click', '.delete-row', function(e){
		e.preventDefault();
		
		var del = $(this).closest("tr").find('input[type=checkbox]').val();
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
			total_amount = parseFloat(total_amount).toFixed(0);
        }

    	$('.amount_before_tax_td').html(parseFloat(total_cost).toFixed(2));
    	$('.sgst_amount_td').html(parseFloat(total_sgst).toFixed(2));
    	$('.cgst_amount_td').html(parseFloat(total_cgst).toFixed(2));
    	$('.igst_amount_td').html(parseFloat(total_igst).toFixed(2));
    	$('.total_amount_td').html(parseFloat(total_amount).toFixed(2)+'<input id="total_amount" name="total_amount" class="form-control " type="hidden" value="'+parseFloat(total_amount).toFixed(2)+'"/>');
    	$('#deletedRow').append('<input name="delRow[]" class="form-control " type="hidden" value="'+del+'"/>');

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
	$('.addTable').hide();
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
                <b>
	                @if($balance)
	                Balance({{$balance}})
	                @endif
                </b>
            </div>
            <div class="body">
                <form id="form" method="post" action="{{route('expenses.update',$expense->id)}}" enctype="multipart/form-data">
                	{{ csrf_field() }}
	                {{ method_field('PUT') }}
                	 <div class="row clearfix">
	                	<div class="col-sm-3">
	                		<label for="voucher_no">Voucher No.(auto)</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                        	{{ $expense->voucher_no }}
		                            <input type="hidden" id="voucher_no" name="voucher_no" class="form-control" placeholder="Enter voucher number" value="" disabled>
		                        </div>
		                    </div>
	                    </div>
                	 	<div class="col-sm-3">
		                    <label for="voucher_date">Voucher Date</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                        	{{  date_format(date_create($expense->created_at),'d F y') }}
		                            <input type="hidden" id="voucher_date" name="voucher_date" class="form-control datepicker" placeholder="Enter Date Of voucher" value="" disabled>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
		                    <label for="invoice_no">Invoice No.</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="invoice_no" name="invoice_no" class="form-control" placeholder="Enter Invoice number" value="{{ $expense->invoice_no }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
		                    <label for="invoice_date">Invoice Date</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="invoice_date" name="invoice_date" class="form-control datepicker" placeholder="Enter Date Of Invoice" value="{{  date_format(date_create($expense->invoice_date),'d F y') }}" >
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-6 ">
		                    <label for="vendor_id">Vendor Name</label>
		                    <div class="form-group">
			                    <div class="form-line">
			                        <select class="form-control show-tick" id="vendor_id" name="vendor_id" data-live-search="true" required>
			                            <option value="" >{{ $expense->party_name }} (Please Update Vendor if this option is selected)</option>
			                            <option value="" >-- Please select Vendor --</option>
			                            @foreach($vendor as $list)
			                            <option value="{{$list->id}}" @if($expense->party_name == $list->name) selected @endif >{{$list->name}}  (GSTIN#{{$list->gst}}#)</option>
			                            @endforeach
			                        </select>
			                        <input name="tax_type" id="tax_type" type="hidden"  value="{{ $expense->inv_type }}" >
		                    	</div>
	                    	</div>
	                    </div>
	                    <!-- <div class="col-sm-3">
		                    <label for="mode">Mode Of Payment</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" id="mode" name="mode">
			                            <option value="1" @if($expense->paid_in==1) selected @endif >Cash</option>
			                            <option value="2" @if($expense->paid_in==2) selected @endif >Credit</option>
			                        </select>
		                        </div>
		                    </div>
	                    </div> -->
	                    <div class="col-sm-3">
		                    <label for="location">Location</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" id="location" name="location" @if(Auth::user()->user_type == 4 || Auth::user()->user_type == 3) disabled @endif>
			                            <option >select</option>
			                            @foreach($workshop as $list)
			                            <option value="{{$list->id}}" @if($expense->location == $list->id) selected @endif >{{$list->name}}</option>
			                            @endforeach
			                        </select>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3 ">
		                    <label for="voucher_img">Upload Invoice</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
	                                <div class="fallback">
	                                    <input name="voucher_img" id="voucher_img" class="form-control" type="file" placeholder="img only"  accept="image/x-png,image/gif,image/jpeg"/>
	                                </div>
			                    </div>
		                    </div>
	                    </div>
	                    <!-- <div class="col-sm-3 ">
	                    	<label>Invoice Type</label>
		                    <div class="form-line">
		                        <input name="group1" type="radio" id="radio_1" value="1" disabled />
                                <label for="radio_1">GST</label>
                                <input name="group1" type="radio" id="radio_2" value="2" disabled />
                                <label for="radio_2">IGST</label>
		                    </div>
		                </div> -->
	                </div>
	                <div class="row clearfix addTable">
	                	<div class="col-sm-12">
			                <table class="table table-bordered table-striped table-hover" >
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
							                        <select class="form-control show-tick" id="expense_category_main">
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
		                                <th >Sub Exp</th>
		                                <th >Description</th>
		                                <th >HSN/SAC</th>
		                                <th >Base Value</th>
		                                <th >Quantity</th>
		                                <th >Amount</th>
		                                <th class="sgst_td">SGST</th>
		                                <th class="cgst_td">CGST</th>
		                                <th class="igst_td">IGST</th>
		                                <th >Total</th>
		                                <th >Action</th>
		                            </tr>
		                        </thead>
		                        <tbody class="data-field">
		                        	@php
		                        		$i = $total_amount = $total_cost = $total_sgst = $total_cgst = $total_igst = 0;
		                        	@endphp
		                        	@foreach( $expense_details as $key=>$expense_details)
		                        	@php 
		                        		
		                        		$total_cost = $expense_details->cost * $expense_details->quantity;
		                        		$total_sgst += $expense_details->sgst;
		                        		$total_cgst += $expense_details->cgst;
		                        		$total_igst += $expense_details->igst;

		                        	@endphp
		                            <tr>
		                            	<td>
		                            		{{$expense_details->category1}}
		                            		<input class="form-control type" name="type[]" type="hidden" value="{{$expense_details->category1}}" />
		                            	</td>
		                            	<td>
		                            		{{$expense_details->category2}}
		                            		<input class="form-control category" name="category[]" type="hidden" value="{{$expense_details->category2}}" />
		                            	</td>
		                            	<td>
		                            		{{$expense_details->category3}}
		                            		<input class="form-control expense_category" name="expense_category[]" type="hidden" value="{{$expense_details->category3}}"  disabled/>
		                            	</td>
		                            	<td>
		                            		{{$expense_details->description}}
		                            		<input class="form-control description" name="description[]" type="hidden" value="{{$expense_details->description}}" disabled/>
		                            	</td>
		                            	<td>
		                            		{{$expense_details->reason}}
		                            		<input class="form-control reason" name="reason[]" type="hidden" value="{{$expense_details->reason}}" disabled/>
		                            	</td>
		                            	<td>{{$expense_details->code}}
		                            		<input class="form-control code" name="code[]" type="hidden" value="{{$expense_details->code}}" disabled />
		                            	</td>
		                            	<td class="cost_td">
		                            		{{$expense_details->cost}}
		                            		<input class="form-control cost1" name="cost[]" type="hidden" value="{{$expense_details->cost}}" disabled />
		                            		<input name='tax[]' class='form-control' type='hidden' value="{{$expense_details->tax_value}}">
		                            	</td>
		                            	<td class="quantity_td">
		                            		{{$expense_details->quantity}}
		                            		<input class="form-control quantity" name="quantity[]" type="hidden" value="{{$expense_details->quantity}}" disabled />
		                            	</td>
		                            	<td class="abt_td">
		                            		{{$expense_details->quantity*$expense_details->cost}}
		                            		<input class="form-control abt" name="abt[]" type="hidden" value="{{$expense_details->quantity*$expense_details->cost}}" disabled />
		                            	</td>
		                            	 <td class="sgst_td">
		                            	 	{{$expense_details->sgst}}
		                            	 	<input class="form-control sgst" name="sgst[]" type="hidden" value="{{$expense_details->sgst}}" disabled />  
		                            	 </td>
		                            	 <td class="cgst_td">
		                            	 	{{$expense_details->cgst}}
		                            	 	<input class="form-control cgst" name="cgst[]" type="hidden" value="{{$expense_details->cgst}}" disabled />  
		                            	 </td>
		                            	 <td class="igst_td">
		                            	 	{{$expense_details->igst}}
		                            	 	<input class="form-control igst" name="igst[]" type="hidden" value="{{$expense_details->igst}}" disabled />  
		                            	 </td> 
		                            	 <td class="amount_td"> 
		                            	 	{{ $total_cost + $expense_details->sgst + $expense_details->cgst + $expense_details->igst }} 
		                            	 	<input class="form-control unamount" disabled type="hidden" value="{{ $total_cost + $expense_details->sgst + $expense_details->cgst + $expense_details->igst}}" /> 
		                            	 </td>
		                            	 <td>
		                            	 	<input type="checkbox" class="filled-in" value="{{$expense_details->id}}"  name="" checked />
		                            	 	<button for="basic_checkbox_{{$expense_details->id}}" type="button" class="btn btn-danger btn-xs m-t-15 waves-effect delete-row"><i class="material-icons" value="{{$expense_details->id}}">remove_circle</i></button>
		                            	 </td>
		                            </tr>
		                            @endforeach
		                        </tbody>
		                            <tr>
		                            	<th colspan="12" style="text-align: right;">Amount Before Tax</th>
		                            	<td class="amount_before_tax_td">
		                            		{{ $total_cost }}
		                                	<!-- <div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback"> -->
					                                    <input name="amount_before_tax" id="amount_before_tax" class="form-control" type="hidden" />
					                                <!-- </div>
							                    </div>
						                    </div> -->
					                	</td>
		                            </tr>
		                            <!-- <tr>
		                            	<th colspan="12" style="text-align: right;">Discount</th>
		                            	<td class="discount">
		                                	<div class="form-group form-float" style="margin-bottom: -5px;margin-top: -8px;">
						                        <div class="form-line">
					                                <div class="fallback">
					                                    <input name="discount" id="discount" class="form-control" type="number" value="{{$expense->discount}}" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
					                	<th></th>
		                            </tr> -->
		                            
		                            <tr class="sgst_tr">
		                            	<th colspan="12" style="text-align:right;">SGST Amount</th>
		                            	<td class="sgst_amount_td">
		                            		{{ $total_sgst }}
		                                	<!-- <div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback"> -->
					                                    <input name="sgst_amount" id="sgst_amount" class="form-control" type="hidden"  />
					                                <!-- </div>
							                    </div>
						                    </div> -->
					                	</td>
		                            </tr>
		                            <tr class="cgst_tr">
		                            	<th colspan="12" style="text-align: right;">CGST Amount</th>
		                            	<td class="cgst_amount_td">
		                            		{{ $total_cgst }}
		                                	<!-- <div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback"> -->
					                                    <input name="cgst_amount" id="cgst_amount" class="form-control" type="hidden"  />
					                                <!-- </div>
							                    </div>
						                    </div> -->
					                	</td>
		                            </tr>
		                            
		                            <tr class="igst_tr">
		                            	<th colspan="12" style="text-align: right;">IGST Amount</th>
		                            	<td class="igst_amount_td">
		                            		{{ $total_igst }}
		                                	<!-- <div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback"> -->
					                                    <input name="igst_amount" id="igst_amount" class="form-control" type="hidden" />
					                                <!-- </div>
							                    </div>
						                    </div> -->
					                	</td>
		                            </tr>
		                            
		                            <tr class="">
		                            	<th colspan="12" style="text-align: right;">Total Amount</th>
		                            	<td class="total_amount_td">
		                            		{{ $total_cost + $total_sgst + $total_cgst + $total_igst }}
		                                	<!-- <div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback"> -->
					                                    <input name="total_amount" id="total_amount" class="form-control " type="number" value="{{ $total_amount }}" />
					                                <!-- </div>
							                    </div>
						                    </div> -->
					                	</td>
					                	<td>
					                		<input type="checkbox" id="round_off" name="round_off" @if( $expense->round_off == 1 ) checked @endif value="1">
			                                <label for="round_off">Round Off</label>
			                            </td>
		                            </tr>
		                            <!-- <tr class="">
		                            	<th colspan="12" style="text-align: right;">Total Amount</th>
		                            	<td class="total_discount_td">
		                            		{{ $total_cost + $total_sgst + $total_cgst + $total_igst }}
		                                	<div class="form-group form-float">
						                        <div class="form-line">
					                                <div class="fallback">
					                                    <input name="total_discount_amount" id="total_discount_amount" class="form-control " type="hidden" value="{{ $total_amount - $expense->discount }}"/>
					                                <!-- </div>
							                    </div>
						                    </div> ->
					                	</td>
					                	<th></th>
		                            </tr> -->
		                        </tfoot>
		                    </table>
	                	</div>
	                </div>
                    @if(!empty($expense->voucher_img))
                    <div class="row clearfix">
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
                    </div>
                    @endif
	                @php
                		$date1=date_create($expense->created_at);
						$date2=date_create(date("y-m-d H:i:s"));
						$diff=date_diff($date2,$date1);
						$days = $diff->format("%a");
                	@endphp 
                	
                	<div class="row clearfix">
	                	<div class="col-sm-6">
	                		@if($days<1 || Auth::user()->user_type==1) 
	                		<button type="submit" id="form-save" class="btn btn-primary waves-effect">Save</button>
	                		<button type="button" id="form-edit" class="btn btn-primary waves-effect">Edit </button></br>
	                		@endif

	                		Note:You can edit only in 24hrs of creation 
	                	</div>
	                </div>
	               <div id="deletedRow"></div>`
                </form>
            </div>
        </div>
    </div>
</div>


<script>
	
$( document ).ready(function() {

		var total_cost = 0; 
    	var total_sgst = 0; 
    	var total_cgst = 0; 
    	var total_igst = 0; 
		var total_amount = 0;
		var radio1 = 0;
		var radio2 = 0;

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

	radio = {{$expense->inv_type}};
	
	if(radio==1)
		$('#radio_1').prop("checked",true);
	else
		$('#radio_2').attr("checked",true);

	if ($("#round_off").prop("checked")==true) {
		total_amount = parseFloat(total_amount).toFixed(0);
    }
    
    total_cost = parseFloat(total_cost).toFixed(2);
        total_sgst = parseFloat(total_sgst).toFixed(2);
        total_cgst = parseFloat(total_cgst).toFixed(2);
        total_igst = parseFloat(total_igst).toFixed(2);
        total_amount = parseFloat(total_amount).toFixed(2);

	$('.amount_before_tax_td').html(parseFloat(total_cost));
	$('.sgst_amount_td').html(parseFloat(total_sgst));
	$('.cgst_amount_td').html(parseFloat(total_cgst));
	$('.igst_amount_td').html(parseFloat(total_igst));
	$('.total_amount_td').html(parseFloat(total_amount)+'<input id="total_amount" name="total_amount" class="form-control " type="hidden" value="'+parseFloat(total_amount)+'"/>');

 });

</script>

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

$("#vendor_id").change(function(){
	@php $comapny = getAllFromID(Auth::user()->company_id, 'companies') @endphp
	var company_gstin = "{{$comapny->gst}}"; 
	var company_gstin_code = parseInt(company_gstin.substring(0,2));
	var vendor_gstin = $( "#vendor_id option:selected" ).text().split('#');
	var vendor_gstin_code = parseInt(vendor_gstin[1].substring(0,2)); 

	if(confirm("If you change the Vendor tax type will also change and it will reflect after saving. Are you sure want to do this?")){
		if (company_gstin_code == vendor_gstin_code) {
			$('#tax_type').val(1);
		}
		else{
			$('#tax_type').val(2);
		}
	}
});

</script>
    
<!-- Select Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

@endsection
