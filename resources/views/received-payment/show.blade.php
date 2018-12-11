@extends('layouts.app')

@section('content')
<!-- JQuery DataTable Css -->
<link href="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet"/>
    
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Received Payment
                </h2>

                <button type="button" class="js-modal2-buttons btn btn-primary header-dropdown m-r--5 waves-effect " data-id="{{$paymentother->uid}}" data-amount="{{$paymentother->amount}}" data-toggle="modal" title="Deposit To Bank" >Add More</button>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/received-payments') }}">Received Payment</a></li>
                    <li class="active">{{ $paymentother->uid }}</li>
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
                        <thead>
                            <tr>
                                <th></th>
                                <th>Job No</th>
                                <th>Receipt No</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                            <tbody class="data-field">
                                @foreach( $job_details as $key=>$list)
                                <tr>
                                    <td>
                                        {{++$key}}
                                        <input name="detailid[]" class="form-control " type="hidden" value="'{{$list->id}}'"  />
                                    </td>
                                    <td>
                                        {{$list->job_no}}
                                    </td>
                                    <td>
                                        {{$list->receipt_no}}
                                    </td>
                                    <td class="jdamount">
                                        {{$list->amount}}
                                    </td>
                                    <td>
                                        <form style="display: inline;" method="post" action="{{route('job-details.destroy',$list->id)}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button onclick="return confirm('Are you sure you want to delete?');" type="submit" class="btn btn-xs btn-danger" title="DELETE"><i class="material-icons">delete</i></button>
                                        </form>
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
                                    {{$paymentother->uid}}
                                    <input type="hidden" id="voucher_no" name="voucher_no" class="form-control" placeholder="Enter asset category name" value="{{ $paymentother->uid }}" >
                                    }
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

<script type="text/javascript">
    $('.js-modal2-buttons').on('click', function () {
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

    $('.add-row').on('click', function () {

        var amount = $('#defaultModal2').find('input[name=amount]').val();
        var job_no = $(this).closest('tr').find('#job_no').val();
        var receipt_no = $(this).closest('tr').find('#receipt_no').val();
        var job_amount = $(this).closest('tr').find('#job_amount').val();
        var total_cost = 0;
        var total_amount=0;

        $("[class *= 'jdamount']").each(function(){
            total_amount += +$(this).text(); 
        }); 

        $("input[name *= 'job_amount']").each(function(){
            total_cost += +$(this).val();
        }); 

        total_cost = parseFloat(total_cost)+parseFloat(job_amount)+parseFloat(total_amount);

        if(amount < total_cost){ alert("Total job amount cannot be greater than "+amount) }
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
</script>


@endsection
