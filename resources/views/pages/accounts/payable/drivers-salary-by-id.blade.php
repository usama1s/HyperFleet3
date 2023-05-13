
@php
use App\Models\Booking;
use App\Models\Booking_invoice;
use App\Models\Supplier;
use App\Models\User;




@endphp

@extends('layouts.app')

@section('title', $user->fullname())

@section('breadcrumb')

<div class="page_sub_menu">

    <h2>
        Salary {{Config('currency-symbol')}} {{$user->driver->amount}}
    </h2>


    <h3>
        Payable  {{Config('currency-symbol')}} {{$user->driver->dueSalary()}}
    </h3>




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
        <th>Salary#</th>
        <th>Amount</th>
        <th>status</th>
        <th>Month</th>
        <th>Created at</th>
        <th>Action</th>
        
    </tr>

    
</thead>
<tbody>
   
@php
    $total = 0;    
@endphp
@foreach ($user->driver->salaries as $salary)
    
     @php
         $total += $salary->salary;
     @endphp

 <tr>
    {{-- <td><input type="checkbox" name="" id=""></td> --}}
    <td>{{$loop->iteration}}</td>
    <td>
        <a href="#">
            Pay Slip # {{$salary->id}}
        </a>
    </td>
     
    <td>{{Config('currency-symbol')}} {{ $salary->salary }}</td>
    <td>{{ $salary->status }}</td>
    <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $salary->salary_date)->format('M, Y') }}</td>
    
    <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $salary->salary_date)->format('Y-m-d') }}</td>

    <td>
        
        <a class="btn btn-sm btn-info" href="#"> View Pay Slip</a>

        @if ($salary->status == "unpaid")      
    <a class="btn btn-sm btn-success" href="{{ route('account.driver.salary.paid',$salary->id)}}"> Paid</a>
        @endif
    </td>

    
    
     
 </tr>

 @endforeach


</tbody>



    <tr>
        <th colspan="2">Total</th>
        <th>
            {{Config('currency-symbol')}}
            <span id="total_profit">
                {{$total}}
            </span>
        </th>
        <th></th>
        <th></th>
        
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
        reCalculate("custom-data-filter");
    });

    table.on( 'search.dt', function () {
        reCalculate();
    });

    function reCalculate(type = null){
       if(type == "custom-data-filter"){

            table.draw();
       }
    
    var rows = table.rows( { search: 'applied' } ).select().data();
   
    var total_salary = 0;

    for(i=0; i<rows.length; i++){
     var data = rows[i];
       total = parseFloat(data[2].slice(1)); //data[4] is total in row
       total_salary += total;
      
    }

   $("#total_profit").text(total_salary.toFixed(2));
    }

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
            var to = $('#date_to').val();
            var from = $('#date_from').val();

           

            // to = moment(to).format('MMM, YYYY');
            // from = moment(from).format('MMM, YYYY');

            var date = data[5] || 0; // use data for the age column

            // if(to == "Invalid date"){
            //     return true;
            // }
            
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