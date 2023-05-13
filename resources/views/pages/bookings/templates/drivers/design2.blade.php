

    {{-- ROW ONE ADD NEW VEHICLE --}}
    <div class="row">
        <div class="col-md-12">
              <!-- SELECT2 EXAMPLE -->
              <div class="card card-default">
                <div class="card-header">
                  <h3 class="card-title">Assign Driver to Booking</h3>
      
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>             
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                      
                        
                       
                       
                        <table  id="design_2_table" style="width:100%"class="table responsive">
                            <thead>
                            <tr>
                                <th>Sno</th>
                                <th>Image</th>
                                <th>Driver Name</th>
                                <th>Supplier Name</th>
                                <th>Vehicle No</th>
                                <th>Vehicle Class</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                          
                            @php
                                $sno = 1;
                            @endphp
                            @forelse ($drivers as $driver)
                             @php
                                 $rejected = $d->BookingRejected($driver->user_id,$booking_id);
                                 $supplier = ($d->supplierName($driver->user_id)) ?$d->supplierName($driver->user_id)  : 'Admin';
                             @endphp
                           
                             <tr>
                                <form method="POST" action="{{ route('bookings.assignDriverToBooking')}}">
                                    @csrf
                                <input type="hidden" name="booking_id" id="booking_id" value="{{$booking_id}}">
                                <input type="hidden" name="driver_id" id="driver_id" value="{{ $driver->user_id }}">
                                 <td>{{$sno++}}</td>
                                 <td><img class="manage-thumbnail" src="{{ asset('public/assets/drivers') }}/{{$driver->driver_image}}" width="80"></td>
                                 <td>{{  $d->fullName($driver->user_id) }}</td>
                                 <td>{{ $supplier }}</td>
                                 <td>{{ $d->withVehicle($driver->user_id,"license_plate") }}</td>
                                 <td>{{ App\Models\VehicleClass::getById($d->withVehicle($driver->user_id,"vehicle_class_id")) }}</td>
                                 <td><input type="submit" class="btn btn-sm btn-success"value="Assign Driver"></td>
                                </form>
                             </tr>
                              
                           
                             
                            @empty
                             <tr>
                                 {{-- <th colspan="7">No Driver Found</th> --}}
                                 
                                 <th>No Driver Found</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                
                             </tr>
                            @endforelse
                        </tbody>            
                        </table>
                                 
                     
                      
                    </div>
    
                 
                  </div>
                  <!-- /.row -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                 
                </div>
              </div>
              <!-- /.card -->
        </div>
      </div>


   

  