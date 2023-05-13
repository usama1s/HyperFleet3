
@php
    $target_url =  route('pricings.create')."?supplier_id=".$user->id;
    
@endphp
<a href="{{ $target_url }}" class="btn btn-info float-right mb-3">Add New Pricing</a>

<div class="table-responsive">
    <table id="manage_pricings" style="width:100%" class="table table-hover responsive">
        <thead>
            <tr>
                <!-- <th data-orderable="false" id="thcheck"><input type="checkbox" id="all-checkbox-seleted-otp"></th> -->
                <th>#</th>
                <th>Title</th>
                <th>Up to 10.0 km</th>
                <th>10.0 km-100.0 km</th>
                <th>100.0 km-200.0 km</th>
                <th>200.0 km or more</th>
                <th>Per Hour</th>
                <th>Per Day</th>
                <th>Minimum Hours</th>
                <th>Additional Pickup</th>
                <th>Additional Waiting</th>
                <th>Airport Pickup Fee</th>
                <th>Connecting Job</th>
                <th>Approval</th>
                <!-- <noscript> -->
                    <th>Actions</th>
                <!-- </noscript> -->
    
            </tr>
        </thead>
        <tbody>
    
            @php
            $sno =1;
            @endphp
    
            @foreach ($pricing_list as $list)
            <tr row_id="{{$sno - 1}}">
            <!-- <td><input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $list->id }}"></td> -->
                <td>
                    {{ $sno++}}    
                </td>
                <td>{{$list->title}}</td>
                <td>{{Config('currency-symbol')}}{{$list->up_to_ten}}</td>
                <td>{{Config('currency-symbol')}}{{$list->ten_to_hundred}}</td>
                <td>{{Config('currency-symbol')}}{{$list->hundred_to_twoHundred}}</td>
                <td>{{Config('currency-symbol')}}{{$list->twoHundred_and_above}}</td>
                <td>{{Config('currency-symbol')}}{{$list->price_per_hour}}</td>
                <td>{{Config('currency-symbol')}}{{$list->price_per_day}}</td>
                <td>{{$list->minimum_hours}}</td>
                <td>{{Config('currency-symbol')}}{{$list->pickup_fee_per_pickup}}</td>
                <td>{{Config('currency-symbol')}}{{$list->waiting_time_per_min}}</td>
                <td>{{Config('currency-symbol')}}{{$list->airport_pickup_fee}}</td>
                <td>{{$list->job_discount}}%</td>
                <!-- <noscript>
                    <style>
                        .action_wapper2 {
                            display: none;
                        }
    
                        .action_wapper {
                            display: none;
                        }
                    </style> -->

                    <td>
                            
                        {{-- admin show button with functionlity that can approve  --}}
                        @if (auth()->user()->role == 1)
                        <form action="{{ route('pricing.approved_and_disapproved',['id'=> $list->id]) }}" method="get">
                            <button  class="btn {!!  $list->admin_approval ? 'btn-success' : 'btn-danger' !!} btn-sm">
                                {!!  $list->admin_approval ? "Approved" : "Pending" !!}
                            </button>
                        </form>
                        @endif
    
                        {{-- supplier can view only  --}}
                        @if (auth()->user()->role == 3)
                            <button disabled  class="btn {!!  $list->admin_approval ? 'btn-success' : 'btn-danger' !!} btn-sm">
                                {!!  $list->admin_approval ? "Approved" : "Pending" !!}
                            </button>
                        @endif
    
                    </td>
                    
                    <td>
                        <!-- <a class="btn btn-sm btn-info" href="{{ url('pricings')}}/{{$list->id}}">View</a> -->
                        @can('pricing-edit')
                        <a class="btn btn-sm btn-info" href="{{ url('/pricing/routes', [$list->id]) }}"><i class="fa fa-route"></i></a>
                        <a class="btn btn-sm btn-success" href="{{url('pricings')}}/{{$list->id}}/edit"><i class="fa fa-edit"></i></a>
                        @endcan
                        @can('pricing-delete')
                        <a class="btn btn-sm btn-danger" onclick="(function(e){e.preventDefault();record_delete(e)})(event)" href="{{ url('/pricing/delete', [$list->id]) }}"><i class="fa fa-trash"></i></a>
                        @endcan
                    </td>
                <!-- </noscript> -->
            </tr>
            @endforeach
        </tbody>
         
        @if($pricing_list->hasPages())
        <tr>
            
                <td colspan="10">
                    {{ $pricing_list->links() }}
                </td>
         
        </tr>
        @endif
           
    </table>
    
</div>
