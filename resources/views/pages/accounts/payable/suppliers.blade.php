
@php
use App\Models\Booking_invoice;
use App\Models\Supplier;
use App\Models\User;

$users = User::where("role","3")->get();


@endphp

@extends('layouts.app')

@section('title', "Supplier's Payable")

@section('breadcrumb')

<div class="page_sub_menu">

<button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>


</div>

@endsection

@section('content')


<form action="#" method="POST" id="record-form">
@csrf

<div class="table-scoller">
<table id="manage_supplier_payable" style="width:100%" class="table table-hover">
<thead>
    <tr>
        <th id="thcheck" data-orderable="false"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
        <th>#</th>
        <th>Supplier#</th>
        <th>Supplier Name</th>
        <th>Bookings Complete</th> 
        <th>Total Profit</th>
        <th>Supplier Profit</th>
        <th>Admin Profit</th>
        <th>Supplier Dues</th>
        <th>Supplier %</th>
        
    </tr>

   
</thead>
<tbody>
    @php
    $sno =1;
    $total_profit= 0;
    $total_supplier = 0;
    $total_admin = 0;
    @endphp

  
 @foreach ($users as $user)

 @php
     $commission = (int) $user->supplier->commission;
     $supplier_due = $user->supplier->credit;
     $earn_by_supplier = Supplier::totalProfit($user->id);
     
     $supplier_profit = $earn_by_supplier * ($commission / 100);

     $admin_profit = $earn_by_supplier - $supplier_profit;
     
     
     
      $total_profit += $earn_by_supplier;
      $total_supplier += $supplier_profit;
      $total_admin += $admin_profit;
 @endphp
     
 <tr>
     <td><input type="checkbox" name="" id=""></td>
     <td>{{$sno++}}</td>
     <td>{{$user->id}}</td>
     <td>
         {{$user->first_name}} {{$user->last_name}}
        
         <span class="action_wapper2">

          
         <a class="text-info view_booking mr-2" id="{{$user->id}}" href="{{ route('account.supplierbyid',$user->id) }}"><i
                title="view" data-toggle="tooltip" class="fa fa-eye"></i></a> 
          
        </span>
    </td>
    
     <td>{{ Supplier::completeBookings($user->id)}}</td>
   
     <td>{{Config('currency-symbol')}} {{ $earn_by_supplier }}</td>
     <td>{{Config('currency-symbol')}} {{  $supplier_profit }}</td>
     <td>{{Config('currency-symbol')}} {{  $admin_profit }}</td>
     <td>{{Config('currency-symbol')}} {{$supplier_due}}</td>
     <td>{{$commission}}%</td>
 </tr>

 @endforeach

 

</tbody>

<tr role="row">
    <td>Total</td>
    <td></td>
    <td></td>
    <td></td>
    <td>
        {{Config('currency-symbol')}} {{ $total_profit }}
    </td>
    <td>
        {{Config('currency-symbol')}} {{ $total_supplier }}
    </td>
    <td>
        {{Config('currency-symbol')}} {{ $total_admin }}
    </td>
    
</tr>

</table>
</div>
</form>
@endsection

@section('js')

<script>

$(document).ready(function() {
    if('{{ count($users) }}' < 1){
            $("#thcheck").remove();
                     
        }

   
    var table = $('#manage_supplier_payable').DataTable({
        "lengthMenu": [[05, 25, 50, -1], [05, 25, 50, "All"]],
        "pageLength": 50,
        paging: true,
        dom: '<"row"<"col-md-4"l><"col-md-4"B><"col-md-4"f>r>tip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
       
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



  
</script>

@endsection