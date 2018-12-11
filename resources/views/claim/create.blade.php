@extends('layouts.claim')

@section('content')

<!-- Bootstrap Material Datetime Picker Css -->
<link href="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />

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
                    <li class="active">Create</li>
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
                    Details
                </h2>
            </div>
            <div class="body">
                <form method="post" action="{{route('claim.store')}}" enctype="multipart/form-data">
                	{{ csrf_field() }}
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id="wizard_vertical1">
                                <h2>Customer Detail</h2>
                                <section>
                                                
                                    <div class="col-md-6">
                                        <label for="category">Customer Category</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" id="category" name="category">
                                                    <option value="">-- Please select category --</option>
                                                    @foreach($type_of_customer as $list)
                                                    <option value="{{$list->name}}" >{{$list->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name_of_insured">Name of Insured</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="name_of_insured" name="name_of_insured" class="form-control" placeholder="Enter name" value="{{ old('name_of_insured') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_of_insured">Contact no.</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="phone_of_insured" name="phone_of_insured" class="form-control" placeholder="Enter phone of insured number" value="{{ old('phone_of_insured') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email_of_insured">Email</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="email_of_insured" name="email_of_insured" class="form-control" placeholder="Enter email of insured " value="{{ old('email_of_insured') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address_of_insured">Address</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <textarea id="address_of_insured" name="address_of_insured" class="form-control" placeholder="Enter address of insured " value="{{ old('address_of_insured') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <h2>Vehicle Detail</h2>
                                <section>
                                    <div class="col-md-6">
                                        <label for="vehicle_num">Vehicle Number</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="vehicle_num" name="vehicle_num" class="form-control" placeholder="Enter company vehicle num" value="{{ old('vehicle_num') }}" >
                                                <!-- <label class="form-label" for="name">Customer Category</label> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="chassis_num">Chassis No.</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="chassis_num" name="chassis_num" class="form-control" placeholder="Enter account number" value="{{ old('chassis_num') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="make_num">Make</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" id="make_num" name="make_num">
                                                    <option value="">-- Please select --</option>
                                                    @foreach($make as $list)
                                                    <option data-id="{{$list->id}}" value="{{$list->name}}" >{{$list->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="model_num">Model</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" id="model_num" name="model_num">
                                                    <option value="">-- Please select --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="type_of_vehicle">Type of Vehicle</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" id="type_of_vehicle" name="type_of_vehicle">
                                                    <option value="">-- Please select category --</option>
                                                    @foreach($type_of_vehicle as $list)
                                                    <option value="{{$list->name}}" >{{$list->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="location_of_vehicle">Location of Vehicle</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" id="location_of_vehicle" name="location_of_vehicle">
                                                    <option value="">-- Please select category --</option>
                                                    @foreach($location_of_vehicle as $list)
                                                    <option value="{{$list->name}}" >{{$list->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <h2>Job Detail</h2>
                                <section>
                                    <div class="col-md-6">
                                        <label for="job_date">Job Date</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="job_date" name="job_date" class="form-control datepicker" placeholder="Enter company job date" value="{{ old('job_date') }}" >
                                                <!-- <label class="form-label" for="name">Customer Category</label> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="job_num">Job Number</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="job_num" name="job_num" class="form-control" placeholder="Enter account number" value="{{ old('job_num') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="invoice_date">Invoice Date</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="invoice_date" name="invoice_date" class="form-control datepicker" placeholder="Enter invoice date " value="{{ old('invoice_date') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="invoice_num">Invoice Number</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="invoice_num" name="invoice_num" class="form-control" placeholder="Enter invoice num" value="{{ old('invoice_num') }}" >
                                                <!-- <label class="form-label" for="name">Customer Category</label> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="invoice_amt">Invoice Amount</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="number" id="invoice_amt" name="invoice_amt" class="form-control" placeholder="Enter invoice amount" value="{{ old('invoice_amt') }}" >
                                             </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <label for="payment_date">Payment Received Date</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="payment_date" name="payment_date" class="form-control datepicker" placeholder="Payment Received Date" value="{{ old('payment_date') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="payment_amt">Payment Received Amount</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="payment_amt" name="payment_amt" class="form-control" placeholder="Enter payment rec amt" value="{{ old('payment_amt') }}">
                                            </div>
                                        </div>
                                    </div> -->

                        <div class="col-md-12 " >
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
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-line focused">
                                                    <select class="form-control show-tick" id="job_payment_mode" required > 
                                                       <option value="1" >Cash</option>
                                                       <option value="2" >Cheque</option>
                                                       <option value="3" >Credit/Debit Card</option>
                                                       <option value="4" >Bank Transfer</option>
                                                       <option value="5" >Other (Paytm/BHIM/etc)</option>
                                                       <option value="6" >Credit Note</option>
                                                       <option value="7" >Debit Note</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="job_payment_date" class="form-control datepicker">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" id="job_payment_amt" class="form-control ">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="job_receipt_no" class="form-control">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="job_remark" class="form-control">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary m-t-15 waves-effect add-payment-details"><i class="material-icons">add_circle</i></button> 
                                        </td>
                                    </tr>
                                </thead>
                                <tbody id="payment_details">
                                </tbody>
                            </table>
                        </div>
                                
                                </section>

                                <h2>Insurance/Claim Detail</h2>
                                <section>
                                    <div class="col-md-6">
                                        <label for="insurer_name">Insurer’s Name</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" id="insurer_name" name="insurer_name">
                                                    <option value="">-- Please select --</option>
                                                    @foreach($insurer as $list)
                                                    <option data-address="{{$list->description}}" data-phone="{{$list->phone}}" value="{{ $list->name }}" >{{$list->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="surveyor_name">Surveyor’s Name</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" id="surveyor_name" name="surveyor_name">
                                                    <option value="">-- Please select --</option>
                                                    @foreach($surveyor as $list)
                                                    <option data-address="{{$list->description}}" data-phone="{{$list->phone}}" value="{{$list->name}}" >{{$list->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="insurer_num">Insurer’s Contact Number</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="insurer_num" name="insurer_num" class="form-control" placeholder="Enter Insurer’s Contact number" value="{{ old('insurer_num') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="surveyor_num">Surveyor’s Contact Number</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="surveyor_num" name="surveyor_num" class="form-control" placeholder="Enter Surveyor’s Contact Number" value="{{ old('surveyor_num') }}" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="office_add">Office Address</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="office_add" name="office_add" class="form-control" placeholder="Enter Office Address" value="{{ old('office_add') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="surveyor_add">Surveyor’s Address</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="surveyor_add" name="surveyor_add" class="form-control" placeholder="Enter surveyor add " value="{{ old('surveyor_add') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="policy_num">Policy Number</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="policy_num" name="policy_num" class="form-control" placeholder="Enter policy num" value="{{ old('policy_num') }}" >
                                                <!-- <label class="form-label" for="name">Customer Category</label> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="claim_num">Claim Number</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="claim_num" name="claim_num" class="form-control" placeholder="Enter claim number" value="{{ old('claim_num') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="accident_date">Accident Date</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="accident_date" name="accident_date" class="form-control datepicker" placeholder="Enter accident date" value="{{ old('accident_date') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="insured_amount">Sum of Insured Amount</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="insured_amount" name="insured_amount" class="form-control" placeholder="Enter insured amount" value="{{ old('insured_amount') }}" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cost_of_repair">Estimated Cost of Repair</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="cost_of_repair" name="cost_of_repair" class="form-control" placeholder="Enter Cost of Repair" value="{{ old('cost_of_repair') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="survey_date">Date of Survey</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="survey_date" name="survey_date" class="form-control datepicker" placeholder="Enter survey date" value="{{ old('survey_date') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="survey_place">Place of Survey</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="survey_place" name="survey_place" class="form-control" placeholder="Enter survey place" value="{{ old('survey_place') }}" >
                                                <!-- <label class="form-label" for="name">Customer Category</label> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="reinspection_date">Date of Re-inspection</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="reinspection_date" name="reinspection_date" class="form-control datepicker" placeholder="Enter reinspection date" value="{{ old('reinspection_date') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driver_name">Driver’s Name</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="driver_name" name="driver_name" class="form-control" placeholder="Enter driver name" value="{{ old('driver_name') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driver_licence_num">Driver’s License Number</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="driver_licence_num" name="driver_licence_num" class="form-control" placeholder="Enter  driver licence num" value="{{ old('driver_licence_num') }}" >
                                                <!-- <label class="form-label" for="name">Customer Category</label> -->
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <h2>Document Detail</h2>
                                <section>
                                    <div class="row">
                                        <div class="col-md-4">
                                           <label for="code">Select Document Type</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-control show-tick" id="document" name="document" >
                                                    <option value="">-- Please select --</option>
                                                    @foreach($doc_verification as $list)
                                                    <option value="{{$list}}" >{{$list}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-3">-->
                                        <!--    <div class="form-group">-->
                                        <!--        <select class="form-control show-tick" id="doc_status" name="doc_status" >-->
                                        <!--            <option value="0">-- Please select --</option>-->
                                        <!--            <option value="Yes">Yes</option>-->
                                        <!--            <option value="No">No</option>-->
                                        <!--        </select>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <div class="col-md-2">
                                            <button type="button" id="add-row" class="add-row btn btn-xs btn-success m-t-15 waves-effect " ><i class="material-icons">add_circle</i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                           <label for="code">Document</label>
                                        </div>
                                        <!--<div class="col-md-3">-->
                                        <!--    <label for="code">Received</label>-->
                                        <!--</div>-->
                                        <div class="col-md-6">
                                            <label for="code">Upload Document</label>
                                        </div>
                                    </div>
                                    <div class="data-field"></div>
                                </section>

                                <h2>KYC Verification</h2>
                                <section>
                                    <div class="row">
                                        <div class="col-md-4">
                                           <label for="code">Select Document Type</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-control show-tick" id="document_2" >
                                                    <option value="">-- Please select --</option>
                                                    @foreach($kyc_verification as $list)
                                                    <option value="{{$list->id}}" >{{$list->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-3">-->
                                        <!--    <div class="form-group">-->
                                        <!--        <select class="form-control show-tick" id="doc_status_2" >-->
                                        <!--            <option value="0">-- Please select --</option>-->
                                        <!--            <option value="Yes">Yes</option>-->
                                        <!--            <option value="No">No</option>-->
                                        <!--        </select>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <div class="col-md-2">
                                            <button type="button" id="add-row-2" class="add-row-2 btn btn-xs btn-success m-t-15 waves-effect " ><i class="material-icons">add_circle</i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                           <label for="code">Document</label>
                                        </div>
                                        <!--<div class="col-md-3">-->
                                        <!--    <label for="code">Received</label>-->
                                        <!--</div>-->
                                        <div class="col-md-6">
                                            <label for="code">Upload Document</label>
                                        </div>
                                    </div>
                                    <div class="data-field-2"></div>
                                </section>

                            </div>
                        </div>
                    </div>
            <!-- #END# Basic Example | Vertical Layout -->


                    
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success waves-effect">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Moment Plugin Js -->
<script src="{{ asset('bsb/plugins/momentjs/moment.js')}}"></script>

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>

<script>

$(document).on("click", "#wizard_vertical1", function () {
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });
});

</script>

<!-- JQuery Steps Plugin Js -->
<script src="{{ asset('bsb/plugins/jquery-steps/jquery.steps.js') }}"></script>

<script type="text/javascript">
    // $(".add-payment-details").click(function(){
    $('#wizard_vertical1').on('click', '.add-payment-details', function(){ 

        var job_payment_mode = $('#job_payment_mode option:selected').text();
        var job_payment_date = $('#job_payment_date').val(); 
        var job_payment_amt = $('#job_payment_amt').val();
        var job_receipt_no = $('#job_receipt_no').val();
        var job_remark = $('#job_remark').val();
 

        if(job_payment_mode == '' || job_payment_mode == 0){ alert("Please select payment mode") }
        else if(job_payment_date == ''){ alert("Please enter payment date") }
        else if(job_payment_amt == '' ){ alert("Please enter payment amount") }
        else if(job_receipt_no == ''){ alert("Please enter receipt no") }

        else{
                    
            var delBtn = '<button type="button" class="btn btn-danger btn-xs m-t-15 waves-effect delete-tr"><i class="material-icons">remove_circle</i></button>';

            var markup = '<tr>'+
                                '<td>'+
                                    '<div class="form-group">'+job_payment_mode+
                                        '<input type="hidden" name="entry_payment_mode[]" class="form-control" value="'+job_payment_mode+'" >'+
                                    '</div>'+
                                '</td>'+
                                '<td>'+
                                    '<div class="form-group">'+job_payment_date+
                                        '<input type="hidden" name="entry_payment_date[]" class="form-control" value="'+job_payment_date+'" >'+
                                    '</div>'+
                                '</td>'+
                                '<td>'+
                                    '<div class="form-group">'+job_payment_amt+
                                        '<input type="hidden" name="entry_payment_amt[]" class="form-control" value="'+job_payment_amt+'" >'+
                                    '</div>'+
                                '</td>'+
                                '<td>'+
                                    '<div class="form-group">'+job_receipt_no+
                                        '<input type="hidden" name="entry_receipt_no[]" class="form-control" value="'+job_receipt_no+'" >'+
                                    '</div>'+
                                '</td>'+
                                '<td>'+
                                    '<div class="form-group">'+job_remark+
                                        '<input type="hidden" name="entry_remark[]" class="form-control" value="'+job_remark+'" >'+
                                    '</div>'+
                                '</td>'+
                                
                                '<td>'+
                                    delBtn+
                                '</td>'+
                            '</tr>';
                              
            $("#payment_details").prepend(markup);

            $('#job_payment_date').val(''); 
            $('#job_payment_amt').val('');
            $('#job_receipt_no').val('');
            $('#job_remark').val('');
        }

    });

    $('#wizard_vertical1').on('click', '.delete-tr', function(e){
        e.preventDefault();
        $(this).closest("tr").remove();
    });

</script>

<script type="text/javascript">
$(function(){  
    
    $('#wizard_vertical1').on('click', '.add-row', function(e){
        var doc_type = $('#document option:selected').text();
        // var doc_type_id = $('#document option:selected').val();
        // var doc_file = $('#doc_file').val();
        


        if(doc_type == '-- Please select --' || doc_type == '' ){ alert("Please select Document") }
        
        else{
            var delBtn = '<button type="button" class="btn btn-danger btn-xs m-t-15 waves-effect delete-row" data-id="document"><i class="material-icons">remove_circle</i></button>';

            var markup = '<div class="row">'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+doc_type+
                                    '<input type="hidden" name="doc_type[]" class="form-control" value="'+doc_type+'" >'+
                                '</div>'+
                            '</div>'+
                            // '<div class="col-md-3">'+
                            //     '<div class="form-group">'+doc_status+
                            //         '<input type="hidden" name="doc_status[]" class="form-control" value="'+doc_status+'" >'+
                            //     '</div>'+
                            // '</div>'+
                            '<div class="col-md-6">'+
                                '<div class="form-group">'+
                                    '<div class="form-line">'+ 
                                        '<input type="file" name="doc_file[]"  class="form-control"  required>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                delBtn+
                                '<input type="hidden" name="doc_category[]" class="form-control" value="1" required>'+
                            '</div>'+
                        '</div>';
            $('#document option:selected').remove();
            $(".data-field").append(markup);
        }

    });

    $('#wizard_vertical1').on('click', '.add-row-2', function(e){
        
        var doc_type = $('#document_2 option:selected').text();
        // var doc_status = $('#doc_status_2 option:selected').val();
        
        if(doc_type == '' || doc_type == '-- Please select --' ){ alert("Please select Document") }
        // else if(doc_status == '0'){ alert("Please select status") }
        else{
            var delBtn = '<button type="button" class="btn btn-danger btn-xs m-t-15 waves-effect delete-row" data-id="document_2"><i class="material-icons">remove_circle</i></button>';

            var markup = '<div class="row">'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+doc_type+
                                    '<input type="hidden" name="doc_type[]" class="form-control" value="'+doc_type+'" >'+
                                '</div>'+
                            '</div>'+
                            // '<div class="col-md-3">'+
                            //     '<div class="form-group">'+doc_status+
                            //         '<input type="hidden" name="doc_status[]" class="form-control" value="'+doc_status+'" >'+
                            //     '</div>'+
                            // '</div>'+
                            '<div class="col-md-6">'+
                                '<div class="form-group">'+
                                    '<div class="form-line">'+ 
                                        '<input type="file" name="doc_file[]"  class="form-control"  required>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                delBtn+
                                '<input type="hidden" name="doc_category[]" class="form-control" value="2" required>'+
                            '</div>'+
                        '</div>';
            $('#document_2 option:selected').remove();
            $(".data-field-2").append(markup);
        }

    });

    $('#wizard_vertical1').on('click', '.delete-row', function(e){
        e.preventDefault();
        var name = $(this).closest(".row").find('input').val();
        var type = $(this).data('id');
        var markup = '<option value="'+name+'">'+name+'</option>'
        $('#'+type).append(markup);
        // $(this).closest("select").append(markup);
        $(this).closest(".row").remove();
    });

    $('#wizard_vertical1').on('change', '#surveyor_name', function(e){
        e.preventDefault();
        var address = $('#surveyor_name option:selected').data("address");
        var phone = $('#surveyor_name option:selected').data("phone");
        $('#surveyor_num').val(phone);
        $('#surveyor_add').val(address);
    });

    $('#wizard_vertical1').on('change', '#insurer_name', function(e){
        e.preventDefault();
        var address = $('#insurer_name option:selected').data("address");
        var phone = $('#insurer_name option:selected').data("phone");
        $('#insurer_num').val(phone);
        $('#office_add').val(address);
    });
});

</script>

<!-- Demo Js -->
<script >
        
$(function () {
    $('#wizard_vertical1').steps({
        headerTag: 'h2',
        bodyTag: 'section',
        transitionEffect: 'slideLeft',
        stepsOrientation: 'vertical',
        onInit: function (event, currentIndex) {
            setButtonWavesEffect(event);
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            setButtonWavesEffect(event);
        },
    });
});

function setButtonWavesEffect(event) {
    $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
    $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
}

</script>


<script>



$(function(){
// $("#expense_category_main").change(function(){
$('#wizard_vertical1').on('change', '#make_num', function(){
    var id = $('#make_num option:selected').data('id'); 
    if(id != ''){
        $('#model_num option').remove();
        $.ajax({
            type: "GET",
            url: "{{url('/sub-claim-ajax/ajax')}}",
            data:'id='+id,
            success: function(data){
                var data = JSON.parse(data);
                var selOpts = "<option>-- select --</option>";
                if(data.length >0){                 
                    console.log(data); 
                    for (i=0;i<data.length;i++)
                    {
                        var id = data[i].id; 
                        var val = data[i].name;
                        selOpts += "<option value='"+val+"'>"+val+"</option>";
                    }
                    $('#model_num').append(selOpts);
                    $('#model_num').selectpicker('refresh');
                }
                else{
                    $('#model_num option').remove();
                }
            }
        });
    }
    else{
            $('#sub_expenses option').remove();
    }
});
});
</script>
@endsection
