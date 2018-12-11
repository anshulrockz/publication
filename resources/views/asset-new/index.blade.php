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
                    Asset New
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="active">Asset New</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    All
                </h2>
                
                @if(Auth::user()->user_type != 4)
	                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{ url('/assets/new/create')}}">Add New</a>
	        @endif
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>Asset ID</th>
                                <th>Asset Category</th>
                                <th>Model</th>
                                <th>Make</th>
                                <th>Manufacturing Yr</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Warranty Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@foreach( $asset as $key=>$list)
                            <tr>
                            	<td>{{ $list->voucher_no }}</td>
                                <td>{{$list->main_category}}
	                                @if(!empty($list->sub_assets))
	                            	- {{$list->sub_assets}}
	                            	@endif
                                </td>
                                <td>{{$list->model}}</td>
                                <td>{{$list->make}}</td>
                                <td>{{$list->mfg}}</td>
                                <td>{{$list->amount}}</td>
                                <td>{{$list->status}}</td>
                                <td>{{date_format(date_create($list->expiry),"d/m/Y")}}</td>
                                <td>
                                    <!-- <a href="{{ url('/assets/new/'.$list->id)}}" class="btn btn-sm btn-success"> View </a> -->
                                    <a href="{{ url('/assets/new/'.$list->id.'/edit')}}" class="btn btn-xs btn-info"> <i class="material-icons">edit</i> </a>
                                    <form style="display: inline;" method="post" action="{{route('new.destroy',$list->id)}}">
				                        {{ csrf_field() }}
				                        {{ method_field('DELETE') }}
				                        <button onclick="return confirm('Are you sure you want to delete?');" type="submit" class="btn btn-xs btn-danger"><i class="material-icons">delete</i></button>
				                    </form>
                                    <a title="barcode" onclick="PrintBR('{{ $list->voucher_no }}')" class="btn btn-xs bg-blue-grey waves-effect"> <i class="material-icons">payment</i> </a>
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

<script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
<svg id="barcode" style="display:none"></svg>
<script type="text/javascript">
    function PrintBR(voucher_no) {
        //var w = window.open();
        JsBarcode("#barcode", voucher_no);
        //$(w.document.body).html("<svg>"+barcode+"</svg>");
        printbar();

    }
    function printbar() {
        var w = window.open();
        var data = $("#barcode").html();
        $(w.document.body).html("<svg>"+data+"</svg>");
        w.print();
    }
</script>

@endsection
