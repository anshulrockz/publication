@extends('layouts.app')

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
                    Deposit
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="active">Deposit</li>
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
                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{ url('/deposits/create')}}">Deposit Now</a>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>Unique ID</th>
                                <th>Date</th>
                                <th>Payment Date</th>
                                <th>Payment Mode</th>
                                <th>Amount</th>
                                <th>Paid To</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <!--  <tfoot>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Payment Mode</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>  -->
                        <tbody>
                        	@foreach( $deposit as $key=>$list)
                            <tr>
                                <td>{{ $list->txn_id }}</td>
                                <td>{{date_format(date_create($list->created_at),"d/m/Y")}}</td>
                                <td>{{date_format(date_create($list->date),"d/m/Y")}}</td>
                                <td>
                                	@if($list->mode==1) Cash
                                	@elseif($list->mode==2) Cheque
                                	@elseif($list->mode==3) Transfer
                                	@endif
                                </td>
                                <td>{{$list->amount}} @if(!empty($list->rem_amount)) ({{$list->rem_amount}}) @endif</td>
                                <td>@if(!empty($list->user)) {{$list->user}} @else {{$list->to_user}} @endif</td>
                                <td>
                                    @if(Auth::user()->user_type==1 || Auth::user()->user_type==3)
                                    <a href="{{ url('/deposits/'.$list->id.'/edit')}}" class="btn btn-xs btn-info"> <i class="material-icons">edit</i> </a>
                                    <form style="display: inline;" method="post" action="{{route('deposits.destroy',$list->id)}}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button onclick="return confirm('Are you sure you want to Delete?');" type="submit"class="btn btn-xs btn-danger"><i class="material-icons">delete</i></button>
                                    </form>
                                    @endif
                                    @if(Auth::user()->user_type==4)
                                    <a href="{{ url('user-transactions/'.$list->to_user)}}" class="btn btn-xs btn-primary"> <i class="material-icons">assessment</i> </a>
                                    <form style="display: inline;" method="post" action="{{url('deposits/return',$list->id)}}">
				                        {{ csrf_field() }}
				                        <button type="submit"class="btn btn-xs btn-primary"><i class="material-icons">assignment_late</i></button>
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
</script>

@endsection
