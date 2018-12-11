@extends('layouts.app')

@section('content')

<script>
    $(function(){
        $("#workshop").change(function(){
            var id = $(this).val();
            if(id == '0'){
                window.location.href = '{{url('dashboard')}}'
            }
            else if(id != ''){
                window.location.href = '{{url('dashboard')}}'+'?location='+id;
                //location.reload();
            }
        });
    })
</script>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    DASHBOARD
                </h2>
                @if(Auth::user()->user_type == 1)
                <div class="header-dropdown m-r--5">
                    <div class=" col-md-3 form-line-label">
                        <label  for="workshop">Location</label>
                    </div>
                    <div class="col-md-9 m-t--10">
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control" id="workshop" name="workshop">
                                    <option value="0">All</option>
                                    @foreach($workshops as $key=>$value)
                                    <option value="{{$value->id}}" 
                                        @if(isset($_GET['location']))
                                        @if($_GET['location'] == $value->id) selected @endif 
                                        @endif >{{$value->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Deposits</h2>
            </div>
            <div class="body">
                <canvas id="bar_chart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Expenses</h2>
            </div>
            <div class="body">
                <canvas id="bar_chart2" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Expense</h2>
            </div>
            <div class="body">
                <canvas id="pie_chart1" height="150"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Asset</h2>
            </div>
            <div class="body">
                <canvas id="pie_chart2" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <!-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Balance Remaining
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Balance</th>
                                <th>Last Expense date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> -->
    
</div>

@endsection
