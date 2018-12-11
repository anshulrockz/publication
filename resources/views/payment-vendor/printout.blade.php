
<script type="text/javascript"
    src="http://jqueryjs.googlecode.com/files/jquery-1.3.1.min.js"> </script>

<style>
table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    /*border: 1px solid #ddd;*/
}

th, td {
    /* text-align: left; */
    padding: 4px;
}

tr:nth-child(even) {
    background-color: #f2f2f2
}

    tfoot tr th:nth-child(2), th{
        text-align: center;
    }
    
    tfoot tr th{
        text-align: right;
    }
    
    tfoot tr, thead tr{
        border-top:1px double;
        border-bottom:1px double;
    }
    
    tfoot tr{
        border-top:1px double;
        border-bottom:1px double;
    }
    
    td:nth-child(5), td:nth-child(4), td:nth-child(3){
        text-align: right;
    }
</style>

<style>



@media print {
    table {page-break-after: always;}
}

</style>

@php
    $chunk_count = 30;
    $debit = 0;
    $credit = 0;
    $company = getAllFromID(Auth::user()->company_id, "companies") ;
    $workshop = getAllFromID($vendor->location, "workshops") ;
    $br = "<br>";
    $ind=0;
@endphp

<h3 style="width:100%;text-align:center;margin:1px"><u>{{ $company->name }} </u></h3>
<h5 style="width:100%;text-align:center;margin:1px">{{ trim(preg_replace("/\s+/", ' ', $workshop->address)) }}</h5>
<h5 style="width:100%;text-align:center;margin:1px"> {{ $workshop->gst }}</h5> 
<h4 style="width:100%;text-align:center;margin:3px">{{ $vendor->name }}</h4>
<h5 style="width:100%;text-align:center;margin:1px"> {{ $vendor->address }} </h5>
<h5 style="width:100%;text-align:center;margin:1px"> {{ $vendor->gst }} </h5>

@foreach( $transaction->chunk($chunk_count) as $chunk)

@php echo $br @endphp

@if($ind>0)
<h3 style="width:100%;text-align:center;margin:2px"><u>{{ $company->name }} </u></h3>
<h4 style="width:100%;text-align:center;margin:5px">{{ $vendor->name }}</h4>

@endif

<h4 style="width:100%;text-align:center;margin:5px">{{ $statement }}</h4>

<table class="compact table dataTable">
    <thead>
        <tr>
            <th>Date</th>
            <th>Particulars</th>
            <th class="debit">Debit</th>
            <th class="credit">Credit</th>
        </tr>
    </thead>
    <tbody>
        @if($ind++>0)
        <tr>
            <th></th>
            <th>Brought Forward</th>
            <th>{{$debit}} </th>
            <th>{{$credit}} </th>
        </tr>
        @endif
        @foreach( $chunk as $key=>$list)
        @php
            $debit += $list->debit;
            $credit += $list->credit;
            $br ="<br>";
        @endphp
        <tr>
            <td>{{date_format(date_create($list->invoice_date),"d/m/Y")}}</td>
            <td>{{$list->particulars}}</td>
            <td class="debit">{{$list->debit}} </td>
            <td class="credit">{{$list->credit}} </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        @if($key == count($transaction)-1 )
        <tr>
            <th></th>
            <th>Total</th>
            <th>{{$debit}} </th>
            <th>{{$credit}} </th>
        </tr>
        @php
            $closing = $debit - $credit;
            if($closing<1){
                $clo_deb = $closing;
                $clo_cre = 0;
            }
            else{
                $clo_deb = 0;
                $clo_cre = $closing;
            }
        @endphp
        <tr>
            <th></th>
            <th>Closing Balance</th>
            <th>{{abs($clo_deb)}} </th>
            <th>{{abs($clo_cre)}} </th>
        </tr>
        <tr>
            <th></th>
            <th>Grand Total</th>
            <th>{{abs($debit+$clo_deb)}} </th>
            <th>{{abs($credit+$clo_cre)}} </th>
        </tr>
        @else
        <tr>
            <th></th>
            <th>Carried Over</th>
            <th>{{$debit}} </th>
            <th>{{$credit}} </th>
        </tr>
        @endif
        <!--<tr> -->
        <!--    <th></th>-->
        <!--    <th>Balance</th>-->
        <!--    <th></th>-->
        <!--    <th class="balanceTol">&#8377; {{round($balance += $debit - $credit,2)}}</th>-->
        <!--</tr>-->
    </tfoot>
</table>


@endforeach

<script>

window.onload = function () {
    
    // window.print();
}

    document.title = "Statement";
    window.print();
    // window.close();

</script>

