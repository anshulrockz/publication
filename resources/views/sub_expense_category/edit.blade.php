@extends('layouts.app')

@section('content')
<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
  
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Sub Expense Category
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('dashboard') }}">Home</a></li>
                    <li><a href="{{ url('sub-expense-categories') }}">Sub Expense Category</a></li>
                    <li><a href="{{ url('sub-expense-categories'.$sub_expense->id) }}">{{$sub_expense->name}}</a></li>
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
                <form method="post" action="{{route('sub-expense-categories.update',$sub_expense->id)}}">
                	{{ csrf_field() }}
	                {{ method_field('PUT') }}
                    <label for="expense_category">Expense Category</label>
                    <div class="form-group">
                        <select class="form-control show-tick" id="expense_category" name="expense_category">
                            <option value="">-- Please select expense category --</option>
                            @foreach($expense_category as $list)
                            <option value="{{$list->id}}" @if($list->id==$sub_expense->expense_category){ selected="selected" } @endif >{{$list->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="name">Name</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter expense category name" value="{{ $sub_expense->name }}" >
                        </div>
                    </div>
                    <label for="description">Description</label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea id="description" name="description" rows="1" class="form-control no-resize auto-growth" placeholder="Enter description(press ENTER for more lines)">{{ $sub_expense->description }}</textarea>
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
