@extends('layouts.app')

@section('content')

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

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
                    <li><a href="{{ url('dashboard') }}">Home</a></li>
                    <li><a href="{{ url('expense-categories') }}">Expense Category</a></li>
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
                <form method="post" action="{{route('expense-categories.store')}}">
                	{{ csrf_field() }}
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label for="expense_category">Supply Type</label>
                            <div class="form-group">
                                <select class="form-control show-tick search" id="expense_category" name="expense_category">
                                    <option value="">-- Please select supply type --</option>
                                    <option value="Service">Service</option>
                                    <option value="Material">Material</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 sub_expenses">
                            <label for="sub_expenses">Supply Category</label>
                            <div class="form-group">
                                <select class="form-control show-tick" id="sub_expenses" name="sub_expenses">
                                    <option value="">-- Please select supply category --</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Non-Workshop">Non-Workshop</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label for="name">Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter expense category name" value="{{ old('name') }}" >
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label for="description">Description</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea id="description" name="description" rows="1" class="form-control no-resize auto-growth" placeholder="Enter description(press ENTER for more lines)">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Select Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

@endsection
