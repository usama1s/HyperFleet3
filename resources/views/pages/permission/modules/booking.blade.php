 {{-- booking Permissions --}}
 <div class="col-sm-4">
     <div class="well">
         <h4>Booking</h4>
         <div id="sub_data10">
             <table class="table table-condensed">
                 <tbody>
                     <tr>
                         <th style="width: 100%;">Privilege</th>
                         <th></th>
                         <th>Action</th>
                     </tr>
                     <tr>
                         <td>Add</td>
                         <td></td>
                         <td>
                             <div class="form-group">
                                 <div class="custom-control custom-switch">
                                     <input type="checkbox" class="custom-control-input" id="booking-create"
                                         slug="booking" p-type="create" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="booking-create"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                     <tr>
                         <td>Edit</td>
                         <td></td>
                         <td>
                             <div class="form-group">
                                 <div class="custom-control custom-switch">
                                     <input type="checkbox" class="custom-control-input" id="booking-edit"
                                         slug="booking" p-type="edit" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="booking-edit"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                     <tr>
                         <td>View</td>
                         <td></td>
                         <td>
                             <div class="form-group">
                                 <div class="custom-control custom-switch">
                                     <input type="checkbox" class="custom-control-input" id="booking-view"
                                         slug="booking" p-type="view" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="booking-view"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                     <tr>
                         <td>Remove</td>
                         <td></td>
                         <td>
                             <div class="form-group">
                                 <div class="custom-control custom-switch">
                                     <input type="checkbox" class="custom-control-input" id="booking-delete"
                                         slug="booking" p-type="delete" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="booking-delete"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                 </tbody>
             </table>

         </div>
     </div>
 </div>
 {{-- booking Permissions End--}}