@extends('layouts.claim')

@section('content')
<!-- Bootstrap Select Css -->
<link href="{{ asset('bsb/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
  
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Challan
                </h2>
                <button class="btn btn-primary waves-effect header-dropdown m-r-10" id="print">Print</button>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('dashboard') }}">Home</a></li>
                    <li><a href="{{ url('challan') }}">Challan</a></li>
                    <li><a href="{{ url('challan/'.$challan->uid) }}">{{$challan->uid}}</a></li>
                    <li><a href="{{ url('challan/'.$challan->uid.'/edit') }}">Edit</li>
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

<div class="row clearfix printarea">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header row justify-content-center">
                <!-- <img src="" alt="PLS"> -->
                <div class="form-line" class="balName">
                    <div class="col-sm-12" style="text-align:center">
                        <h3>Material Gate Pass</h3>
                    </div>
                    <div class="col-sm-12">
                        <h4>To</h4>
                        <h4>Security Officer, Main Gate</h4>
                        <h4>Please allow the following material to be taken out:</h4>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-sm-6">
                        <label for="uid">Challan No.(auto)</label>&nbsp;{{ $challan->uid }}
                    </div>
                    <div class="col-sm-6">
                        <label for="created_at">Challan Date</label>&nbsp;{{ date_format(date_create($challan->created_at), 'd-F-Y') }}
                    </div>
                    <div class="col-sm-6">
                        <label for="from_unit">From Location</label>&nbsp;
                    </div>
                    <div class="col-sm-6">
                        <label for="to_unit">To Location</label>&nbsp;
                    </div>
                    <div class="col-sm-6">
                        <label for="reciever">Name of the person carrying material </label>&nbsp;{{ $challan->reciever }}
                    </div>
                    <div class="col-sm-6">
                        <label for="vehicle_num">Vehicle no.</label>&nbsp;{{ $challan->vehicle_num }}
                    </div>
                    <div class="col-sm-6">
                        <label for="job_num">Job no.</label>&nbsp;{{ $challan->job_num }}
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-sm-12 " >
                        <table class="table table-bordered table-hover itemtable" >
                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Item code</th>
                                    <th>Item name</th>
                                    <th>Quantity</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody class="data-field">
                                @foreach($challan_details as $key => $value)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{$value->item_code}}</td>
                                    <td>{{$value->item_name}}</td>
                                    <td>{{$value->item_qty}}</td>
                                    <td>{{$value->item_description}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <label>Security Officer Name</label>&nbsp;{{ $challan->security_officer }}
                    </div>
                    <div class="col-sm-12">
                        <label>Remark</label>&nbsp;{{ $challan->remark }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select Plugin Js -->
<script src="{{ asset('bsb/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

<script type="text/javascript">
    
    $('#print').click( function() {
        var printBlock = $(this).parents('.printarea').siblings('.printarea');
        printBlock.hide();
        window.print();
        printBlock.show();
        // var w = window.open();
        // var title = $("#header").html();
        // // var header = $(".printout").html();
        // var body = $(".printout").html();
        // w.document.title = title;
        // $(w.document.body).html(body);
        // // $(w.document.body).html("<h1 style='text-align:center;text-transform:uppercase;'>"+title+"</h1><table style='text-align:left;width:100%'>"+header+body+"</table>");
        // w.print();
    });
    
</script>

@endsection
