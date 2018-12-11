@extends('layouts.app')

@section('content')

<!-- Bootstrap Material Datetime Picker Css -->
<link href="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="{{ asset('bsb/css/datatable-style.css')}}" rel="stylesheet" /> 

<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css" rel="stylesheet"/>

<style type="text/css">
    tfoot tr th:nth-child(2), th{
        text-align: center;
    }
    
    tfoot tr th{
        text-align: right;
    }
    
    td:nth-child(5), td:nth-child(4), td:nth-child(3){
        text-align: right;
    }
</style>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <!-- <h1>
                    {{ getFromID(Auth::user()->company_id, 'companies') }}
                    {{ getFromID($vendor->location, 'workshops') }}<br> 
                </h1> -->
                <h1>{{ $vendor->name }}</h1>
                <h2>{{ getFromID($vendor->location, 'workshops') }}</h2>
                <h2>{{ $vendor->address }}</h2>
                <a class="btn btn-primary waves-effect header-dropdown m-r--5" href="{{ url('payment-vendors/create?id='.$vendor->id)}}">Pay Now</a>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/payment-vendors') }}">Payments</a></li>
                    <li class="active">{{ $vendor->name }}</li>
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
                    <div class="row clearfix">
                        <form method="post" action="{{url('print-out',$vendor->id)}}" target="_blank">
                        {{ csrf_field() }}
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label">
                                <label for="start">From</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <!-- <div class="form-line"> -->
                                        <input type="text" id="start" name="start" class="datepicker form-control" placeholder="Please choose a from date...">
                                    <!-- </div> -->
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label">
                                <label for="end">To</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <!-- <div class="form-line"> -->
                                        <input type="text" id="end" name="end" class="datepicker form-control" placeholder="Please choose a from date...">
                                        <input type="hidden" id="vendor_id" name="vender_id" value="{{ $vendor->id }}">
                                    <!-- </div> -->
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-2 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <!-- <div class="form-line"> -->
                                        <button type="submit" class="btn">Print</button>
                                    <!-- </div> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Particulars</th>
                                <th class="debit">Debit</th>
                                <th class="credit">Credit</th>
                                <th>Action</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $debit = 0;
                                $credit = 0;
                            @endphp
                            @foreach( $transaction as $key=>$list)
                            @php
                                $debit += $list->debit;
                                $credit += $list->credit;
                            @endphp
                            <tr>
                                <td>{{date_format(date_create($list->invoice_date),"d/m/Y")}}</td>
                                <td>{{$list->particulars}}</td>
                                <td class="debit">{{$list->debit}} </td>
                                <td class="credit">{{$list->credit}} </td>
                                <td>
                                    <form style="display: inline;" method="post" action="{{route('payment-vendors.destroy',$list->id)}}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button onclick="return confirm('Are you sure you want to Delete?');" type="submit"class="btn btn-xs btn-danger"
                                        @if($list->txn_type != 2) disabled @endif 
                                        ><i class="material-icons">delete</i></button>
                                    </form>
                                </td>
                                <td>{{date_format(date_create($list->invoice_date),"m/d/y")}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th id="debitTol"></th>
                                <th class="creditTol"></th>
                                <th></th>
                            </tr>
                            <!-- <tr> 
                                <th></th>
                                <th>Balance</th>
                                <th></th>
                                <th class="balanceTol">{{round($balance = $debit - $credit,2)}}</th>
                                <th></th>
                            </tr> -->
                        </tfoot>
                    </table>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 right">
                        <div class="info-box-3 bg-red">
                            <div class="icon">
                                <i class="material-icons">&#x20B9;</i>
                            </div>
                            <div class="content balance">
                                <div class="text">BALANCE</div>
                                <div class="number count-to balanceTol" data-from="0" data-to="125" data-speed="1000" data-fresh-interval="20">{{round($balance,2) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 right">
                        <div class="info-box-3 bg-green">
                            <div class="icon">
                                <i class="material-icons">&#x20B9;</i>
                            </div>
                            <div class="content credit">
                                <div class="text">Credit</div>
                                <div class="number count-to creditTol" data-from="0" data-to="125" data-speed="1000" data-fresh-interval="20">{{$credit}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 right">
                        <div class="info-box-3 bg-blue">
                            <div class="icon">
                                <i class="material-icons">&#x20B9;</i>
                            </div>
                            <div class="content debit">
                                <div class="text">Debit</div>
                                <div class="number count-to debitTol" data-from="0" data-to="125" data-speed="1000" data-fresh-interval="20">{{$debit}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Jquery DataTable Plugin Js -->
<script src="{{ asset('bsb/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>
<script src="{{ asset('bsb/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('bsb/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>

<!-- Moment Plugin Js -->
<script src="{{ asset('bsb/plugins/momentjs/moment.js')}}"></script>

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>

<!-- Select Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

<script>
$('.datepicker').bootstrapMaterialDatePicker({
    format: 'MM/DD/YY',
    clearButton: true,
    weekStart: 1,
    time: false
});
</script>


<script>

@php

$company = getAllFromID(Auth::user()->company_id, "companies") ;
$workshop = getAllFromID($vendor->location, "workshops") ;

@endphp

var normalizeDate = function(dateString) {
  var date = new Date(dateString);
  var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
  return normalized;
}

$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var start = normalizeDate( $('#start').val() );
        var end = normalizeDate( $('#end').val() );
        var colDate = normalizeDate( data[5] ) || 0;
 
        if ( ( isNaN( start ) && isNaN( end ) ) ||
             ( isNaN( start ) && colDate <= end ) ||
             ( start <= colDate && isNaN( end ) ) ||
             ( start <= colDate && colDate <= end )
        ) 
        {
            return true;
        }
        
        return false;
    }
);

$(document).ready(function() {
    document.title = 'Ledger';
    
    var table = $('.dataTable').DataTable({
        "order": false, //[[ 6, "asc" ]], //
        // "paging":   false,
        // dom: 'Bfrtip',
        responsive: true,
        buttons: [
            {
                extend: 'print',
                footer: true,
                title: '',
                exportOptions: {
                    columns: [ 0, 1, 2, 3 ],
                },
                customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<h3 style="width:100%;text-align:center"><u>{{ $company->name }} </u></h3>'+
                            '<h5 style="width:100%;text-align:center">{{ trim(preg_replace("/\s+/", ' ', $workshop->address)) }}</h5>'+
                            '<h5 style="width:100%;text-align:center"> {{ $workshop->gst }}</h5>' + 
                            '<h4 style="width:100%;text-align:center">{{ $vendor->name }}</h4>' + 
                            '<h5 style="width:100%;text-align:center"> {{ $vendor->address }} </h5>'+
                            '<h5 style="width:100%;text-align:center"> {{ $vendor->gst }} </h5>'
                        );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                    
                }
            }
        ],
        
        "initComplete": function (settings, json) {
            var api = this.api();
            CalculateTableSummary(this);
        },
        
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data; 
            CalculateTableSummary(this);
            return ;       
        },
    });

    table.column( 5 ).visible( false );


    $('#start, #end').change( function() { 
        table.draw();
    });

    function CalculateTableSummary(table) {
    try {

        var intVal = function (i) {
            return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            var api = table.api();
            api.columns(".debit, .credit").eq(0).each(function (index) {
                var column = api.column(index,{page:'current'});

                var sum = column
                   .data()
                   .reduce(function (a, b) {
                       //return parseInt(a, 10) + parseInt(b, 10);
                       return intVal(a) + intVal(b);
                   }, 0);

                // console.log(sum);

                $(column.footer()).html('' + sum.toFixed(2));

            });
        } catch (e) {
            console.log('Error in CalculateTableSummary');
            console.log(e)
        }
    }
    
} );
</script>

@endsection
