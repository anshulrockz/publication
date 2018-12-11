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
                    Received Payment
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="active">Received Payment</li>
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
                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{route('received-payments.create')}}">Add New</a>
            </div>
            <div class="body">
                <div class="">
                    <table class="table table-bordered table-striped table-hover dataTable">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Date</th>
                                <th>Seller Name</th>
                                <th>Amount</th>
                                <th>Booked By</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $data as $key=>$list)
                            <tr>
                                <td>{{ $list->uid }}</td>
                                <td>{{date_format(date_create($list->created_at),"d/m/Y")}}</td>
                                <td>{{$list->party_name}}</td>
                                <td>
                                   {{round($list->amount,2)}} 
                                </td>
                                
                                <td>{{$list->user}}</td>
                                <td>
                                    @if($list->mode==1) Cash
                                    @elseif($list->mode==2) Cheque
                                    @elseif($list->mode==3) Card
                                    @elseif($list->mode==4) Other
                                    @endif
                                </td>
                                <td>
                                        @if($list->payment_status==1 ) 
                                            Received
                                            @if($list->mode!=1)
                                            <button type="button" class="js-modal2-buttons btn btn-success btn-xs  waves-effect " data-id="{{$list->uid}}" data-amount="{{$list->amount}}" data-toggle="modal" title="Deposit To Bank" @if($list->job_entry==1) disabled @endif ><i class="material-icons" >done_all</i></button>
                                            @endif
                                        @elseif($list->payment_status=='2') {{$list->narrator}} (Not Received)
                                        @elseif($list->payment_status=='4')  Cheque not deposited yet 
                                        <a href="{{ url('/payment/others/cheque-status/'.$list->id)}}" class="btn btn-xs btn-info"><i class="material-icons">play_for_work</i></a>
                                        @else Pending
                                            @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 5)
                                                <button type="button" class="js-modal-buttons btn btn-default btn-xs  waves-effect " data-id="{{$list->uid}}" data-amount="{{$list->amount}}" data-toggle="modal" title="Deposit To Bank"><i class="material-icons">done</i></button>
                                            @endif
                                        @endif
                                </td>
                                <td>
                                    @if($list->job_entry==1) 
                                    <a href="{{ url('/received-payments/'.$list->id)}}" class="btn btn-xs btn-primary" title="Job Entries"> <i class="material-icons">chrome_reader_mode</i> </a>
                                    @endif
                                    @if($list->payment_status==0 || $list->payment_status==4)
                                        <a href="{{ url('/received-payments/'.$list->id.'/edit')}}" class="btn btn-xs btn-info"> <i class="material-icons">edit</i> </a>
                                        @php
                                            $date1=date_create($list->created_at);
                                            $date2=date_create(date("y-m-d H:i:s"));
                                            $diff=date_diff($date2,$date1);
                                            $days = $diff->format("%a");
                                        @endphp
                                        @if( $days<'1' )
                                        <!-- <a href="{{ url('/payment/others/cancel/'.$list->id)}}" class="btn btn-xs btn-warning"> <i class="material-icons">cancel</i> </a> -->
                                        @endif
                                        @if(Auth::user()->id==1 || Auth::user()->id==5)
                                        <form style="display: inline;" method="post" action="{{route('received-payments.destroy',$list->id)}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button onclick="return confirm('Are you sure you want to delete?');" type="submit" class="btn btn-xs btn-danger" title="DELETE"><i class="material-icons">delete</i></button>
                                        </form>
                                        @endif
                                    @endif
                                    <div class="clearfix"></div>
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
                    <form method="post" action="{{url('payment/others/change-status')}}">
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Payment Details</h4>
                        </div>
                        <div class="modal-body">
                            <label for="voucher_no">Sr No</label>
                            <div class="form-group">
                                <div class="form-line" id="voucher_no_div">
                                    <input type="hidden" id="voucher_no" name="voucher_no" class="form-control" placeholder="Enter asset category name" value="{{ old('voucher_no') }}" >
                                </div>
                            </div>
                            <label >Payment Received?</label>
                            <div class="form-line">
                                <input name="group1" type="radio" id="radio_1" value="1" checked />
                                <label for="radio_1">Yes</label>
                                <input name="group1" type="radio" id="radio_2" value="2" />
                                <label for="radio_2">No</label>
                                <input name="tax_type" id="tax_type" class="form-control" type="hidden" />
                            </div>
                            <label for="date">Date</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="date" name="date" class="form-control datepicker" placeholder="Enter date" value="{{ old('date') }}" required>
                                </div>
                            </div>
                            <label for="narrator">Narrator</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="narrator" name="narrator" class="form-control" placeholder="Enter narrator" value="{{ old('narrator') }}" required>
                                </div>
                            </div>
                            <label for="amount">Amount Received</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="amount" name="amount" class="form-control" placeholder="Enter amount" value="{{ old('amount') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success waves-effect">SAVE</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>


<!-- Modal Dialogs ====================================================================================================================== -->
            <!-- Default Size -->
            <div class="modal fade" id="defaultModal2" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{route('job-details.store')}}">
                                {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Payment Details</h4>
                        </div>
                        <div class="modal-body">
                            <label for="voucher_no">Sr No</label>
                            <div class="form-group">
                                <div class="form-line" id="voucher_no_div2">
                                    <input type="hidden" id="voucher_no" name="voucher_no" class="form-control" placeholder="Enter asset category name" value="{{ old('voucher_no') }}" >
                                </div>
                            </div>
                            <label for="amount">Amount</label>
                            <div class="form-group">
                                <div class="form-line" id="amount_div2">
                                    <input type="hidden" id="amount" name="amount" class="form-control" placeholder="Enter asset category name" value="{{ old('amount') }}" >
                                </div>
                            </div>
                            <table class="table table-striped dataTable2">
                                <thead>
                                    <tr>
                                        <th><label for="bank">Job No.</label></th>
                                        <th><label for="bank">Receipt No.</label></th>
                                        <th><label for="bank">Amount</label></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="job_no" class="form-control" placeholder="Enter Job">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-line" >
                                                    <input type="text" id="receipt_no" class="form-control" placeholder="Enter receipt no"  >
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" id="job_amount" class="form-control" placeholder="Enter amount"  >
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-success add-row" title="Add"><i class="material-icons">add_circle</i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success waves-effect">SAVE</button>
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
        "order": [[ 1, "desc" ]],
        fixedHeader: {
            header: true,
            headerOffset: $('#navbar-collapse').height()
        }
    });
});

$(function () {
    $('.dataTable').on('click', '.js-modal-buttons', function () {
        var color = $(this).data('color');
        var voucher_no = $(this).data('id');
        var amount = $(this).data('amount');

        $('#defaultModal .modal-content').removeAttr('class').addClass('modal-content modal-col-' + color);
        
        $('#voucher_no_div').html('<input type="text" value="'+voucher_no+'" name="voucher_no" class="form-control" placeholder="Enter asset category name" readonly>');

        $('#amount_div').html('<input type="text" value="'+amount+'" name="amount" class="form-control" placeholder="Enter asset category name" readonly >');

        //$('#voucher_no').val(voucher_no);
        //$('#amount').val(amount);
        
        $('#defaultModal').modal('show');
    });

    $('.dataTable').on('click', '.js-modal2-buttons', function () {
        var color = $(this).data('color');
        var voucher_no = $(this).data('id');
        var amount = $(this).data('amount');

        $('#defaultModal2 .modal-content').removeAttr('class').addClass('modal-content modal-col-' + color);
        
        $('#voucher_no_div2').html('<input type="text" value="'+voucher_no+'" name="voucher_no" class="form-control" placeholder="Enter asset category name" readonly>');

        $('#amount_div2').html('<input type="text" value="'+amount+'" name="amount" class="form-control" placeholder="Enter asset category name" readonly >');

        //$('#voucher_no').val(voucher_no);
        //$('#amount').val(amount);
        
        $('#defaultModal2').modal('show');
    });

    $('.dataTable2').on('click', '.add-row', function () {

        var amount = $('#defaultModal2').find('input[name=amount]').val();
        var job_no = $(this).closest('tr').find('#job_no').val();
        var receipt_no = $(this).closest('tr').find('#receipt_no').val();
        var job_amount = $(this).closest('tr').find('#job_amount').val();
        var total_cost = 0;

        $("input[name *= 'job_amount']").each(function(){
            total_cost += +$(this).val();
        }); 
        total_cost = parseFloat(total_cost)+parseFloat(job_amount);

        if(amount < total_cost){ alert("Job amount cannot be greater than Amount") }
        else if(receipt_no == ''){ alert("Please fill all the details") }
        else if(job_no == ''){ alert("Please fill all the details") }
        else if(job_amount == ''){ alert("Please fill all the details ") }
        else {
            var delBtn = '<button type="button" class="btn btn-danger btn-xs m-t-15 waves-effect delete-row"><i class="material-icons">remove_circle</i></button>'; 

            var markup = '<tr><td><div class="form-group"><div class="form-line"><input type="text" name="job_no[]" class="form-control" placeholder="Enter Job" value="'+job_no+'" ></div></div></td><td><div class="form-group"><div class="form-line" ><input type="text"  name="receipt_no[]" class="form-control" placeholder="Enter receipt no" value="'+receipt_no+'" ></div></div></td><td><div class="form-group"><div class="form-line"><input type="text" name="job_amount[]" class="form-control" placeholder="Enter amount" value="'+job_amount+'" ></div></div></td><td>'+delBtn+'</td></tr>'; 
                                          
            $(".dataTable2 tbody").append(markup);

            $(this).closest('tr').find('#job_no').val('');
            $(this).closest('tr').find('#receipt_no').val('');
            $(this).closest('tr').find('#job_amount').val('');
        }
    })

    $('.dataTable2').on('click', '.delete-row', function () {
        $(this).closest("tr").remove();
    })

});


</script>

@endsection
