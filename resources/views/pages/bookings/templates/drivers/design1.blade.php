
<form method="POST" action="{{ route('bookings.assignDriverToBooking')}}">
    @csrf
    {{-- ROW ONE ADD NEW VEHICLE --}}
    <div class="row">
        <div class="col-md-12">
              <!-- SELECT2 EXAMPLE -->
              <div class="card card-default">
                <div class="card-header">
                  <h3 class="card-title">Assign Driver and Vehicle to Booking</h3>
      
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>             
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Drivers</label>
                       
                          <input type="hidden" name="booking_id" id="booking_id" value="{{$booking_id}}">
                     
                        <select class="form-control assign-to-vehicle" name="driver_id" id="booking_driver_id" class="height:50px">
                          <option value="">Select Driver</option>
                          
                            @forelse ($drivers as $driver)
                             @php
                                 $rejected = $d->BookingRejected($driver->user_id,$booking_id);
                                 $supplier = ($d->supplierName($driver->user_id)) ? '------ '.$d->supplierName($driver->user_id) .' (Supplier)' : '';
                             @endphp
                        <option value="{{ $driver->user_id }}">{{ $d->fullName($driver->user_id) }} (Driver)  {{ $supplier }} {{ ( $rejected) ? "------ (Once Rejected)" : ''}}</option>
                             
                            @empty
                              <option value="">No Driver Available</option>
                            @endforelse
                                               
                        </select>
                        @error('driver_id')
                        <div class="invalid-feedback" style="display:block;" role="alert">
                        Please Select the Driver.
                        </div>
                        @enderror            
                        
                               
                     
                      </div>
                    </div>


                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Vehicle</label>
                       
                          
                     
                          <select class="form-control assign-to-vehicle" name="vehicle_id" id="booking_vehicle_id" class="height:50px">
                            <option value="">Select Vehicle</option>
                            
                              @forelse ($vehicles as $vehicle)
                              
                               <option value="{{ $vehicle->id }}">{{ $vehicle->manufacturer}} {{ $vehicle->car_model}} {{ $vehicle->car_year}} - {{ $vehicle->license_plate }}</option>
                               
                              @empty
                                <option value="">No Vehicle Available</option>
                              @endforelse
                                                 
                          </select>
                          @error('vehicle_id')
                          <div class="invalid-feedback" style="display:block;" role="alert">
                          Please Select the Vehicle.
                          </div>
                          @enderror          
                        
                               
                     
                      </div>
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
    
      <div class="row">
        <div class="col-md-12">
          <div class="d-flex justify-content-end" style="margin: 50px 20px;">
              <input class="btn btn-lg btn-success" type="submit" value="Save" style="width:200px">
          </div>
        </div>
      </div>
    
    </form>