@extends('layouts.app')

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="header">
                <h2>
                    Vendor
                </h2>
            </div>
            <div class="body">
                <ol class="breadcrumb breadcrumb-bg-pink">
                    <li><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li><a href="{{ url('/vendors') }}">Vendor</a></li>
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
                <form method="post" action="{{route('vendors.store')}}">
                	{{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <label for="uid">Vendor ID</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value="{{ $voucher_no  }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="name">Vendor Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value="{{ old('name') }}" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="contact_person">Contact Person Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="contact_person" name="contact_person" class="form-control" placeholder="Enter Name" value="{{ old('contact_person') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="gstin">Vendor GSTIN</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="gstin" name="gstin" class="form-control" placeholder="Enter gstin " value="{{ old('gstin') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="state_code">State(code)</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" id="state_code" name="state_code" >
                                        <option >select</option>
                                        @foreach($state as $list)
                                        <option value="{{$list->id}}">{{$list->name}} ({{sprintf("%02d",$list->id)}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Please enter" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="mobile">Mobile Number</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" id="mobile" name="mobile" class="form-control" placeholder="Please enter" value="{{ old('mobile') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="location">Location</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" id="location" name="location" @if(Auth::user()->user_type == 3 || Auth::user()->user_type == 4) disabled @endif>
                                        <option >select</option>
                                        @foreach($location as $list)
                                        <option value="{{$list->id}}" @if(Auth::user()->workshop_id == $list->id) selected @endif >{{$list->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="checkbox" id="tds_deduction" class="filled-in" >
                            <label for="tds_deduction">TDS Deduction</label>
                        </div>
                        <div class="col-md-6">
                            <label for="pan">PAN Number</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="pan" name="pan" class="form-control" placeholder="Enter PAN" value="{{ old('pan') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="tds_rate">TDS (%)</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" id="tds_rate" name="tds_rate" class="form-control" placeholder="Enter number 0-100" value="{{ old('tds_rate') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="address">Address</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea id="address" name="address" rows="1" class="form-control no-resize auto-growth" placeholder="Enter address(press ENTER for more lines)">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4>
                                Account Details
                            </h4>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <label for="bank_name">Vendor Bank</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="Enter bank name" value="{{ old('bank_name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="acc_no">Account Number</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" id="acc_no" name="acc_no" class="form-control" placeholder="Enter account number " value="{{ old('acc_no') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="ifsc">IFSC</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="ifsc" name="ifsc" class="form-control" placeholder="Enter ifsc " value="{{ old('ifsc') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="branch">Branch Address</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea id="branch" name="branch" rows="1" class="form-control no-resize auto-growth" placeholder="Enter branch address(press ENTER for more lines)">{{ old('branch') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 ">
                            <label for="doc_img">File Upload</label>
                            <div class="form-group form-float">
                                <div class="form-line ">
                                    <div class="fallback">
                                        <input name="voucher_img" id="voucher_img" class="form-control" type="file" placeholder="img only"  accept="image/x-png,image/gif,image/jpeg" title="image/x-png,image/gif,image/jpeg 5MB" max-size=5120/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <label for="remark">Remark</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea id="remark" name="remark" rows="1" class="form-control no-resize auto-growth" placeholder="Enter remark(press ENTER for more lines)">{{ old('remark') }}</textarea>
                                </div>
                            </div>
                        </div> -->
                    </div>
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

<script>
    $('#tds_deduction').change(function(){
        if($(this).prop('checked') == true){
            $('#pan').prop('required', true);
            $('#tds_rate').prop('required', true);
        }
        else{
            $('#pan').prop('required', false);
            $('#tds_rate').prop('required', false);
        }
    });
</script>

@endsection
