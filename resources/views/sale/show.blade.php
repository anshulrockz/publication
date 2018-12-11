@extends('layouts.app')

@section('content')

<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
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
                    <li class="active">{{$expense->voucher_no}}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
				<div class="row clearfix">
				    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
		                <div class="table-responsive">
		                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
		                        <tbody>
		                            <tr>
		                            	<th>Spent By</th>
		                                <td>{{$userdetails[0]}}</td>
		                            </tr>
		                            <tr>
		                            	<th>Expense Category</th>
		                                <td>
		                                	{{$expense->category}}
			                                @if(!empty($expense->main_category))
			                                	- {{$expense->main_category}}
			                                @endif
			                                @if(!empty($expense->sub_expense))
			                            		- {{ $expense->sub_expense }}
			                            	@endif
		                                </td>
		                            </tr>
		                            <tr>
		                            	<th>Subject</th>
		                                <td>{{$expense->subject}}</td>
		                            </tr>
		                            <tr>
		                            	<th>Amount</th>
		                                <td>{{$expense->amount}}</td>
		                            </tr>
		                            <tr>
		                            	<th>Voucher No</th>
		                                <td>{{$expense->voucher_no}}</td>
		                            </tr>
		                            <tr>
		                            	<th>Voucher Date</th>
		                                <td>{{date_format(date_create($expense->voucher_date),"d F Y")}}</td>
		                            </tr>
		                            <tr>
		                            	<th>Invoice No</th>
		                                <td>{{$expense->invoice_no}}</td>
		                            </tr>
		                            <tr>
		                            	<th>Invoice Date</th>
		                                <td>{{date_format(date_create($expense->invoice_date),"d F Y")}}</td>
		                            </tr>
		                            <tr>
		                            	<th>Remark</th>
		                                <td>{{$expense->remarks}}</td>
		                            </tr>
		                        </tbody>
		                    </table>
		                </div>
                	</div>
                	@if(!empty($expense->voucher_img))
	                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
	                	<img src="{{asset('uploads/expenses/'.$expense->voucher_img )}}" width="100%" height="100%"/>
	                </div>
	                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
