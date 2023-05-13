
@php
    use App\Models\Booking_invoice;
@endphp

@extends('layouts.app')

@section('title', 'Manage Invoices')

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
<form action="{{ route("booking-invoice.bulkdestroy") }}" method="POST" id="record-form">
    @csrf
<table id="manage_invoices" style="width:100%" class="table table-hover responsive">
    <thead>
        <tr>
            <th id="thcheck" data-orderable="false"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
            <th>#</th>
            <th>Invoice#</th>
            <th>Customer Name</th>
            <th>Status</th>
            <th>Created at</th>
            <th>Total</th>
            <th>Remaining</th>
            
        </tr>
    </thead>
    <tbody>
        @php
        $sno =1;
        $total_paid = 0;
        $total_unpaid = 0;
        @endphp

        @foreach ($invoices as $invoice)
        <tr>
            
            <td class="multi-select-ids-td">
                <input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $invoice->id }}">
            
            </td>
            
            <td>
                {{ $sno++}}
            </td>
            
            <td>
            <a href="{{ route('booking-invoice.show',$invoice->id)}}">
                Invoice#{{ $invoice->id }}
            </a>

            <span class="action_wapper2">
                @can('accounts-delete')
                    <a class="text-danger" onclick="(function(e){e.preventDefault();record_delete(e)})(event)" href="{{route('booking-invoice.destroy',$invoice->id)}}"><i class="fa fa-trash"></i></a>
                @endcan
            </span>
            </td>

            <td>
                {{ Booking_invoice::getCustomerName($invoice) }}
            </td>

            <td>
                {{ $invoice->status }}
            </td>

            <td>
                
                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->created_at)->format('Y-m-d') }}
            
            </td>

            <td>
                @if ( $invoice->status == "paid")
                
                    {{Config('currency-symbol')}} {{ Booking_invoice::getTotal($invoice) }}

                    @php
                        $total_paid =  $total_paid +  Booking_invoice::getTotal($invoice)
                    @endphp

                @else
                {{Config('currency-symbol')}}0
                @endif
            </td>

            <td>
                @if ( $invoice->status == "unpaid")
                
                {{Config('currency-symbol')}}{{ Booking_invoice::getTotal($invoice) }} 

                @php
                     $total_unpaid =  $total_unpaid +  Booking_invoice::getTotal($invoice)
                @endphp

                @else
                {{Config('currency-symbol')}}0
                @endif
            </td>

           
        </tr>
        @endforeach

    </tbody>


    <tr>
        <th colspan="6">Total</th>
        <th>
            {{Config('currency-symbol')}} {{ $total_paid }}
        </th>
        <th>
            {{Config('currency-symbol')}} {{ $total_unpaid }}
        </th>
        
    </tr>

     <tr>
            <td colspan="10">
                {{-- {{ $invoices->links() }} --}}
            </td>
    </tr>
</table>
</form>
@endsection

@section('js')

<script>

    $(document).ready(function() {
          
        if('{{ count($invoices) }}' < 1){
            $("#thcheck").remove();
                     
        }
       
        var table = $('#manage_invoices').DataTable({
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

        $('select[name="manage_invoices_length"]').addClass("form-control");
        $("#manage_invoices_filter input").addClass('form-control')
       

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