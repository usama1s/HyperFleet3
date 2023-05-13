
@php
use App\Models\Booking;
use App\Models\Booking_invoice;
use App\Models\Supplier;
use App\Models\User;

$user = User::find($id);

$invoices = DB::table('bookings')
            ->join('booking_invoices', 'bookings.id', '=', 'booking_invoices.booking_id')
            ->where("booking_invoices.status","paid")
            ->where("bookings.supplier_id",$user->id)
            ->select('booking_invoices.*','bookings.grand_price')->get();

// dd($invoices);
$supplier_commision = $user->supplier->commission;
$supplier_credits = $user->supplier->credit;
// $admin_commission = 100 - $supplier_commision;

$sno =1;
$total_profit= 0;
$total_supplier = 0;
$total_admin = 0;

$dues =0;
$balance =0;
if($supplier_credits > 0){

    $dues =  $supplier_credits;

}else{
    $balance =  -($supplier_credits);
}




@endphp

@extends('layouts.app')

@section('title', $user->first_name." ".$user->last_name )

@section('breadcrumb')

<div class="page_sub_menu">

    <h2>
        Commision {{$supplier_commision}}%
    </h2>


    <h3>
        Dues  {{Config('currency-symbol')}} {{$dues}}
    </h3>

    <h3>
        Balance  {{Config('currency-symbol')}} {{$balance}}
    </h3>

    @if (auth()->user()->role == 1)
        
    @include('pages.accounts.payable.receive-by-hand')
    
    @elseif(auth()->user()->role == 3)
     
    @include('pages.accounts.payable.payonline')

    @endif



<button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>


</div>

@endsection

@section('content')

<div class="row mb-3">
<div class="col-md-4">
    <label>To:</label>
    <input class="form-control" type="date" id="date_to">
</div>
<div class="col-md-4">
    <label>From:</label>
    <input class="form-control" type="date" id="date_from">
</div>
</div>
<form action="#" method="POST" id="record-form">
@csrf
<table id="manage_supplier_payable" style="width:100%" class="table responsive table-hover">
<thead>
    <tr>
        {{-- <th id="thcheck" data-orderable="false"><input type="checkbox" id="all-checkbox-seleted-otp"></th> --}}
        <th>#</th>
        <th>Booking#</th>
        <th>status</th>
        <th>method</th>
        <th>Total Income</th>
        <th>Supplier Income</th>
        <th>Admin Income</th>
        <th>Date</th>
        
    </tr>

    
</thead>
<tbody>
   

@foreach ($invoices as $invoice)
    

 @php
    
    $earn_by_supplier = $invoice->grand_price;
    $supplier_profit = $earn_by_supplier * ($supplier_commision / 100);

    $admin_profit = $earn_by_supplier -  $supplier_profit;

    $total_profit += $earn_by_supplier;
    $total_supplier += $supplier_profit;
    $total_admin += $admin_profit;
    
 @endphp
     

 <tr>
    {{-- <td><input type="checkbox" name="" id=""></td> --}}
    <td>{{$sno++}}</td>
    <td>
        <a href="{{ route('booking-invoice.show',$invoice->id)}}">
            Invoice#{{ $invoice->id }}
        </a>
    </td>
     
    <td>{{ $invoice->status }}</td>
    <td>{{  Booking::getPaymentMethod($invoice->booking_id) }}</td>
    <td>
        {{Config('currency-symbol')}} {{ $earn_by_supplier }}
    </td>
    <td>
        {{Config('currency-symbol')}} {{ $supplier_profit }}
    </td>
    <td> {{Config('currency-symbol')}} {{ $admin_profit }}</td>
    <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->updated_at)->format('Y-m-d') }}</td>
    
     
 </tr>

 @endforeach


</tbody>



    <tr>
        <th colspan="4">Total</th>
        <th>
            {{Config('currency-symbol')}}
            <span id="total_profit">
                {{ $total_profit }}
            </span>
        </th>
        <th>
            {{Config('currency-symbol')}}
            <span id="total_supplier">
                {{ $total_supplier }}
            </span>
        </th>
        <th>
            {{Config('currency-symbol')}}
            <span id="total_admin">
              {{ $total_admin }}
            </span>
        </th>
        
    </tr>







</table>
</form>
@endsection

@section('js')

<script>

$(document).ready(function() {
      

   
    var table = $('#manage_supplier_payable').DataTable({
        "lengthMenu": [[05, 25, 50, -1], [05, 25, 50, "All"]],
        "pageLength": 50,
        paging: true,
        dom: '<"row"<"col"l><"col"B><"col"f>r>tip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
       
    });

    

    $('#date_to, #date_from').change( function() {
     table.draw();
    
     var rows = table.rows( { search: 'applied' } ).select().data();
    
     var total_profit = 0;
     var total_supplier = 0;
    var total_admin = 0;

     for(i=0; i<rows.length; i++){
      var data = rows[i];
        total = parseFloat(data[4].slice(1)); //data[4] is total in row
        supplier_profit = parseFloat(data[5].slice(1)); //data[5] is supplier in row
        admin_profit = parseFloat(data[6].slice(1)); //data[5] is payable in row

        total_profit += total;
        total_supplier += supplier_profit;
        total_admin += admin_profit;

     }

    $("#total_profit").text(total_profit.toFixed(2));
    $("#total_supplier").text(total_supplier.toFixed(2));
    $("#total_admin").text(total_admin.toFixed(2));


    });

    $('select[name="manage_supplier_payable_length"]').addClass("form-control");
    $("#manage_supplier_payable_filter input").addClass('form-control');


    $("table th").click(function(e){
        index = e.target.cellIndex;
       
       
        var table = $(this).parents("table");

     
        
        $(table).each(function(i,v){
            var tbody = $(v).children("tbody").eq(0);
            $(tbody).children().each(function(i, tr){
                $(tr).children().removeClass("bg-info");
               

               
                    $(tr).children().eq(index).addClass("bg-info");

               
            })
        });
    });
   

});


 /* Custom filtering function which will search data in column four between two values */
 $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var to =  $('#date_to').val();
            var from =  $('#date_from').val();
            var date = data[7] || 0; // use data for the age column

            if(
                (to <= date && from >= date) ||
                (to <= date && from == "") ||
                (from >= date && to == "")
             ){

                return true;
            }else{
                return false;
            }
    
            // if ( ( isNaN( to ) && isNaN( from ) ) ||
            //     ( isNaN( to ) && age <= to ) ||
            //     ( to <= age   && isNaN( from ) ) ||
            //     ( to <= age   && age <= from ) )
            // {
               
            // }
            // return false;

           
        }
    );
  
</script>

@endsection