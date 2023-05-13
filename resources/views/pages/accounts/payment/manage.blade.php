
@php
   
@endphp

@extends('layouts.app')

@section('title', 'Payments')

@section('breadcrumb')

<div class="page_sub_menu">
    
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
<form action="{{ route("payments.bulkdestroy")}}" method="POST" id="record-form">
    @csrf
<table id="manage_payments" style="width:100%" class="table table-hover responsive">
    <thead>
        <tr>
            <th id="thcheck" data-orderable="false"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
            <th>#</th>
            <th>Description</th>
            <th>Transaction#</th>
            <th>Currency</th>
            <th>Status</th>
            <th>Type</th>
            <th>Created at</th>
            
        </tr>
    </thead>
    <tbody>
        @php
        $sno =1;
       
        @endphp

        @foreach ($payments as $payment)
        <tr>
            
            <td class="multi-select-ids-td">
                <input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $payment->id }}">
            
            </td>
            
            <td>
                {{ $sno++}}
            </td>
            
            <td>
                @if ($payment->booking_id)
                    
                    Booking#{{ $payment->booking_id }}
                
                @endif

                @if ($payment->supplier_id)
                    
                Supplier#{{ $payment->supplier_id }}
            
            @endif
        

                <span class="action_wapper2">
                    @can('accounts-delete')
                    <a class="text-danger"  onclick="(function(e){e.preventDefault();record_delete(e)})(event)" href="{{route('payments.destroy',$payment->id)}}">Delete</a>
                    @endcan
                </span>
            </td>

            <td>
               {{ $payment->transaction_id}}
            </td>

            <td>
                {{ $payment->currency_code }}
            </td>

            <td>
               {{ $payment->payment_status}}
            </td>

            <td>
              {{ $payment->payment_type}}
            </td>

            <td>
                
                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->format('Y-m-d') }}
                
            </td>
        </tr>
        @endforeach

    </tbody>


   
</table>
</form>
@endsection

@section('js')

<script>

    $(document).ready(function() {


        if('{{ count($payments) }}' < 1){
            $("#thcheck").remove();
                     
        }
       
        var table = $('#manage_payments').DataTable({
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
        });

        $('select[name="manage_payments_length"]').addClass("form-control");
        $("#manage_payments_filter input").addClass('form-control')
       

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