 <!-- Modal -->
 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="pills-bookinginfo-tab" data-toggle="pill" href="#pills-bookinginfo" role="tab" aria-controls="pills-bookinginfo" aria-selected="true">Booking</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="pills-vehicle-tab" data-toggle="pill" href="#pills-vehicle" role="tab" aria-controls="pills-vehicle" aria-selected="false">Vehicle</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="pills-driver-tab" data-toggle="pill" href="#pills-driver" role="tab" aria-controls="pills-driver" aria-selected="false">Driver</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-bookinglogs-tab" data-toggle="pill" href="#pills-bookinglogs" role="tab" aria-controls="pills-bookinglogs" aria-selected="false">Booking Log</a>
                      </li>
                  </ul>
            </div>
            <div class="modal-body">              
                    <div id="booking_detail_modal">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active p-2" id="pills-bookinginfo" role="tabpanel" aria-labelledby="pills-bookinginfo-tab">
                         <ul class="text-left booking_modal_list">
                            <h4>Booking Info:</h4>
                            <div class="row">
                                <li class="col-md-6">
                                    <strong>Pickup Point:</strong>
                                    <span id="pickup-point"></span>
                                </li>
                                <li class="col-md-6"><strong>Drop-off/Duration:</strong> 
                                    <span id="dropoff"></span>
                                    <span id="duration"></span>
                                </li>
                            </div>
                            <div class="row">
                                <li class="col-md-6">
                                    <strong>Pickup Date:</strong>
                                    <span id="pickup-date"></span>
                                </li>                                                        
                                <li class="col-md-6"><strong>Pickup Time:</strong>
                                    <span id="pickup-time"></span>
                                </li>
                            </div>
                            <div class="row">
                                <li class="col-md-6"><strong>Price:</strong>
                                    <span id="price"></span>
                                </li>
                                <li class="col-md-6"><strong>Flight Number:</strong>
                                    <span id="flightnumber"></span>
                                </li>
                            </div>
                            <div class="row">        
                                <li class="col-md-6"><strong>No of Bags:</strong>
                                    <span id="bags"></span>
                                </li>
                                <li class="col-md-6"><strong>No of Adults:</strong>
                                    <span id="adults"></span>
                                </li><br/><br/>
                            </div>
                             <h4>Customer Info:</h4>
                            <div class="row">
                             <li class="col-md-6"><strong>Name:</strong>
                                <span id="c-name"></span>
                            </li>                            
                             <li class="col-md-6"><strong>Email:</strong>
                                <span id="c-email"></span>
                            </li></div>
                            <div class="row">
                             <li class="col-md-6"><strong>Contact:</strong>
                                <span id="c-contact"></span>
                            </li>
                                <li class="col-md-6"><strong>Pickup Sign:</strong>
                                    <span id="pickup_sign"></span>
                                </li></div>
                         </ul>
                        </div>
                        <div class="tab-pane fade" id="pills-vehicle" role="tabpanel" aria-labelledby="pills-vehicle-tab">
                            <ul class="text-left booking_modal_list vehicle_data">
                                <img id="vehicle-img" class="no-light-box" src="" style="width:100%;height: 200px;object-fit: contain;">
                                <li><strong>Vehicle Maker:</strong>
                                    <span id="vehicle-make"></span>
                                </li>
                                <li><strong>Vehicle Class:</strong>
                                    <span id="v-class"></span>
                                </li>
                                <li><strong>Vehicle Colour:</strong>
                                    <span id="v-color"></span>
                                </li>
                            </ul>
                            <div class="no-vehicle">No Vehicle assigned</div> 
                        </div>
                        <div class="tab-pane fade" id="pills-driver" role="tabpanel" aria-labelledby="pills-driver-tab">
                            <ul class="text-left booking_modal_list driver_data">
                                <img id="driver-img" class="no-light-box" src="" style="width:100%;height:200px;object-fit:contain;">
                                <li><strong>Name:</strong> 
                                    <span id="d-name"></span>
                                </li>
                                <li><strong>Contact:</strong> 
                                    <span id="d-contact"></span>
                                </li>
                                <li><strong>Email:</strong>
                                    <span id="d-email"></span>
                                </li>
                            </ul>
                            <div class="no-vehicle">No Driver assigned</div> 
                        </div>
                        <div class="tab-pane fade" id="pills-bookinglogs" role="tabpanel" aria-labelledby="pills-bookinglogs-tab">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Log</th>
                                        <th>Time</th>
                                        <th>Updated By</th>
                                    </tr>
                                </thead>
                                <tbody id="booking_log">
                                </tbody>     
                            </table>
                        </div>
                    </div>            
                </div> {{-- Booking Detail modal ends --}}
            </div>
            <div class="modal-footer">               
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                
            </div>
        </div>
    </div>
</div>