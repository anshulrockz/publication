@extends('layouts.app')

@section('content')

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Companies
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/companies') }}">Companies</a></li>
                    <li><a href="{{ url('/companies/'.$company->id) }}">{{$company->name}}</a></li>
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
                <form id="form" method="post" action="{{route('companies.update',$company->id)}}">
                	{{ csrf_field() }}
	                {{ method_field('PUT') }}
	                <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="name">Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter company name" value="{{ $company->name }}" >
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="contact_person">Contact Person</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="contact_person" name="contact_person" class="form-control" placeholder="Enter contact person name" value="{{ $company->contact_person }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="mobile">Mobile</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Enter mobile number" value="{{ $company->mobile }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="email">Email</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter email id" value="{{ $company->email }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="gst">GST Number</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="gst" name="gst" class="form-control" placeholder="Enter company GST number" value="{{ $company->gst }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="transport">Transport & Corurier</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="transport" name="transport" class="form-control" placeholder="Enter company GST number" value="{{ $company->gst }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="type_of_category">Category</label>
                            <div class="form-group">
                                <select class="form-control show-tick" id="type_of_category" name="type_of_category" required>
                                    <option value="1">Seller</option>
                                    <option value="2">Publication</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="state_id">State</label>
                            <div class="form-group">
                                <select class="form-control show-tick" id="state_id" name="state_id" data-live-search="true" required>
                                    <option value="">Please Select...</option>
                                    @foreach($states as $list)
                                    <option value="{{$list->id}}" @if($company->state_id == $list->id) selected @endif>{{$list->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="city_id">City</label>
                            <div class="form-group">
                                <select class="form-control show-tick" id="city_id" name="city_id" data-live-search="true" required>
                                    <option value="{{ $company->city_id }}">{{getFromID($company->city_id, 'cities')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="address">Address</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea id="address" name="address" rows="1" class="form-control no-resize auto-growth" placeholder="Enter address of workshop (press ENTER for more lines)">{{ $company->address }}</textarea>
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


<!-- Select Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

<script>
$( document ).ready(function() {
    $("#form input").prop("disabled", true);
    $("#form select").prop("disabled", true);
    $("#form textarea").prop("disabled", true);
    $("#form-save").prop("disabled", true);
});

$("#form-edit").click(function() {
    $("#form input").prop("disabled", false);
    $("#form select").prop("disabled", false);
    $("#form textarea").prop("disabled", false);
    $("#form-save").prop("disabled", false);
    $('select').selectpicker('refresh');
});
</script>

<script>

$("#state_id").change(function(){ 
    var id = $(this).val();

    if(id != ''){
        $('#city_id option').remove();
        $.ajax({
            type: "GET",
            url: "{{url('cities')}}",
            data:'id='+id,
            success: function(data){
                var data = JSON.parse(data);
                var selOpts = "<option>select</option>";
                if(data.length >0){					
                    // console.log(data); 
                    for (i=0;i<data.length;i++)
                    {
                        var id = data[i].id; 
                        var val = data[i].name;
                        selOpts += "<option value='"+id+"'>"+val+"</option>";
                    }
                    $('#city_id').append(selOpts);
                    $('#city_id').selectpicker('refresh');
                }
                else{
                    $('#city_id option').remove();
                    $('#city_id').selectpicker('refresh');
                }
            }
        });
    }
});

</script>
@endsection
