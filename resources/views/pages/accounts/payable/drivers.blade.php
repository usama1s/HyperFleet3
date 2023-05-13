
@php
use App\Models\Booking_invoice;
use App\Models\Supplier;
use App\Models\User;

$users = User::where("role","4")->get();


@endphp

@extends('layouts.app')

@section('title', "Driver Payables")

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
        <th>Driver#</th>
        <th>Driver Name</th>
        <th>Bookings Complete</th> 
        <th>Driver Dues</th>
        <th>Payment Type</th>
        <th>Driver % / Salary</th>
        
    </tr>

   
</thead>
<tbody>
    @php
    $sno =1;
    $total_driver_payable= 0;
    @endphp

  
 @foreach ($users as $user)

 @php
    $driver_due = 0;

   

    if($user->driver->payment_type =="fixed"){
        $driver_due =  $user->driver->dueSalary();
    }else{
        $driver_due = 0;
    }

    $total_driver_payable = $total_driver_payable + $driver_due;

 @endphp
     
 <tr>

     <td><input type="checkbox" name="" id=""></td>
     <td>{{$sno++}}</td>
     <td>{{$user->id}}</td>
     <td>
         {{$user->fullname()}}
        
         <span class="action_wapper2">

          
         <a class="text-info view_booking mr-2" id="{{$user->id}}" href="{{ route('account.driverbyid',$user->id) }}"><i
                title="view" data-toggle="tooltip" class="fa fa-eye"></i></a> 
          
        </span>
    </td>
    
     <td>{{ $user->booking()->whereStatus("finish")->count()}}</td>
   
    
     <td>{{Config('currency-symbol')}} {{   $driver_due }}</td>
     <td>{{ $user->driver->payment_type}}</td>
     <td>
        @if ($user->driver->payment_type =="fixed")
              {{ config('currency-symbol')}}{{ $user->driver->amount }}
          @else
            {{ $user->driver->amount }}%
          @endif
     </td>
 </tr>

 @endforeach

 

</tbody>

<tr role="row">
    <td>Total</td>
    <td></td>
    <td></td>
    <td></td>
    <td>
    </td>
    <td>
        {{Config('currency-symbol')}} {{ $total_driver_payable }}
    </td>
    <td></td>
    
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