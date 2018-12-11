@extends('layouts.claim')

@section('content')

<style type="text/css">
    h2 { page-break-inside:auto }
    section   { page-break-inside:avoid; } /* This is the key */
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
    th{
        text-align:center;
        width:60%;
    }
    
    td{
        text-align:left;
    }
</style>

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
                    <li><a href="{{ url('/claim') }}">Claim</a></li>
                    <li class="active"><a href="{{ url('/claim/'.$customer_details->id) }}">{{$customer_details->name_of_insured}}</a></li>
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
                    Details
                </h2>
                <button class="btn btn-success header-dropdown waves-effect " onclick="printDiv('printableArea')" type="button" />Print </button>
                <div class="row" >
                    <div class="col-md-3" id="status">
                        <h4>Status: {{$customer_details->claim_status}}</h4>
                    </div>
                    <div class="col-md-3">
                        <h5>
                            
                        </h5>
                    </div>
                </div>
            </div>
            <div id="printableArea">
                <div class="body"  >
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div>
                                    <h2 style="page-break-before: always">Customer Detail</h2>
                                    <section class="row" style="display-inline">
                                        <div class="col-md-6">
                                            <label for="category">Customer Category</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="name_of_insured" name="name_of_insured" class="form-control" placeholder="Enter name" value="{{ $customer_details->category }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="name_of_insured">Name of Insured</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="name_of_insured" name="name_of_insured" class="form-control" placeholder="Enter name" value="{{ $customer_details->name_of_insured }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone_of_insured">Contact no.</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="phone_of_insured" name="phone_of_insured" class="form-control" placeholder="Enter phone of insured number" value="{{ $customer_details->phone_of_insured }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email_of_insured">Email</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="email_of_insured" name="email_of_insured" class="form-control" placeholder="Enter email of insured " value="{{ $customer_details->email_of_insured }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="address_of_insured">Address</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <textarea id="address_of_insured" name="address_of_insured" class="form-control" placeholder="Enter address of insured ">{{ $customer_details->address_of_insured }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                    <br>

                                    </section>
                                    <h2 style="page-break-before: always">Vehicle Detail</h2><br>
                                    <section class="row" >
                                        <div class="col-md-6">
                                            <label for="vehicle_num">Vehicle Number</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="vehicle_num" name="vehicle_num" class="form-control" placeholder="Enter company vehicle num" value="{{ $vehicle_details->vehicle_num }}" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="chassis_num">Chassis No.</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="chassis_num" name="chassis_num" class="form-control" placeholder="Enter account number" value="{{ $vehicle_details->chassis_num }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="make_num">Make/Model</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" id="make_num" name="make_num">
                                                        <option value="">{{$vehicle_details->make_num}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="model_num">Model</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" id="model_num" name="model_num">
                                                        <option value="">{{$vehicle_details->model_num}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="type_of_vehicle">Type of Vehicle</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" id="type_of_vehicle" name="type_of_vehicle">
                                                        <option value="">{{$vehicle_details->type_of_vehicle}}</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <h2 style="page-break-before: always">Job Detail</h2><br>
                                    <section class="row">
                                        <div class="col-md-6">
                                            <label for="job_date">Job Date</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="job_date" name="job_date" class="form-control datepicker" placeholder="Enter company job date" value="{{ date_format(date_create($claim_job_detail->job_date),'d-m-Y') }}" >
                                                    <!-- <label class="form-label" for="name">Customer Category</label> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="job_num">Job Number</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="job_num" name="job_num" class="form-control" placeholder="Enter account number" value="{{ $claim_job_detail->job_num }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="invoice_date">Invoice Date</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="invoice_date" name="invoice_date" class="form-control datepicker" placeholder="Enter invoice date name" value="{{ date_format(date_create($claim_job_detail->invoice_date),'d-m-Y') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="invoice_num">Invoice Number</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="invoice_num" name="invoice_num" class="form-control" placeholder="Enter company invoice num" value="{{ $claim_job_detail->invoice_num }}" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="invoice_amt">Invoice Amount</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="invoice_amt" name="invoice_amt" class="form-control" placeholder="Enter invoice amount" value="{{ $claim_job_detail->invoice_amt }}" >
                                                 </div>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-6">-->
                                        <!--    <label for="payment_date">Payment Received Date</label>-->
                                        <!--    <div class="form-group">-->
                                        <!--        <div class="form-line">-->
                                        <!--            <input type="text" id="payment_date" name="payment_date" class="form-control datepicker" placeholder="Payment Received Date" value="{{ date_format(date_create($claim_job_detail->payment_date),'d-m-Y') }}">-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <!--<div class="col-md-6">-->
                                        <!--    <label for="payment_amt">Payment Received Amount</label>-->
                                        <!--    <div class="form-group">-->
                                        <!--        <div class="form-line">-->
                                        <!--            <input type="text" id="payment_amt" name="payment_amt" class="form-control" placeholder="Enter payment rec amt" value="{{ $claim_job_detail->payment_amt }}">-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                            @if(count($claim_job_entry)>0)
                            <table class="table table-bordered table-striped table-hover " >
                                <thead>
                                    <tr>
                                        <th>Payment Mode</th>
                                        <th>Payment Received Date</th>
                                        <th>Payment Received Amount</th>
                                        <th>Receipt number</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="payment_details">
                                	@foreach($claim_job_entry as $key => $value)
                                	<tr>
                                        <td>
                                            <div class="form-group">{{$value->entry_payment_mode}}
                                                <input type="hidden"   class="form-control" value="" >
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">{{ date_format(date_create($value->entry_payment_date),'d/m/y') }}
                                                <input type="hidden"   class="form-control" value="" >
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">{{$value->entry_payment_amt}}
                                                <input type="hidden"  class="form-control entry_payment_amt" value="{{$value->entry_payment_amt}}" >
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">{{$value->entry_receipt_no}}
                                                <input type="hidden"  class="form-control" value="" >
                                            </div>
                                        </td>
                                        <td>
                                          	<div class="form-group">{{$value->entry_remark}}
                                                <input type="hidden"  ="form-control" value="" >
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <button type="button" class="btn btn-danger btn-xs m-t-15 waves-effect delete-tr" data-id="{{$value->id}}"><i class="material-icons">remove_circle</i></button>
                                        </td>
                                    </tr>
                                	@endforeach
                                </tbody>
                            </table>
                            @endif
                                        
                                    </section>

                                    <h2 style="page-break-before: always">Insurance/Claim Detail</h2><br>
                                    <section class="row">
                                        <div class="col-md-6">
                                            <label for="insurer_name">Insurer’s Name</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" id="insurer_name" name="insurer_name">
                                                        <option value="">{{$claim_detail->insurer_name}}</option>
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="surveyor_name">Surveyor’s Name</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" id="surveyor_name" name="surveyor_name">
                                                        <option value="">{{$claim_detail->surveyor_name}}</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="insurer_num">Insurer’s Contact Number</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="insurer_num" name="insurer_num" class="form-control" placeholder="Enter Insurer’s Contact number" value="{{ $claim_detail->insurer_num }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="surveyor_num">Surveyor’s Contact Number</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="surveyor_num" name="surveyor_num" class="form-control" placeholder="Enter Surveyor’s Contact Number" value="{{ $claim_detail->surveyor_num }}" >
                                                    <!-- <label class="form-label" for="name">Customer Category</label> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="office_add">Office Address</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="office_add" name="office_add" class="form-control" placeholder="Enter Office Address" value="{{ $claim_detail->office_add }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="surveyor_add">Surveyor’s Address</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="surveyor_add" name="surveyor_add" class="form-control" placeholder="Enter surveyor add " value="{{ $claim_detail->surveyor_add }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="policy_num">Policy Number</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="policy_num" name="policy_num" class="form-control" placeholder="Enter policy num" value="{{ $claim_detail->policy_num }}" >
                                                    <!-- <label class="form-label" for="name">Customer Category</label> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="claim_num">Claim Number</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="claim_num" name="claim_num" class="form-control" placeholder="Enter claim number" value="{{ $claim_detail->claim_num }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="accident_date">Accident Date</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="accident_date" name="accident_date" class="form-control datepicker" placeholder="Enter accident date" value="{{ date_format(date_create($claim_detail->accident_date),'d-m-Y') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="insured_amount">Sum of Insured Amount</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="insured_amount" name="insured_amount" class="form-control" placeholder="Enter insured amount" value="{{ $claim_detail->insured_amount }}" >
                                                    <!-- <label class="form-label" for="name">Customer Category</label> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cost_of_repair">Estimated Cost of Repair</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="cost_of_repair" name="cost_of_repair" class="form-control" placeholder="Enter Cost of Repair" value="{{ $claim_detail->cost_of_repair }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="survey_date">Date of Survey</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="survey_date" name="survey_date" class="form-control datepicker" placeholder="Enter survey date" value="{{ date_format(date_create($claim_detail->survey_date),'d-m-Y') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="survey_place">Place of Survey</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="survey_place" name="survey_place" class="form-control" placeholder="Enter survey place" value="{{ $claim_detail->survey_place }}" >
                                                    <!-- <label class="form-label" for="name">Customer Category</label> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="reinspection_date">Date of Re-inspection</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="reinspection_date" name="reinspection_date" class="form-control datepicker" placeholder="Enter reinspection date" value="{{ date_format(date_create($claim_detail->reinspection_date),'d-m-Y') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="driver_name">Driver’s Name</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="driver_name" name="driver_name" class="form-control" placeholder="Enter driver name" value="{{ $claim_detail->driver_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="driver_licence_num">Driver’s License Number</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="driver_licence_num" name="driver_licence_num" class="form-control" placeholder="Enter  driver licence num" value="{{ $claim_detail->driver_licence_num }}" >
                                                    <!-- <label class="form-label" for="name">Customer Category</label> -->
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    
                                    <h2 style="page-break-before: always">Document Detail</h2><br>
                                    <section class="row">
                                        
                                        <br>
                                        <div class="col-md-12">
                                        <table class="old-fields table">
                                            <tbody>
                                            @foreach($document_details_1 as $key => $value)
                                             <tr class="row"> 
                                                <th >
                                                    <div class="form-group">{{ $value->doc_type }}
                                                        <input type="hidden" /*name="doc_type[]"*/ class="form-control" value="doc_type+'" >
                                                    </div>
                                                </th>
                                                <!--<td>-->
                                                <!--    <div class="form-group">{{$value->doc_status}}-->
                                                <!--        <input type="hidden" /*name="doc_status[]"*/ class="form-control" value="doc_status+'" >-->
                                                <!--    </div>-->
                                                <!--</td>-->
                                                <td >
                                                    <!--<div class="form-group">-->
                                                    <!--    <div class="fallback">-->
                                                                <img border="0" src="{{ asset('uploads/claims/'.$value->doc_file)}}" alt="click to view" width="50" height="50" title="{{$value->doc_file }}" onerror="this.src='{{ asset('no-image.jpg')}}'" >
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                </td>
                                             </tr> 
                                            @endforeach
                                            
                                            @foreach($doc_verification as $value)
                                             <tr class="row"> 
                                                <th >
                                                    <div class="form-group">{{ $value }}
                                                    </div>
                                                </th>
                                                <!--<td>-->
                                                <!--    <div class="form-group">No-->
                                                <!--    </div>-->
                                                <!--</td>-->
                                                <td >
                                                    <div class="form-group">
                                                        <div class="fallback">
                                                            Not Found
                                                        </div>
                                                    </div>
                                                </td>
                                             </tr> 
                                            @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                    </section>
                                    
                                    <h2 style="page-break-before: always">KYC Verification</h2>
                                    <section class="row">
                                        
                                        <br>
                                        <div class="col-md-12">
                                        <table class="old-fields table">
                                            <tbody>
                                            @foreach($document_details_2 as $key => $value)
                                             <tr class="row"> 
                                                <th >
                                                    <div class="form-group">{{ $value->doc_type }}
                                                        <input type="hidden" /*name="doc_type[]"*/ class="form-control" value="doc_type+'" >
                                                    </div>
                                                </th>
                                                <!--<td>-->
                                                <!--    <div class="form-group">{{$value->doc_status}}-->
                                                <!--        <input type="hidden" /*name="doc_status[]"*/ class="form-control" value="doc_status+'" >-->
                                                <!--    </div>-->
                                                <!--</td>-->
                                                <td >
                                                    <!--<div class="form-group">-->
                                                    <!--    <div class="fallback">-->
                                                                <img border="0" src="{{ asset('uploads/claims/'.$value->doc_file)}}" alt="click to view" width="50" height="50" title="{{$value->doc_file }}" onerror="this.src='{{ asset('no-image.jpg')}}'" >
                                                                    
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                </td>
                                             </tr> 
                                            @endforeach
                                            
                                            @foreach($kyc_verification as $value)
                                             <tr class="row"> 
                                                <th >
                                                    <div class="form-group">{{ $value }}
                                                    </div>
                                                </th>
                                                <!--<td>-->
                                                <!--    <div class="form-group">No-->
                                                <!--    </div>-->
                                                <!--</td>-->
                                                <td >
                                                    <div class="form-group">
                                                        <div class="fallback">
                                                            Not Found
                                                        </div>
                                                    </div>
                                                </td>
                                             </tr> 
                                            @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                    </section>

                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function printDiv(divName) {

        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.title = "Accidental Vehicle Claim Detail";
        var title = "Accidental Vehicle Claim Detail";
        var status = document.getElementById('status').innerHTML;;

        document.body.innerHTML = '<h1>'+title+'</h1><h3>'+status+'</h3><div style="width:100%">'+printContents+'</div>';

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>

<script type="text/javascript">
    
$('.print').click( function() {
    var w = window.open();
    
    var title = "Accidental Vehicle Claim Detail";
    var link = "{{ asset('bsb/plugins/bootstrap/css/bootstrap.css') }}";
    // var header = $(".printableArea").html();
    var body = $("#printableArea").html();
    w.document.title = title;
    w.document.write('<link rel="stylesheet" href="'+link+'" type="text/css" />');
    // w.document.write('<style type="text/css">.test { color:red; } </style></head><body>');
    $(w.document.body).html("<h1 style='text-align:center;text-transform:uppercase;'>"+title+"</h1><div style='text-align:left;width:100%'>"+body+"</div>");
    // w.print();
});

</script>
@endsection
