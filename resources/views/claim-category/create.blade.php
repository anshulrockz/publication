@extends('layouts.app')

@section('content')
<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Claim Category
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/claim-categories') }}">Claim Category</a></li>
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
                <form method="post" action="{{route('claim-categories.store')}}">
                	{{ csrf_field() }}
                    <label for="type_of_category">Type of Category</label>
                    <div class="form-group">
                        <select class="form-control show-tick" id="type_of_category" name="type_of_category" required>
                            <option value="">-- Please select --</option>
                            <option value="1">Make</option>
                            <option value="5">Type of Customer</option>
                            <option value="2">Type of Vehicle</option>
                            <option value="3">Document Verification</option>
                            <option value="4">KYC Verification</option>
                            <option value="6">Insurer</option>
                            <option value="7">Surveyor</option>
                            <option value="8">Vehicle Location</option>
                            <option value="9">Claim Status</option>
                        </select>
                    </div>
                    <label for="name">Name</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter category name" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <label for="phone" class="phone">Phone</label>
                    <div class="form-group phone">
                        <div class="form-line">
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone" value="{{ old('phone') }}" >
                        </div>
                    </div>
                    <label for="description">Description</label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea id="description" name="description" rows="1" class="form-control no-resize auto-growth" placeholder="Enter description(press ENTER for more lines)"> {{ old('description') }} </textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Select Plugin Js -->
    <script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.phone').hide();
    });
    $('#type_of_category').on('change',function(){
        if($(this).val() == '7' || $(this).val() == '6'){
            $('.phone').show();
            $('label[for=description]').text('Address');
            // $('.phone').prop('disabled',false);
        }
        else{
            $('.phone').hide();
            $('label[for=description]').text('Description');
        }
    });
</script>

@endsection
