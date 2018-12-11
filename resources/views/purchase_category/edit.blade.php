@extends('layouts.app')

@section('content')

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Purchase Category
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/purchase-categories') }}">Purchase Category</a></li>
                    <li><a href="{{ url('/purchase-categories/'.$purchase_category->id) }}">{{$purchase_category->name}}</a></li>
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
                <form method="post" action="{{route('purchase-categories.update',$purchase_category->id)}}">
                	{{ csrf_field() }}
	                {{ method_field('PUT') }}
	                <label for="employee_type">Department</label>
                    <div class="form-group">
                        <select class="form-control show-tick search" id="employee_type" name="employee_type" required>
                            <option value="">-- Please select purchase category --</option>
                            @foreach($department as $list)
                            <option value="{{$list->id}}" @if($list->id==$purchase_category->department_id) selected="selected" @endif >{{$list->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="name">Name</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter purchase category name" value="{{ $purchase_category->name }}" required >
                        </div>
                    </div>
                    <label for="description">Description</label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea id="description" name="description" rows="1" class="form-control no-resize auto-growth" placeholder="Enter description(press ENTER for more lines)">{{ $purchase_category->description }}</textarea>
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

@endsection
