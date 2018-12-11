@extends('layouts.claim')

@section('content')

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css" rel="stylesheet"/>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Accidental Vehicle Claim
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="active">Claim</li>
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
                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{ url('/claim/create')}}">Create New</a>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable">
                        <thead>
                            <tr>
                                <th>Job Number</th>
                                <th>Vehicle Reg</th>
                                <th>Model</th>
                                <th>Customer Name</th>
                                <th>Mobile</th>
                                <th>Invoice Amount</th>
                                <th>Pending Amount</th>
                                <th>Insurer's Name</th>
                                <th style="width:55px">Last updated</th>
                                <th>Status</th>  
                                <th style="width:140px">Action</th>
                            </tr>
                        </thead>
                        <!-- <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Contact Person</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot> -->
                        <tbody>
                        	@foreach( $data as $key=>$list)
                            <tr>
                                <td>{{$list->job_title}}</td>
                                <td>{{$list->vehicle_num}}</td>
                                <td>{{$list->model_num}}</td>
                                <td>{{$list->name_of_insured}}</td>
                                <td>{{$list->phone_of_insured}}</td>
                                <td>₹ {{$list->invoice_amt + 0}}</td>
                                <td>₹ {{$list->invoice_amt - $list->payment_amt}}</td>
                                <td>{{$list->insurer_name}}</td>
                                <td>{{date_format(date_create($list->updated_at), 'm-d-Y h:i a')}}</td>
                                <td>{{$list->claim_status}}</td>
                                <td>
                                    <a href="{{ url('/claim/'.$list->id)}}" class="btn btn-xs btn-success"> <i class="material-icons">print</i></a>
                                    @if($list->claim_status !== "Completed" || Auth::user()->user_type == 1)
                                    <a href="{{ url('/claim/'.$list->id.'/edit')}}" class="btn btn-xs btn-info"> <i class="material-icons">edit</i> </a>
                                    <form style="display: inline;" method="post" action="{{route('claim.destroy',$list->id)}}">
				                        {{ csrf_field() }}
				                        {{ method_field('DELETE') }}
				                        <button onclick="return confirm('Are you sure you want to Delete?');" type="submit" class="btn btn-xs btn-danger"><i class="material-icons">delete</i></button>
				                    </form>
                                    <button type="button" class="js-modal-buttons btn btn-default btn-xs  waves-effect m-r-20" data-id="{{$list->id}}" data-status="{{$list->claim_status}}" data-toggle="modal" ><i class="material-icons">autorenew</i></button>
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
                <div class="modal-dialog modal-sm" role="document">
                    <form method="post" action="{{url('claim/status-change')}}">
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Payment Details</h4>
                        </div>
                        <div class="modal-body">
                               
                            <label for="name">Current Status</label>
                            <div class="form-group">
                                <div class="form-line status">
                                    <input type="text" id="status" name="status" class="form-control datepicker" placeholder="Enter date" value="{{ old('date') }}" required>
                                </div>
                            </div>
                            <label for="remark">Change Status</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <div class="form-line">
                                        <select class="form-control show-tick" id="category" name="category">
                                            <option value="Pending">-- Please select one --</option>
                                            @foreach($claim_status as $list)
                                            <option value="{{$list->name}}" >{{$list->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
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

<script>
$(document).ready(function() {
    $('.dataTable').DataTable( {
        "order": [[ 1, "desc" ]],
        fixedHeader: {
            header: true,
            headerOffset: $('#navbar-collapse').height()
        }
    } );
} );


$(function () {
    // $('.js-modal-buttons').on('click', function () {
    $('.dataTable').on('click', '.js-modal-buttons', function(e){
        var color = $(this).data('color');
        var id = $(this).data('id');
        var status = $(this).data('status');

        $('#defaultModal .modal-content').removeAttr('class').addClass('modal-content modal-col-' + color);
        
        $('.status').html(status+'<input type="hidden" value="'+id+'" name="id" class="form-control" placeholder="Enter asset category name" >');
        
        $('#defaultModal').modal('show');
    });
});
</script>

@endsection
