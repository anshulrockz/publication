@extends('layouts.claim')

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
$("#Challan_category_main").change(function(){
	var id = $(this).val();
	if(id != ''){
		$('#description_main option').remove();
		$.ajax({
			type: "GET",
			url: "{{url('/subChallans/ajax')}}",
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
			$('#sub_Challans option').remove();
		}
});
});
</script>

<script>

$(function(){
	$(".add-row").click(function(){
		var itemcode = $('thead #itemcode').val();
    	var itemname = $('thead #itemname').val(); 
    	var itemqty = $('thead #itemqty').val();
    	var desc = $('thead #desc').val();
    	
		if(itemcode == '' || itemcode == 0 || itemname == '' || itemqty == ''){ alert("Item code, name, qyantity are mandatory") }
		
		else{

			$('#itemcode').val(''); 
	    	$('#itemname').val('');
	    	$('#itemqty').val('');
	    	$('#desc').val('');

			var delBtn = '<button type="button" class="btn btn-danger btn-xs m-t-15 waves-effect delete-row"><i class="material-icons">remove_circle</i></button>';

			// var markup = "<tr><td>"+supply_type+"<input name='type[]' class='form-control' type='hidden' value='"+supply_type+"'  /></td><td>"+category+"<input name='category[]' class='form-control' type='hidden' value='"+category+"'  /></td><td>"+Challan_category+"<input name='Challan_category[]' class='form-control' type='hidden' value='"+Challan_category+"'  /></td><td>"+description+"<input name='description[]' class='form-control' type='hidden' value='"+description+"' /></td><td>"+reason+"<input name='reason[]' class='form-control' type='hidden' value='"+reason+"'  /></td><td>"+code+"<input name='code[]' class='form-control' type='hidden' value='"+code+"'  /></td><td class='cost_td'>"+cost+"<input name='cost[]' class='form-control cost1' type='hidden' value='"+cost+"'  /><input name='tax[]' class='form-control' type='hidden' value='"+tax+"'/></td><td class='quantity_td'>"+quantity+"<input name='quantity[]' class='form-control quantity' type='hidden' value='"+quantity+"'  /></td><td class='abt_td'>"+abt+"<input name='abt[]' class='form-control abt' type='hidden' value='"+abt+"'  /></td> <td class='sgst_td'> "+sgst+"<input name='sgst[]' class='form-control sgst' type='hidden' value='"+sgst+"'  />  </td><td class='tax_amount_td'>"+cgst+"<input name='cgst[]' class='form-control cgst' type='hidden' value='"+cgst+"' />  </td><td class='tax_amount_td'>"+igst+" <input name='igst[]' class='form-control igst' type='hidden' value='"+igst+"'  />  </td> <td class='amount_td'> "+amount+" <input name='amount[]' class='form-control unamount1' type='hidden' value='"+amount+"' /> </td><td>"+delBtn+"</td></tr>";
			var markup = '<tr>'
                                        +'<td>'
		                                	+'<div class="form-group form-float">'
						                        +'<div class="form-line">'
					                                +'<div class="fallback">'
					                                    +'<input class="form-control item_code" name="item_code[]" value="'+itemcode+'" type="text" />'
					                                +'</div>'
							                    +'</div>'
						                    +'</div>'
					                	+'</td>'
		                                +'<td>'
		                                	+'<div class="form-group form-float">'
						                        +'<div class="form-line">'
													+'<div class="fallback">'
														+'<input class="form-control  item_name" name="item_name[]" value="'+itemname+'"  type="text" />'
													+'</div>'
												+'</div>'
											+'</div>'
										+'</td>'
		                                +'<td>'
		                                	+'<div class="form-group form-float">'
						                        +'<div class="form-line">' 
					                                +'<div class="fallback">'
					                                    +'<input class="form-control  item_qty" name="item_qty[]" value="'+itemqty+'"  type="number" />'
					                                +'</div>'
							                    +'</div>'
						                    +'</div>'
					                	+'</td>'
		                                +'<td >'
						                    +'<div class="form-group form-float">'
							                    +'<div class="form-line">' 
					                                +'<div class="fallback">'
					                                    +'<input class="form-control description" name="description[]" value="'+desc+'"  type="text" />'
					                                +'</div>'
							                    +'</div>'
					                    	+'</div>'
		                                +'</td>'
		                                +'<td>'+delBtn+'</td>'
		                            +'</tr>';	
			$(".data-field").append(markup);
			
			// $('.dataTable').show();

			$('.itemtable tfoot').hide();
		}

    });

    $('.data-field').on('click', '.delete-row', function(e){
		e.preventDefault();
		
		$(this).closest("tr").remove();
	});

});    



$(document).ready(function() {
	$('.itemtable tfoot').hide();
});

</script>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Challan
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/challan') }}">Challan</a></li>
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
        	<form id="form" method="post" action="{{route('challan.store')}}" enctype="multipart/form-data">
            	{{ csrf_field() }}
	            <div class="header row justify-content-center">
	                <!-- <img src="" alt="PLS"> -->
					<div class="form-line" class="balName">
						<div class="col-sm-12" style="text-align:center">
							<h3>Material Gate Pass</h3>
						</div>
						<div class="col-sm-12">
							<h4>To</h4>
							<h4>Security Officer, Main Gate</h4>
							<h4>Please allow the following material to be taken out:</h4>
						</div>
					</div>
				</div>
            	<div class="body">
                	<div class="row clearfix">
	                	<div class="col-sm-3">
	                		<label for="uid">Challan No.(auto)</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="uid" name="uid" class="form-control" placeholder="Enter voucher number" value="{{ $voucher_no }}" readonly>
		                        </div>
		                    </div>
	                    </div>
                	 	<div class="col-sm-3">
		                    <label for="voucher_date">Challan Date</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="voucher_date" name="voucher_date" class="form-control" placeholder="Enter Date Of voucher" value="{{  date('d F Y') }}" readonly>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
		                    <label for="from_unit">From Location</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" id="from_unit" name="from_unit" required>
			                            <option >select</option>
			                            @foreach($workshop as $list)
			                            <option value="{{$list->id}}" @auth @if(Auth::user()->workshop_id == $list->id) selected @endif @endauth >{{$list->name}}</option>
			                            @endforeach
			                        </select>
		                        </div>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
		                    <label for="to_unit">To Location</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <select class="form-control show-tick" id="to_unit" name="to_unit" required>
			                            <option >select</option>
			                            @foreach($workshop as $list)
			                            <option value="{{$list->id}}" >{{$list->name}}</option>
			                            @endforeach
			                        </select>
		                        </div>
		                    </div>
	                    </div>
                	 	<div class="col-sm-3">
		                    <label for="reciever">Name of the person carrying material </label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="reciever" name="reciever" class="form-control" placeholder="Enter Name of Material Reciever" value="{{  old('reciever') }}" >
		                        </div>
		                    </div>
	                    </div>
                	 	<div class="col-sm-3">
		                    <label for="vehicle_num">Vehicle no.</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="vehicle_num" name="vehicle_num" class="form-control" placeholder="Enter Vehicle Number" value="{{  old('vehicle_num') }}" >
		                        </div>
		                    </div>
	                    </div>
                	 	<div class="col-sm-3">
		                    <label for="job_num">Job no.</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="job_num" name="job_num" class="form-control" placeholder="Enter Job Number" value="{{  old('job_num') }}" >
		                        </div>
		                    </div>
	                    </div>
	                </div>
	                <div class="row clearfix">
	                	<div class="col-sm-12 " >
			                <table class="table table-bordered table-striped table-hover itemtable" >
		                        <thead>
		                            <tr>
		                                <th>Item code</th>
		                                <th>Item name</th>
		                                <th>Quantity</th>
		                                <th>Description</th>
		                                <th>Action</th>
		                            </tr>
		                        	<tr>
                                        <td>
		                                	<div class="form-group form-float">
						                        <div class="form-line focused">
					                                <div class="fallback">
					                                    <input id="itemcode" class="form-control" type="text" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
		                                <td>
		                                	<div class="form-group form-float">
						                        <div class="form-line focused">
					                                <div class="fallback">
					                                    <input id="itemname" class="form-control" type="text" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
		                                <td  >
		                                	<div class="form-group form-float">
						                        <div class="form-line focused"> 
					                                <div class="fallback">
					                                    <input id="itemqty" class="form-control " type="number" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
		                                <td >
						                    <div class="form-group form-float">
							                    <div class="form-line focused"> 
					                                <div class="fallback">
					                                    <input id="desc" class="form-control " type="text" />
					                                </div>
							                    </div>
					                    	</div>
		                                </td>
		                                <td><button type="button" class="btn btn-xs btn-primary m-t-15 waves-effect add-row"><i class="material-icons">add_circle</i></button> </td>
		                            </tr>
		                        </thead>
		                        <tbody class="data-field">
		                            
		                        </tbody>
								<tfoot>
									<tr>
                                        <td>
		                                	<div class="form-group form-float">
						                        <div class="form-line focused">
					                                <div class="fallback">
					                                    <input id="itemcode" class="form-control" type="text" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
		                                <td>
		                                	<div class="form-group form-float">
						                        <div class="form-line focused">
					                                <div class="fallback">
					                                    <input id="itemname" class="form-control" type="text" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
		                                <td  >
		                                	<div class="form-group form-float">
						                        <div class="form-line focused"> 
					                                <div class="fallback">
					                                    <input id="itemqty" class="form-control " type="number" />
					                                </div>
							                    </div>
						                    </div>
					                	</td>
		                                <td >
						                    <div class="form-group form-float">
							                    <div class="form-line focused"> 
					                                <div class="fallback">
					                                    <input id="desc" class="form-control " type="text" />
					                                </div>
							                    </div>
					                    	</div>
		                                </td>
		                                <td><button type="button" class="btn btn-xs btn-primary m-t-15 waves-effect add-row"><i class="material-icons">add_circle</i></button> </td>
		                            </tr>
								</tfoot>
		                    </table>
		                </div>
		            </div>
	                <div class="row clearfix">
						<div class="col-sm-6">
		                    <label for="remark">Remark</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <textarea id="remark" name="remark" class="form-control" placeholder="Remark if any">{{  old('remark') }}</textarea>
		                        </div>
		                    </div>
	                    </div>
						<div class="col-sm-3 pull-right	">
		                    <label for="security_officer">Security Officer Name</label>
		                    <div class="form-group form-float">
		                        <div class="form-line ">
		                            <input type="text" id="security_officer" name="security_officer" class="form-control" placeholder="Enter Name of Security Officer" value="{{  old('security_officer') }}" >
		                        </div>
		                    </div>
	                    </div>
	                </div>
	                <div class="row clearfix">
	                	<div class="col-sm-6">
	                		<button type="submit" id="form-save" class="btn btn-primary m-t-15 waves-effect">Save</button>
	                	</div>
	                </div>
            	</div>
            </form>
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
		$('#Challan_category_main').selectpicker('refresh');
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
		            	// console.log(data);
		                // $(".data-field").html("testing");
		                $(".data-field").append(data);
		                $('#tax_type').val(1);
		                calculate();
		                $('.dataTable').show();
		                $("#btnSubmit").prop("disabled", false);

		            },
		            error: function (e) {

		                console.log("ERROR : ", e);
		                $("#upload").prop("disabled", false);

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
