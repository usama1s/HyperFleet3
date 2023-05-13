 {{-- Drivers Permissions --}}
 <div class="col-sm-4">
     <div class="well">
         <h4>Drivers</h4>
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
                                     <input type="checkbox" class="custom-control-input" id="driver-create"
                                         slug="driver" p-type="create" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="driver-create"></label>
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
                                     <input type="checkbox" class="custom-control-input" id="driver-edit" slug="driver"
                                         p-type="edit" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="driver-edit"></label>
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
                                     <input type="checkbox" class="custom-control-input" id="driver-view" slug="driver"
                                         p-type="view" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="driver-view"></label>
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
                                     <input type="checkbox" class="custom-control-input" id="driver-delete"
                                         slug="driver" p-type="delete" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="driver-delete"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                 </tbody>
             </table>

         </div>
     </div>
 </div>
 {{-- Drivers Permissions End--}}