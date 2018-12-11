@extends('layouts.app')

@section('content')

<!-- Bootstrap Material Datetime Picker Css -->
<link href="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<link href="{{ asset('bsb/css/datatable-style.css')}}" rel="stylesheet" /> 

<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css" rel="stylesheet"/>


<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Report
                </h2>
                <!-- <button class="btn btn-primary waves-effect header-dropdown m-r--5" id="print" >Print</button> -->
            </div>
            <div class="body">
                <div class="row ">
                    <div class="col-sm-12">
                        <ol class="breadcrumb breadcrumb-bg-pink">
                            <li><a href="{{ url('/dashboard') }}">Home</a></li>
                            <li class="active">Insurance Claim Report</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header" >
                <h2 id="header">
                    Insurance Claim  
                </h2>
                <!-- <button class="btn btn-primary waves-effect header-dropdown m-r--5" id="print" >Print</button> -->
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label">
                        <label for="email_address_2">From</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <div class="form-group">
                            <!-- <div class="form-line"> -->
                                <input type="text" id="start" class="datepicker form-control" placeholder="Please choose a from date...">
                            <!-- </div> -->
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label">
                        <label for="email_address_2">To</label>
                    </div>
                    <div class="col-lg-3 col-md-2 col-sm-3 col-xs-3">
                        <div class="form-group">
                            <!-- <div class="form-line"> -->
                                <input type="text" id="end" class="datepicker form-control" placeholder="Please choose a from date...">
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable">
                        <thead class="print-header">
                            <tr>
                                <th>Name of insured</th>
                                <th>Category</th>
                                <th>Phone of insured</th>
                                <th>Email of insured </th>
                                <th>Address of insured </th>
                                <th>Insurer name </th>
                                <th>Surveyor name</th>
                                <th>Insurer num</th>
                                <th>Surveyor num</th>
                                <th>Office add</th>
                                <th>Surveyor add</th>
                                <th>Policy num </th>
                                <th>Claim num </th>
                                <th>Accident date</th>
                                <th>Insured amount</th>
                                <th>Cost of repair</th>
                                <th>Survey date</th>
                                <th>Survey place</th>
                                <th>Reinspection date</th>
                                <th>Driver name</th>
                                <th>Driver licence num</th>
                                <th>Vehicle num</th>
                                <th>Chassis num</th>
                                <th>Make num</th>
                                <th>Model num </th>
                                <th>Type of vehicle</th>
                                <th>Job date</th>
                                <th>Job num</th>
                                <th>Invoice date</th>
                                <th>Invoice num</th>
                                <th>Invoice amt</th>
                                <th>Payment amt</th>
                                <th>Booked by</th>
                            </tr>
                        </thead>
                        <tfoot style="display: table-header-group;">
                            <tr>
                                <th>Name of insured</th>
                                <th>Category</th>
                                <th>Phone of insured</th>
                                <th>Email of insured </th>
                                <th>Address of insured </th>
                                <th>Insurer name </th>
                                <th>Surveyor name</th>
                                <th>Insurer num</th>
                                <th>Surveyor num</th>
                                <th>Office add</th>
                                <th>Surveyor add</th>
                                <th>Policy num </th>
                                <th>Claim num </th>
                                <th>Accident date</th>
                                <th>Insured amount</th>
                                <th>Cost of repair</th>
                                <th>Survey date</th>
                                <th>Survey place</th>
                                <th>Reinspection date</th>
                                <th>Driver name</th>
                                <th>Driver licence num</th>
                                <th>Vehicle num</th>
                                <th>Chassis num</th>
                                <th>Make num</th>
                                <th>Model num </th>
                                <th>Type of vehicle</th>
                                <th>Job date</th>
                                <th>Job num</th>
                                <th>Invoice date</th>
                                <th>Invoice num</th>
                                <th>Invoice amt</th>
                                <th>Payment amt</th>
                                <th>Booked by</th>
                            </tr>
                        </tfoot>
                        <tbody class="print-body">
                        	@foreach($report as $key => $value)
                            <tr>
                                <td>{{ $value->name_of_insured }}</td>
                                <td>{{ $value->category }}</td>
                                <td>{{ $value->phone_of_insured }}</td>
                                <td>{{ $value->email_of_insured  }}</td>
                                <td>{{ $value->address_of_insured  }}</td>
                                <td>{{ $value->insurer_name  }}</td>
                                <td>{{ $value->surveyor_name }}</td>
                                <td>{{ $value->insurer_num }}</td>
                                <td>{{ $value->surveyor_num }}</td>
                                <td>{{ $value->office_add }}</td>
                                <td>{{ $value->surveyor_add }}</td>
                                <td>{{ $value->policy_num  }}</td>
                                <td>{{ $value->claim_num  }}</td>
                                <td>{{ date_format(date_create($value->accident_date),"d/m/Y") }}</td>
                                <td>{{ $value->insured_amount }}</td>
                                <td>{{ $value->cost_of_repair }}</td>
                                <td>{{ date_format(date_create($value->survey_date),"d/m/Y") }}</td>
                                <td>{{ $value->survey_place }}</td>
                                <td>{{ $value->reinspection_date }}</td>
                                <td>{{ $value->driver_name }}</td>
                                <td>{{ $value->driver_licence_num }}</td>
                                <td>{{ $value->vehicle_num }}</td>
                                <td>{{ $value->chassis_num }}</td>
                                <td>{{ $value->make_num }}</td>
                                <td>{{ $value->model_num  }}</td>
                                <td>{{ $value->type_of_vehicle }}</td>
                                <td>{{ date_format(date_create($value->job_date),"d/m/Y") }}</td>
                                <td>{{ $value->job_num }}</td>
                                <td>{{ $value->invoice_date }}</td>
                                <td>{{ $value->invoice_num }}</td>
                                <td>{{ $value->invoice_amt }}</td>
                                <td>{{ $value->payment_amt }}</td>
                                <td>
                                    @php 
                                        $user = getAllFromID($value->created_by, 'users'); 
                                        echo $user->name;
                                    @endphp
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
<script src="{{ asset('bsb/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('bsb/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('bsb/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
<script src="{{ asset('bsb/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
<script src="{{ asset('bsb/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
<script src="{{ asset('bsb/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
<script src="{{ asset('bsb/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
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

var normalizeDate = function(dateString) {
  var date = new Date(dateString);
  var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
  return normalized;
}

$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var start = normalizeDate( $('#start').val() );
        var end = normalizeDate( $('#end').val() );
        var colDate = normalizeDate( data[0] ) || 0;
 
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
    document.title = $("#header").html();
    var table = $('.datatable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [ 'print', 'excel',
             // {
             //            extend: 'print',
             //            exportOptions: {
             //        columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25 ]
             //    }
             //        },
             //        {
             //            extend: 'excel',
             //            exportOptions: {
             //        columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25 ]
             //    }
             //        }
        ],
        "order": [[ 0, "asc" ]],
        // fixedHeader: {
        //     header: true,
        //     headerOffset: $('#navbar-collapse').height()
        // },
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    });
     
    table.column( 0 ).visible( false );
    //table.column( 1 ).visible( false );

    $('#start, #end').change( function() {
        table.draw();
    } );
} );

</script>

<script type="text/javascript">
    
$('#print').click( function() {
    var w = window.open();
    var title = $("#header").html();
    var header = $(".print-header").html();
    var body = $(".print-body").html();
    w.document.title = title;
    $(w.document.body).html("<h1 style='text-align:center;text-transform:uppercase;'>"+title+"</h1><table style='text-align:left;width:100%'>"+header+body+"</table>");
    w.print();
});

</script>

@endsection
