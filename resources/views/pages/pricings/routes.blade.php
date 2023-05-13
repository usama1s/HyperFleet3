@extends('layouts.app')

@section('title', 'Pricing Routes')

@section('breadcrumb')
{{-- <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home </a></li>
    <li class="breadcrumb-item"><a href="{{url('pricings')}}">Pricing Routes </a></li>
    <li class="breadcrumb-item active">Manage</li>
</ol>

</ol> --}}
<div class="page_sub_menu">
    
    @can('pricing-edit')
        <a class="btn btn-sm btn-success" href="{{ url('/pricing/routes/create', [$pricing->id]) }}">Add Route</a>
    @endcan
</div>
@endsection

@section('content')
<table id="manage_pricings" style="width:100%" class="table table-hover responsive">
    <thead>
        <tr>
            <th data-orderable="false" id="thcheck"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
            <th>#</th>
            <th>Pickup Point</th>
            <th>Dropoff point</th>
            <th>Pickup Radius</th>
            <th>Dropoff Radius</th>
            <th>Price</th>
            <th>Distance</th>
            <th>Type</th>
            <th>Actions</th>

        </tr>
    </thead>
    <tbody>

        @php
        $sno =1;
        @endphp

        @foreach ($pricing_routes as $route)
        <tr row_id="{{$sno - 1}}">
        <td><input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $route->id }}"></td>
            <td>
                {{ $sno++}}

            </td>
            <?php
                $start_point = $route->start_point;
                $end_point = $route->end_point;
             ?>
            <td>{{$start_point->address}}</td>
            <td>{{$end_point->address}}</td>
            <td>{{$route->start_radius}}</td>
            <td>{{$route->end_radius}}</td>
            <td>{{Config('currency-symbol')}}{{$route->price}}</td>
            <td>{{$route->distance}}</td>
            <td>
                @if($route->isValidForReturn == 1)
                    <span class="badge badge-success">Return Trip</span>
                @else
                    <span class="badge badge-warning">N/A</span>
                @endif
            </td>
            <td>
                @can('pricing-edit')
                <a class="btn btn-sm btn-success" href="{{ url('/pricing/routes/edit', [$route->id]) }}"><i class="fa fa-edit"></i></a>
                @endcan
                @can('pricing-delete')
                <a class="btn btn-sm btn-danger" href="{{ url('/pricing/routes/delete', [$route->id]) }}"><i class="fa fa-trash"></i></a>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
     
    @if($pricing_routes->hasPages())
    <tr>
        
        <td colspan="10">
            {{ $pricing_routes->links() }}
        </td>
     
    </tr>
    @endif
       
</table>
@endsection

@section('js')

<script>
   
    $(document).ready(function() {


        $('[data-toggle="tooltip"]').tooltip();
         table = $('#manage_pricings').DataTable({
            "paging": false,
            "searching": false
        });

    });

        
    
</script>

@endsection