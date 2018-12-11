@extends('layouts.app')

@section('content')

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css" rel="stylesheet"/>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />

    
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
                    <li class="active">Expense</li>
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
                    All
                </h2>
                @if(Auth::user()->user_type!=1)
                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{ url('/expenses/create')}}">Add New</a>
                @endif

            </div>
            <div class="body">
                <div class="">
                    <table class="table table-bordered table-striped table-hover dataTable">
                        <thead>
                            <tr>
                                <th>Voucher No</th>
                                <th>Invoice No</th>
                                <th>Invoice Date</th>
                                <th>Seller Name</th>
                                <th>Amount</th>
                                <!-- <th>Payment</th> -->
                                <th>Booked By</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@foreach( $expense as $key=>$list)
                            <tr>
                                <td>{{ $list->voucher_no }}</td>
                            	<td>{{ $list->invoice_no }}</td>
                                <td>{{date_format(date_create($list->invoice_date),"d/m/Y")}}</td>
                                <td>{{$list->party_name}}</td>
                                <td>
                                    @if($list->round_off==1) {{round(($list->cost+$list->cgst+$list->sgst+$list->igst),0)}} @else {{($list->cost+$list->cgst+$list->sgst+$list->igst)}} @endif
                                </td>
                                <!-- <td>
                                    @if($list->mode==1 || $list->mode=='Cash') Paid
                                    @else Unpaid
                                        @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 5 && $list->status == 1)
                                            <button type="button" class="js-modal-buttons btn btn-default btn-xs  waves-effect m-r-20" data-id="{{$list->voucher_no}}" data-amount="{{$list->amount}}" data-toggle="modal" ><i class="material-icons">attach_money</i></button> 
                                        @endif
                                    @endif
                                </td> -->
                                <td>{{$list->user}}</td>
                                <td>
                                	@if($list->status==1) Active
                                	@else Cancelled
                                	@endif
                                    @if($list->mode==1 || $list->mode=='Cash') (Paid)
                                    @endif
                                </td>
                                <td>
                                    <!-- <a href="{{ url('/expenses/'.$list->id)}}" class="btn btn-xs btn-success"> View </a> -->
                                    @if($list->status==1)
                                    <a href="{{ url('/expenses/'.$list->id.'/edit')}}" class="btn btn-xs btn-info"> <i class="material-icons">edit</i> </a>
                                    
                                    @php
            	                		$date1=date_create($list->created_at);
            							$date2=date_create(date("y-m-d H:i:s"));
            							$diff=date_diff($date2,$date1);
            							$days = $diff->format("%a");
        	                		@endphp
        	                		@if($days<1)
                                    <a href="{{ url('/expenses/cancel/'.$list->id)}}" class="btn btn-xs btn-warning"> <i class="material-icons">cancel</i> </a>
                                    @endif
                                    @endif
                                    @if(Auth::id()==1)
                                    <form style="display: inline;" method="post" action="{{route('expenses.destroy',$list->id)}}">
				                        {{ csrf_field() }}
				                        {{ method_field('DELETE') }}
				                        <button onclick="return confirm('Are you sure you want to delete?');" type="submit" class="btn btn-xs btn-danger" title="Cancel"><i class="material-icons">delete</i></button>
				                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dialogs ====================================================================================================================== -->
            <!-- Default Size -->
            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{url('payment-history.store')}}">
                                {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Payment Details</h4>
                        </div>
                        <div class="modal-body">
                                
                                <label for="voucher_no">Voucher No</label>
                                <div class="form-group">
                                    <div class="form-line" id="voucher_no_div">
                                        <input type="hidden" id="voucher_no" name="voucher_no" class="form-control" placeholder="Enter asset category name" value="{{ old('voucher_no') }}" >
                                    </div>
                                </div>
                                <label for="amount">Amount</label>
                                <div class="form-group">
                                    <div class="form-line" id="amount_div">
                                        <input type="hidden" id="amount" name="amount" class="form-control" placeholder="Enter asset category name" value="{{ old('amount') }}" >
                                    </div>
                                </div>
                                <label for="name">Date</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="date" name="date" class="form-control datepicker" placeholder="Enter date" value="{{ old('date') }}" required>
                                    </div>
                                </div>
                                <label for="remark">Remark</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea id="remark" name="remark" rows="1" class="form-control no-resize auto-growth" placeholder="Enter remark(press ENTER for more lines)">{{ old('remark') }}</textarea>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-link waves-effect">SAVE</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

<!-- Jquery DataTable Plugin Js -->
    <script src="{{ asset('bsb/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>

<!-- Select Plugin Js -->
    <script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
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
</script>

<script>
$(document).ready(function() {
    $('.dataTable').DataTable( {
        // "processing": true,
        // "serverSide": true,
        // "ajax":{
        //              "url": "/fetch_records",
        //              "dataType": "json",
        //              "type": "POST",
        //              "data":{ _token: "{{csrf_token()}}"}
        //            },
        "order": [[ 1, "desc" ]],
        fixedHeader: {
            header: true,
            headerOffset: $('#navbar-collapse').height()
        }
    });
});

$(function () {
    $('.js-modal-buttons').on('click', function () {
        var color = $(this).data('color');
        var voucher_no = $(this).data('id');
        var amount = $(this).data('amount');

        $('#defaultModal .modal-content').removeAttr('class').addClass('modal-content modal-col-' + color);
        
        $('#voucher_no_div').html(voucher_no+'<input type="hidden" value="'+voucher_no+'" name="voucher_no" class="form-control" placeholder="Enter asset category name" >');

        $('#amount_div').html(amount+'<input type="hidden" value="'+amount+'" name="amount" class="form-control" placeholder="Enter asset category name" >');

        //$('#voucher_no').val(voucher_no);
        //$('#amount').val(amount);
        
        $('#defaultModal').modal('show');
    });
});
</script>

@endsection
