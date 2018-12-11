@extends('layouts.app')

@section('content')

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

<!-- AJAX DD Selecter for Sub Expense Js -->
<script>
$(function(){
	$("#purchase_category").change(function(){
		var id = $(this).val();
		if(id != ''){
			$('#sub_category option').remove();
			$.ajax({
				type:"GET",
				url: "{{url('/sub-purchase-categories/fetch-by-id-ajax')}}",
				data:'id='+id,
				success: function(data){
					var data = JSON.parse(data);
					var selOpts = "<option>-- Please select --</option>";
					if(data.length >0){					
						console.log(data);
						for (i=0;i<data.length;i++)
						{
							var id = data[i].id; 
							var val = data[i].name;
							selOpts += "<option value='"+id+"'>"+val+"</option>";
						}
						$('#sub_category').append(selOpts);
    		            $('#sub_category').selectpicker('refresh');
					}
					else{
						$('#sub_category option').remove();
					}
				}
			});
		}
		else{
				$('#sub_category option').remove();
			}
	});
});
</script>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Expense Category
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('expense-categories/expense-category') }}">Expense Category</a></li>
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
                <form method="post" action="{{route('items.store')}}">
                	{{ csrf_field() }}
                	<div class="row clearfix">
	                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		                    <label for="purchase_category">Publication</label>
		                    <div class="form-group">
		                        <select class="form-control show-tick search" id="purchase_category" name="purchase_category" data-live-search="true">
		                            <option value="">-- Please select --</option>
		                            @foreach($purchase_category as $list)
		                            <option value="{{$list->id}}">{{$list->name}}</option>
		                            @endforeach
		                        </select>
		                    </div>
		                </div>
		            	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 sub_category">
		                    <label for="sub_category">Series</label>
		                    <div class="form-group">
		                        <select class="form-control show-tick" id="sub_category" name="sub_category" data-live-search="true">
		                            <option value="">-- Please select --</option>
		                        </select>
		                    </div>
		                </div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
		                    <label for="sr_no">Order Form S.No.</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="number" id="sr_no" name="sr_no" class="form-control" placeholder="Enter Order Form S.No." value="{{ old('sr_no') }}" >
		                        </div>
		                    </div>
		                </div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
		                    <label for="code">Item Code</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="code" name="code" class="form-control" placeholder="Enter Item Code" value="{{ old('code') }}" >
		                        </div>
		                    </div>
		                </div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
		                    <label for="name">Rate (₹)</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter expense category name" value="{{ old('name') }}" >
		                        </div>
		                    </div>
		                </div>
		            	<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 ">
		                    <label for="name">Name of Item</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value="{{ old('name') }}" >
		                        </div>
		                    </div>
		                </div>
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 ">
		                    <label for="class">Class/Standard</label>
		                    <div class="form-group">
								<select class="form-control show-tick" id="class" name="class">
		                            <option value="">-- Please select --</option>
		                            <option value="Nursery">Nursery</option>
		                            <option value="LKG">LKG</option>
		                            <option value="UKG">UKG</option>
		                            <option value="1">1</option>
		                            <option value="2">2</option>
		                            <option value="3">3</option>
		                            <option value="4">4</option>
		                            <option value="5">5</option>
		                            <option value="6">6</option>
		                            <option value="7">7</option>
		                            <option value="8">8</option>
		                        </select>
		                    </div>
		                </div>
						<div class="clearfix"></div>
		            	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
		                    <label for="description">Description</label>
		                    <div class="form-group">
		                        <div class="form-line">
		                            <textarea id="description" name="description" rows="1" class="form-control no-resize auto-growth" placeholder="Enter description(press ENTER for more lines)">{{ old('description') }}</textarea>
		                        </div>
		                    </div>
		                </div>
                    </div>
                    <button type="submit" class="btn btn-success m-t-15 waves-effect">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Select Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

@endsection
