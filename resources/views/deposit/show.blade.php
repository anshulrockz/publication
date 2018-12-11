@extends('layouts.app')

@section('content')
<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Deposit
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/deposits') }}">Deposit</a></li>
                    <li class="active">{{ $userdetails->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <tbody>
                            <tr>
                            	<th>Payee</th>
                                <td>{{$userdetails->name}}</td>
                            </tr>
                            <tr>
                            	<th>Amount</th>
                                <td>{{$deposit->amount}}</td>
                            </tr>
                            <tr>
                            	<th>Date of Payment</th>
                                <td>{{date_format(date_create($deposit->date),"d M Y")}}</td>
                            </tr>
                            <tr>
                            	<th>Mode of Payment</th>
                                <td>
                                	@if($deposit->mode==1) Cash
                                	@elseif($deposit->mode==2) Cheque
                                	@elseif($deposit->mode==3) Transfer
                                	@endif
                                </td>
                            </tr>
                            @if($deposit->mode != 1)
                            <tr>
                            	<th>Account No</th>
                                <td>{{$deposit->acc_no}}</td>
                            </tr>
                            <tr>
                            	<th>IFSC</th>
                                <td>{{$deposit->ifsc}}</td>
                            </tr>
                            @endif
                            @if($deposit->mode == 3)
                            <tr>
                            	<th>Date Of Cheque/Transaction</th>
                                <td>{{$deposit->txn_date}}</td>
                            </tr>
                            <tr>
                            	<th>Cheque/Transaction No</th>
                                <td>{{$deposit->txn_no}}</td>
                            </tr>
                            <tr>
                            	<th>Remark</th>
                                <td>{{$deposit->remark}}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
